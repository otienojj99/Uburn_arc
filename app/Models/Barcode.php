<?php

namespace App\Models;

use App\Traits\HasUserAudit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use function PHPSTORM_META\type;

class Barcode extends Model

{
    use SoftDeletes, HasUserAudit;
    //

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */

    protected $fillable = [
        'barcode',
        'product_item_id',
        'type',
        'format',
        'is_primary',
        'is_active',
        'notes',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */

    protected $casts = [
        'is_primary'  => 'boolean',
        'is_active'   => 'boolean',
        'deleted_at'  => 'datetime',
    ];


    /**
     * Default attribute values.
     *
     * @var array
     */
    protected $attributes = [
        'type'       => 'code128',
        'is_primary' => false,
        'is_active'  => true,
    ];

    // ========== RELATIONSHIPS ==========

    /**
     * Get the product item this barcode belongs to.
     */

    public function productItem()
    {
        return $this->belongsTo(ProductItem::class);
    }

    /**
     * Get the user who created this barcode.
     */

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this barcode.
     */

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // ========== SCOPES ==========

    /**
     * Scope to include only active barcodes.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to include only primary barcodes.
     */

    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    /**
     * Scope to filter by barcode type.
     */

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to search by barcode value (for quick lookup).
     */

    public function scopeWithBarcode($query, $barcode)
    {
        return $query->where('barcode', $barcode);
    }

     // ========== ACCESSORS ==========

    /**
     * Get the barcode in a human-readable format if format is stored.
     * (E.g., if the raw barcode is "1234567890128", format might be "12345-67890-12")
     */
    public function getFormattedBarcodeAttribute(): string
    {
        return $this->format ?: $this->barcode;
    }

    /**
     * Determine if the barcode is of a numeric type (EAN, UPC).
     */

    public function getIsNumericAttribute(): bool
    {
        return in_array($this->type, ['ean13', 'ean8', 'upc']);
    }

    /**
     * Determine if the barcode is a 2D type (QR, DataMatrix).
     */

    public function getIs2DAttribute(): bool
    {
        return in_array($this->type, ['qr', 'datamatrix']);
    }
}
