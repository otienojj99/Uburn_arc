<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;


trait HasUserAudit
{
    /**
     * Boot the trait.
     */

    protected static function bootHasUserAudit(): void
    {
        // When creating a model, set created_by and updated_by
        static::creating(function ($model) {
            if (Auth::check()) {
                if (! $model->created_by) {
                    $model->created_by = Auth::id();
                }
                if (! $model->updated_by) {
                    $model->updated_by = Auth::id();
                }
            }
        });

        // When updating a model, set updated_by
        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_by = Auth::id();
            }
        });

        // Set deleted_by on soft delete (if the model uses SoftDeletes and has deleted_by column)
        static::deleting(function ($model) {
            if (Auth::check() && $model->isSoftDelete() && $model->hasDeletedByColumn()) {
                $model->deleted_by = Auth::id();
                $model->saveQuietly(); // save without firing events again
            }
        });
    }
    /**
     * Check if the model is using soft deletes.
     */
    protected function isSoftDelete(): bool
    {
        return in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses_recursive($this));
    }

    /**
     * Check if the model has a deleted_by column (i.e., it's in $fillable or exists as a column).
     * You can simplify by just checking if the attribute is fillable or if the column exists.
     * Here we check if the attribute is fillable (you may need to adjust).
     */
    protected function hasDeletedByColumn(): bool
    {
        return in_array('deleted_by', $this->getFillable()) || array_key_exists('deleted_by', $this->getAttributes());
    }
}
