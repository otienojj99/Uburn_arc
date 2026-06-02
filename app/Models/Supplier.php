<?php

namespace App\Models;

use App\Traits\HasUserAudit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Supplier extends Model
{
    use SoftDeletes, HasUserAudit;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */

    protected $fillable = [
        'company_name',
        'trade_name',
        'supplier_code',
        'tax_number',
        'registration_number',
        'contact_person',
        'email',
        'phone',
        'mobile',
        'fax',
        'website',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'postal_code',
        'country',
        'payment_terms',
        'currency',
        'credit_limit',
        'payment_due_days',
        'bank_name',
        'bank_account_name',
        'bank_account_number',
        'bank_sort_code',
        'bank_swift_code',
        'notes',
        'metadata',
        'is_active',
        'is_preferred',
        'rating',
        'deleted_by',
        'updated_by',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */

    protected $casts = [
        'credit_limit'    => 'decimal:2',
        'payment_due_days' => 'integer',
        'is_active'       => 'boolean',
        'is_preferred'    => 'boolean',
        'rating'          => 'integer',        // Enum values are integers 1-5
        'metadata'        => 'array',
        'deleted_at'      => 'datetime',
    ];

    /**
     * Default attribute values.
     *
     * @var array
     */

    protected $attributes = [
        'country'          => 'Kenya',
        'currency'         => 'NGN',
        'is_active'        => true,
        'is_preferred'     => false,
        'payment_due_days' => 30,
    ];

    // ========== RELATIONSHIPS ==========

    /**
     * Get all purchases from this supplier.
     */

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    /**
     * Get all products supplied by this supplier.
     * (Assumes a many-to-many relationship via a supplier_products pivot table)
     */

    public function products()
    {
        return $this->belongsToMany(ProductItem::class, 'supplier_products')
            ->withPivot('price', 'lead_time', 'is_preferred')
            ->withTimestamps();
    }

     // ========== SCOPES ==========

    /**
     * Scope to only include active suppliers.
     */

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to only include preferred suppliers.
     */

    public function scopePreferred($query)
    {
        return $query->where('is_preferred', true);
    }

    /**
     * Scope to filter by rating (minimum rating).
     */

    public function scopeMinRating($query, int $rating)
    {
        return $query->where('rating', '>=', $rating);
    }

    /**
     * Scope to filter by country.
     */

    public function scopeInCountry($query, string $country)
    {
        return $query->where('country', $country);
    }

    // ========== ACCESSORS ==========

    /**
     * Get the full address as a single string.
     */

    public function  getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address_line1,
            $this->address_line2,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country,
        ]);
        return implode(', ', $parts);
    }

    /**
     * Get the bank details as a formatted string.
     */

    public function getBankDetailsAttribute(): string
    {
        $details = array_filter([
            $this->bank_name,
            $this->bank_account_name,
            $this->bank_account_number,
            $this->bank_sort_code,
            $this->bank_swift_code,
        ]);

        return implode(' | ', $details);
    }
}
