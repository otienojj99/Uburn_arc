<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */

    protected $fillable = [
        'supplier_id',
        'purchase_number',
        'order_date',
        'expected_delivery_date',
        'delivery_date',
        'status',
        'subtotal',
        'tax',
        'shipping_cost',
        'other_cost',
        'total',
        'currency',
        'payment_terms',
        'payment_status',
        'shipping_method',
        'tracking_number',
        'shipping_address',
        'notes',
        'internal_notes',
        'requested_by',
        'approved_by',
        'approved_at',
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
        'order_date'              => 'date',
        'expected_delivery_date'  => 'date',
        'delivery_date'           => 'date',
        'approved_at'             => 'datetime',
        'subtotal'                => 'decimal:2',
        'tax'                     => 'decimal:2',
        'shipping_cost'           => 'decimal:2',
        'other_cost'              => 'decimal:2',
        'total'                   => 'decimal:2',
        'metadata'                => 'array',
        'deleted_at'              => 'datetime',
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
     * Get the supplier for this purchase.
     */

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the user who requested this purchase.
     */

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    /**
     * Get the user who approved this purchase.
     */

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }


    /**
     * Get the user who created this record.
     */

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this record.
     */

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get all items in this purchase.
     */

    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
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
     * Scope to include only purchases from a specific supplier.
     */

    public function scopeFromSupplier($query, $supplierId)
    {
        return $query->where('supplier_id', $supplierId);
    }

    /**
     * Scope to include purchases expected before a given date.
     */

    public function scopeExpectedBefore($query, $date)
    {
        return $query->where('expected_delivery_date', '<=', $date);
    }

    // ========== ACCESSORS ==========

    /**
     * Check if the purchase is fully received.
     */

    public function  getIsFullyReceivedAttribute(): bool
    {
        return $this->status === 'received';
    }

    /**
     * Check if the purchase is overdue.
     */

    public function getIsOverdueAttribute(): bool
    {
        return $this->expected_delivery_date
            && $this->expected_delivery_date->isPast()
            && !in_array($this->status, ['received', 'cancelled']);;
    }
}
