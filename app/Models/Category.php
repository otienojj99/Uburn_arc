<?php

namespace App\Models;

use App\Models\ProductItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasUserAudit;

class Category extends Model
{
    use SoftDeletes, HasUserAudit; // If you want soft deletes (recommended)

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */

    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'is_active',
        'sort_order',
        'image',
        'metadata',
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
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'metadata' => 'array', // if metadata is JSON
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the parent category.
     */

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get the child categories.
     */

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }


    public function descendants()
    {
        return $this->children()->with('descendants');
    }


    /**
     * Get all products in this category.
     */

    public function products()
    {
        return $this->hasMany(ProductItem::class, 'category_id');
    }

    /**
     * Scope a query to only include active categories.
     */

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeChildrenOnly($query)
    {
        return $query->whereNotNull('parent_id');
    }

    public function scopeWithTree($query)
    {
        return $query->with('descendants');
    }


    /**
     * Get the full path of the category (breadcrumb).
     */

    public function getFullPathAttribute(): string
    {
        $names = [];
        $category = $this;
        $depth = 0;
        $maxDepth = 10; // safety limit

        while ($category && $depth < $maxDepth) {
            $names[] = $category->name;
            // Use loaded relation if available, otherwise query
            $category = $category->relationLoaded('parent')
                ? $category->parent
                : $category->parent()->first();
            $depth++;
        }

        return implode(' > ', array_reverse($names));
    }

    public function getIsRootAttribute(): bool
    {
        return is_null($this->parent_id);
    }
}
