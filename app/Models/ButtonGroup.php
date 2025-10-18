<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ButtonGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_order',
        'process_id',
        'button_id',
        'status',
        'created_by_id',
    ];

    protected $casts = [
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function process(): BelongsTo
    {
        return $this->belongsTo(Process::class);
    }

    public function button(): BelongsTo
    {
        return $this->belongsTo(Button::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function forms(): HasMany
    {
        return $this->hasMany(Form::class, 'button_group_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}
