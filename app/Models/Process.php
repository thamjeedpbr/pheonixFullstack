<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Process Model
 * 
 * @property int $id
 * @property string $name
 * @property string $msi_id
 * @property bool $status
 * @property int|null $created_by_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Process extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'msi_id',
        'status',
        'created_by_id',
    ];

    protected $casts = [
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function machines(): HasMany
    {
        return $this->hasMany(Machine::class, 'process_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'process_id');
    }

    public function forms(): HasMany
    {
        return $this->hasMany(Form::class, 'process_id');
    }

    public function qcMasters(): HasMany
    {
        return $this->hasMany(QcMaster::class, 'process_id');
    }

    public function buttonGroups(): HasMany
    {
        return $this->hasMany(ButtonGroup::class, 'process_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}
