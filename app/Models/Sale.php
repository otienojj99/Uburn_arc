<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use SoftDeletes;
    //
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */

    protected $fillable = [
        'order_number',
        'customer_id',
        'order_date',
        'required_date',
        'shipped_date',
        'delivered_date',
        'status',
        'subtotal',
        'tax',
        'shipping_cost',
        'discount',
        'total',
        'currency',
        'payment_status',
        'payment_method',
        'transaction_id',
        'shipping_method',
        'tracking_number',
        'shipping_address',
        'billing_address',
        'customer_notes',
        'staff_notes',
        'created_by',
        'updated_by',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */

    protected $casts = [
        'order_date'     => 'date',
        'required_date'  => 'date',
        'shipped_date'   => 'date',
        'delivered_date' => 'date',
        'subtotal'       => 'decimal:2',
        'tax'            => 'decimal:2',
        'shipping_cost'  => 'decimal:2',
        'discount'       => 'decimal:2',
        'total'          => 'decimal:2',
        'metadata'       => 'array',
        'deleted_at'     => 'datetime',
    ];

    /**
     * Default attribute values.
     *
     * @var array
     */

    protected $attributes = [
        'currency'       => 'NGN',
        'status'         => 'draft',
        'payment_status' => 'pending',
    ];

    // ========== RELATIONSHIPS ==========

    /**
     * Get the customer associated with this sale.
     */

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the items (line items) for this sale.
     */

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    /**
     * Get the user who created this sale.
     */

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this sale.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get all payments for this sale.
     */
    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }


      // ========== SCOPES ==========

    /**
     * Scope to filter by status.
     */

    public function scopeWhereStatus($query, $status)
    {
        return $query->where('status', $status);
    }



    /**
     * Scope to filter by payment status.
     */
    public function scopeWherePaymentStatus($query, $paymentStatus)
    {
        return $query->where('payment_status', $paymentStatus);
    }

    /**
     * Scope to filter by customer.
     */
    public function scopeForCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    /**
     * Scope to include sales created between dates.
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('order_date', [$startDate, $endDate]);
    }

    /**
     * Scope to include sales that are not cancelled or refunded.
     */
    public function scopeActive($query)
    {
        return $query->whereNotIn('status', ['cancelled', 'refunded']);
    }

    // ========== ACCESSORS ==========

    /**
     * Check if the order is paid.
     */
    public function getIsPaidAttribute(): bool
    {
        return $this->payment_status === 'paid';
    }

    /**
     * Get the total amount paid for this sale.
     */
    public function getTotalPaidAttribute(): float
    {
        return $this->payments()->where('status', 'completed')->sum('amount');
    }

    /**
     * Check if the order is shipped.
     */
    public function getIsShippedAttribute(): bool
    {
        return in_array($this->status, ['shipped', 'delivered', 'completed']);
    }

    /**
     * Get the balance due (if partial payment).
     * (Assumes total stored and you track payments elsewhere – can be extended.)
     */
    public function getBalanceDueAttribute(): float
    {
        // If you have a payments relationship, sum payments and subtract from total.
        // For now, return 0 if paid, else total.
        return max(0, $this->total - $this->total_paid);
    }

    /**
     * Check if the sale is fully paid.
     */
    public function getIsFullyPaidAttribute(): bool
    {
        return $this->balance_due <= 0;
    }
}
