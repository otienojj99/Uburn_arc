<?php

namespace App\Models;

use App\Traits\HasUserAudit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Customer extends Model
{
    use SoftDeletes, Notifiable, HasUserAudit;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'customer_code',
        'customer_type',
        'business_name',
        'first_name',
        'last_name',
        'email',
        'phone',
        'alternative_phone',
        'password',
        'email_verified_at',
        'verification_token',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'postal_code',
        'country',
        'tax_number',
        'registration_number',
        'website',
        'currency',
        'language',
        'credit_limit',
        'payment_due_days',
        'account_balance',
        'loyalty_points',
        'loyalty_tier',
        'marketing_emails',
        'marketing_sms',
        'last_login_at',
        'last_login_ip',
        'status',
        'blocked_reason',
        'notes',
        'metadata',
        'preferences',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'verification_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at'     => 'datetime',
        'credit_limit'      => 'decimal:2',
        'account_balance'   => 'decimal:2',
        'payment_due_days'  => 'integer',
        'loyalty_points'    => 'integer',
        'marketing_emails'  => 'boolean',
        'marketing_sms'     => 'boolean',
        'metadata'          => 'array',
        'preferences'       => 'array',
        'deleted_at'        => 'datetime',
    ];

    /**
     * Default attribute values.
     *
     * @var array
     */
    protected $attributes = [
        'customer_type'    => 'individual',
        'country'          => 'Nigeria',
        'currency'         => 'NGN',
        'language'         => 'en',
        'payment_due_days' => 0,
        'account_balance'  => 0,
        'loyalty_points'   => 0,
        'loyalty_tier'     => 'bronze',
        'marketing_emails' => true,
        'marketing_sms'    => false,
        'status'           => 'active',
    ];

    // ========== RELATIONSHIPS ==========

    /**
     * Get all sales for this customer.
     */
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Get the staff user who created this customer.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the staff user who last updated this customer.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // ========== SCOPES ==========

    /**
     * Scope to filter by customer type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('customer_type', $type);
    }

    /**
     * Scope to include only active customers.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to include customers with a specific loyalty tier.
     */
    public function scopeInTier($query, $tier)
    {
        return $query->where('loyalty_tier', $tier);
    }

    /**
     * Scope to search by name or email.
     */
    public function scopeSearch($query, $searchTerm)
    {
        return $query->where(function ($q) use ($searchTerm) {
            $q->where('first_name', 'like', "%{$searchTerm}%")
                ->orWhere('last_name', 'like', "%{$searchTerm}%")
                ->orWhere('email', 'like', "%{$searchTerm}%")
                ->orWhere('customer_code', 'like', "%{$searchTerm}%");
        });
    }

    // ========== ACCESSORS ==========

    /**
     * Get the customer's full name.
     */
    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Get the customer's display name (business name if exists, else full name).
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->business_name ?: $this->full_name;
    }

    /**
     * Get the full address as a single string.
     */
    public function getFullAddressAttribute(): string
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
     * Check if the customer has a credit limit and is within it.
     * (Placeholder – you can implement actual logic based on outstanding invoices.)
     */
    public function getHasAvailableCreditAttribute(): bool
    {
        if (!$this->credit_limit) {
            return true; // No limit
        }
        // You would calculate total outstanding invoices/orders here
        $outstanding = $this->sales()
            ->where('payment_status', '!=', 'paid')
            ->sum('total');
        return $outstanding < $this->credit_limit;
    }
}
