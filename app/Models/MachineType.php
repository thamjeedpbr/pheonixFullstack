<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * MachineType Model
 * 
 * Represents different types/categories of machines in the manufacturing system.
 * 
 * @property int $id
 * @property string $type_id
 * @property string $name
 * @property string|null $remark
 * @property string $machine_type
 * @property bool $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Machine[] $machines
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SubStatus[] $subStatuses
 */
class MachineType extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'machine_types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type_id',
        'name',
        'remark',
        'machine_type',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Machine type enum values
     */
    const TYPE_PRE_PRESS = 'PRE_PRESS';
    const TYPE_PRESS = 'PRESS';
    const TYPE_POST_PRESS = 'POST_PRESS';
    const TYPE_DIE_CUT = 'DIE_CUT';
    const TYPE_OTHER = 'OTHER';

    /**
     * Get all machines of this type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function machines(): HasMany
    {
        return $this->hasMany(Machine::class, 'machine_type_id');
    }

    /**
     * Get all sub-statuses for this machine type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subStatuses(): HasMany
    {
        return $this->hasMany(SubStatus::class, 'machine_type_id');
    }

    /**
     * Scope to get only active machine types.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope to filter by machine type.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('machine_type', $type);
    }
}
