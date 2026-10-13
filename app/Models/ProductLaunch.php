<?php
// app/Models/ProductLaunch.php

namespace App\Models;

use App\Traits\HasUserAudit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductLaunch extends Model 
{
    use SoftDeletes, HasUserAudit;

    protected $fillable = [
        'product_item_id',
        'title',
        'subtitle',
        'description',
        'cta_text',
        'cta_url',
        'hero_image_path',
        'launches_at',
        'ends_at',
        'status',
        'is_active',
        'priority',
        'metadata',
    ];

    protected $casts = [
        'launches_at' => 'datetime',
        'ends_at'     => 'datetime',
        'is_active'   => 'boolean',
        'priority'    => 'integer',
        'metadata'    => 'array',
        'deleted_at'  => 'datetime',
    ];


    public function product(){
        return $this->belongsTo(ProductItem::class, 'product_item_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->whereIn('status', ['scheduled', 'live'])
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            });
    }

     public function scopeCurrent($query)
    {
        return $query->active()
            ->orderByDesc('priority')
            ->orderBy('launches_at');
    }

    public function scopeUpcoming($query)
    {
        return $query->active()->where('launches_at', '>', now());
    }

    public function getIsLiveAttribute(): bool
    {
        return $this->status === 'live'
            && $this->launches_at <= now()
            && (!$this->ends_at || $this->ends_at >= now());
    }

    public function getTimeUntilLaunchAttribute(): ?int
    {
        if ($this->launches_at <= now()) return null;
        return now()->diffInSeconds($this->launches_at);
    }
}