<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Status extends Model
{
    protected $fillable = [
        'Status_code',
        'Status_name',
        'remark',
        'status',
        'img',
        'status_type',
        'created_by_id',
    ];

    protected $casts = [
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Accessor to map Status_code to status_id for API responses
    public function getStatusIdAttribute()
    {
        return $this->Status_code;
    }

    // Mutator to map status_id to Status_code when saving
    public function setStatusIdAttribute($value)
    {
        $this->attributes['Status_code'] = $value;
    }

    // Accessor to map Status_name to name for API responses
    public function getNameAttribute()
    {
        return $this->Status_name;
    }

    // Mutator to map name to Status_name when saving
    public function setNameAttribute($value)
    {
        $this->attributes['Status_name'] = $value;
    }

    // Accessor for description (uses remark)
    public function getDescriptionAttribute()
    {
        return $this->remark;
    }

    // Mutator for description (saves to remark)
    public function setDescriptionAttribute($value)
    {
        $this->attributes['remark'] = $value;
    }

    /**
     * Get all sub-statuses for this status.
     */
    public function subStatuses(): HasMany
    {
        return $this->hasMany(SubStatus::class);
    }

    /**
     * Get all forms for this status.
     */
    public function forms(): HasMany
    {
        return $this->hasMany(Form::class);
    }

    /**
     * Get the user who created this status.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    /**
     * Scope to filter active statuses.
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}
