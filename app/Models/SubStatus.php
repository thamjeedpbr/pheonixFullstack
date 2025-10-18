<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'sub_status_name',
        'sub_status_code',
        'hours_kpi',
        'sub_status_kpi',
        'active',
        'sub_status_remark',
        'non_sellable',
        'img',
        'status_id',
        'machine_type_id',
        'created_by_id',
    ];

    protected $casts = [
        'active' => 'boolean',
        'non_sellable' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    public function machineType(): BelongsTo
    {
        return $this->belongsTo(MachineType::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function forms(): HasMany
    {
        return $this->hasMany(Form::class, 'sub_status_id');
    }

    public function dmiData(): HasMany
    {
        return $this->hasMany(DmiData::class, 'sub_status_id');
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
