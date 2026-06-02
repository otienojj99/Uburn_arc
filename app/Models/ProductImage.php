<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;


class ProductImage extends Model
{

    use SoftDeletes;
    //

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */

    protected $fillable = [
        'product_item_id',
        'image_path',
        'file_name',
        'mime_type',
        'file_size',
        'sort_order',
        'thumbnail_path',
        'medium_path',
        'large_path',
        'alt_text',
        'title',
        'caption',
        'is_primary',
        'is_active',
        'uploaded_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */

    protected $casts = [
        'file_size'   => 'integer',
        'sort_order'  => 'integer',
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
        'sort_order' => 0,
        'is_primary' => false,
        'is_active'  => true,
    ];

    // ========== RELATIONSHIPS ==========

    /**
     * Get the product item this image belongs to.
     */

    public function productItem()
    {
        return $this->belongsTo(ProductItem::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'image_id');
    }


    /**
     * Get the user who uploaded this image.
     */

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }


     // ========== SCOPES ==========

    /**
     * Scope to include only active images.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to include only primary images.
     */

    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    /**
     * Scope to order by sort order (ascending).
     */

    public function scopeOrder($query)
    {
        return $query->orderBy('sort_order');
    }


    // ========== ACCESSORS ==========

    /**
     * Get the full URL of the image (using S3 or public disk).
     * Assumes `image_path` stores the relative path (e.g., 'products/123/image.jpg').
     */
    public function  getUrlAttribute(): string
    {
        return Storage::disk('s3')->url($this->image_path);
    }

    /**
     * Get the full URL of the thumbnail image.
     * Falls back to the main image if thumbnail not available.
     */

    public function getThumbnailUrlAttribute(): string
    {
        $path = $this->thumbnail_path ?? $this->image_path;
        return Storage::disk('s3')->url($path);
    }

    /**
     * Get the full URL of the medium image.
     */

    public function getMediumUrlAttribute(): string
    {
        $path = $this->medium_path ?? $this->image_path;
        return Storage::disk('s3')->url($path);
    }

    /**
     * Get the full URL of the large image.
     */

    public function getLargeUrlAttribute(): string
    {
        $path = $this->large_path ?? $this->image_path;
        return Storage::disk('s3')->url($path);
    }

    /**
     * Get the human-readable file size (e.g., "1.2 MB").
     */

    public function getFormattedFileSizeAttribute(): string
    {
        if (!$this->file_size) {
            return 'Unknown';
        }

        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}
