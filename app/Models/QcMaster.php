<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QcMaster extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'msi_id',
        'status',
        'process_id',
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

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function manualQcVerifications(): HasMany
    {
        return $this->hasMany(ManualQcVerification::class, 'qc_master_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}
