<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Button extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'button_id',
        'msi_id',
        'is_stop',
        'status',
        'created_by_id',
    ];

    protected $casts = [
        'is_stop' => 'boolean',
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function buttonGroups(): HasMany
    {
        return $this->hasMany(ButtonGroup::class);
    }

    public function formButtonActions(): HasMany
    {
        return $this->hasMany(FormButtonAction::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeStopButtons($query)
    {
        return $query->where('is_stop', true);
    }
}
