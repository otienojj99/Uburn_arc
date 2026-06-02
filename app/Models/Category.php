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
        return $query->where("is_active", true);
    }


    /**
     * Get the full path of the category (breadcrumb).
     */

    public function getFullPathAttribute()
    {
        $names = [];
        $category = $this;

        while ($category) {
            $names[] = $category->name;
            $category = $category->parent;
        }

        return implode(' > ', array_reverse($names));
    }
}
