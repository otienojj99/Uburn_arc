<?php

namespace App\Services;

use App\Models\StockTransaction;
use App\Models\ProductItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockTransactionService
{
    public function getPaginated(array $filters = [], int $perPage = 15)
    {
        $query = StockTransaction::with(['productItem', 'warehouse', 'user'])
            ->when($filters['product_item_id'] ?? null, fn($q, $id) => $q->where('product_item_id', $id))
            ->when($filters['warehouse_id'] ?? null, fn($q, $id) => $q->where('warehouse_id', $id))
            ->when($filters['type'] ?? null, fn($q, $type) => $q->where('type', $type))
            ->when($filters['user_id'] ?? null, fn($q, $id) => $q->where('user_id', $id))
            ->when($filters['from_date'] ?? null, fn($q, $date) => $q->whereDate('created_at', '>=', $date))
            ->when($filters['to_date'] ?? null, fn($q, $date) => $q->whereDate('created_at', '<=', $date))
            ->when($filters['reference_type'] ?? null, fn($q, $type) => $q->where('reference_type', $type))
            ->when($filters['reference_id'] ?? null, fn($q, $id) => $q->where('reference_id', $id))
            ->when($filters['trashed'] ?? false, fn($q) => $q->onlyTrashed());

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Create a manual adjustment transaction.
     * This method directly updates the product's current stock.
     */
    public function createManualAdjustment(array $data): StockTransaction
    {
        return DB::transaction(function () use ($data) {
            $product = ProductItem::where('id', $data['product_item_id'])
                ->lockForUpdate()
                ->first();

            $oldStock = $product->current_stock;
            $newStock = $oldStock + $data['quantity_change'];

            if ($newStock < 0) {
                throw new \Exception('Insufficient stock. Current stock: ' . $oldStock);
            }

            $product->current_stock = $newStock;
            $product->save();

            $data['stock_before'] = $oldStock;
            $data['stock_after'] = $newStock;
            $data['type'] = 'adjustment';
            $data['user_id'] = Auth::id();

            return StockTransaction::create($data);
        });
    }

    public function delete(StockTransaction $transaction): bool
    {
        // Prevent deletion of non-adjustment transactions? Maybe only allow soft delete.
        return $transaction->delete();
    }

    public function restore(int $id): bool
    {
        $transaction = StockTransaction::onlyTrashed()->findOrFail($id);
        return $transaction->restore();
    }

    /**
     * Generic stock movement (purchase, sale, return, transfer, etc.)
     * 
     * @param int $productId
     * @param int $quantityChange (positive = stock in, negative = stock out)
     * @param string $type (purchase, sale, return_in, return_out, adjustment, transfer, etc.)
     * @param array $options (optional: warehouse_id, reference_type, reference_id, notes, batch_number, expiry_date)
     * @return StockTransaction
     */
    public function moveStock(int $productId, int $quantityChange, string $type, array $options = []): StockTransaction
    {
        try {
            \Log::info('Attempting stock movement', [
                'product_id' => $productId,
                'quantity_change' => $quantityChange,
                'type' => $type,
                'options' => $options,
            ]);
            return DB::transaction(function () use ($productId, $quantityChange, $type, $options) {
                $product = ProductItem::where('id', $productId)
                    ->lockForUpdate()
                    ->first();

                if (!$product) {
                    throw new \Exception('Product not found');
                }

                $oldStock = $product->current_stock;
                $newStock = $oldStock + $quantityChange;

                if ($newStock < 0) {
                    throw new \Exception('Insufficient stock. Current stock: ' . $oldStock);
                    \Log::warning('Stock movement failed due to insufficient stock', [
                        'product_id' => $productId,
                        'attempted_change' => $quantityChange,
                        'current_stock' => $oldStock,
                    ]);
                }

                // Update product stock
                $product->current_stock = $newStock;
                $product->save();

                // Create transaction record
                $transactionData = [
                    'product_item_id' => $productId,
                    'quantity_change' => $quantityChange,
                    'stock_before'    => $oldStock,
                    'stock_after'     => $newStock,
                    'type'            => $type,
                    'user_id'         => Auth::id(),
                    'warehouse_id'    => $options['warehouse_id'] ?? null,
                    'reference_type'  => $options['reference_type'] ?? null,
                    'reference_id'    => $options['reference_id'] ?? null,
                    'batch_number'    => $options['batch_number'] ?? null,
                    'expiry_date'     => $options['expiry_date'] ?? null,
                    'reason'          => $options['reason'] ?? null,
                    'notes'           => $options['notes'] ?? null,
                    'metadata'        => $options['metadata'] ?? null,
                ];

                return StockTransaction::create($transactionData);
            });
        } catch (\Exception $e) {
            \Log::error('Transaction failed', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
