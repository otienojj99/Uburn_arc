<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'logo',
        'cover_image',
        'website',
        'email',
        'phone',
        'is_active',
        'is_featured',
        'sort_order',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'metadata',
        'created_by',
        'updated_by',
        'deleted_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active'   => 'boolean',
        'is_featured' => 'boolean',
        'sort_order'  => 'integer',
        'metadata'    => 'array',
        'deleted_at'  => 'datetime',
    ];

    /**
     * Default attribute values.
     *
     * @var array
     */
    protected $attributes = [
        'is_active'   => true,
        'is_featured' => false,
        'sort_order'  => 0,
    ];

    // ========== RELATIONSHIPS ==========

    /**
     * Get all products belonging to this brand.
     */
    public function products()
    {
        return $this->hasMany(ProductItem::class);
    }

    /**
     * Get the user who created this brand.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this brand.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // ========== SCOPES ==========

    /**
     * Scope to include only active brands.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to include only featured brands.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope to order by sort_order (ascending).
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    // ========== ACCESSORS ==========

    /**
     * Get the display name (same as name, but can be extended).
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->name;
    }

    /**
     * Get the logo URL (if stored in S3, you can generate a full URL).
     * Adjust disk name as needed.
     */
    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo ? \Storage::disk('s3')->url($this->logo) : null;
    }

    /**
     * Get the cover image URL.
     */
    public function getCoverImageUrlAttribute(): ?string
    {
        return $this->cover_image ? \Storage::disk('s3')->url($this->cover_image) : null;
    }
}
