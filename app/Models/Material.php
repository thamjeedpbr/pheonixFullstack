<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_id',
        'name',
        'utilization',
        'coating',
        'description',
        'gsm',
        'unit',
        'status',
        'department_id',
        'created_by_id',
    ];

    protected $casts = [
        'coating' => 'boolean',
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function forms(): HasMany
    {
        return $this->hasMany(Form::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}
