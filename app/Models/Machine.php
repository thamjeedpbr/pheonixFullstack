<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Machine extends Model
{
    use HasFactory;

    protected $fillable = [
        'machine_id',
        'machine_name',
        'description',
        'min_width',
        'min_height',
        'max_height',
        'max_width',
        'min_gsm',
        'max_gsm',
        'status',
        'per_day_impression',
        'per_hour_impression',
        'remarks',
        'make_ready_time',
        'minimum_cost',
        'per_hour_cost',
        'meter_per_impression',
        'devise_url',
        'machine_type_id',
        'process_id',
        'created_by_id',
    ];

    protected $casts = [
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function machineType(): BelongsTo
    {
        return $this->belongsTo(MachineType::class);
    }

    public function process(): BelongsTo
    {
        return $this->belongsTo(Process::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'machine_user');
    }

    public function forms(): BelongsToMany
    {
        return $this->belongsToMany(Form::class, 'form_machine');
    }

    public function loginDetails(): HasMany
    {
        return $this->hasMany(LoginDetail::class);
    }

    public function sheets(): HasMany
    {
        return $this->hasMany(Sheet::class);
    }

    public function dmiData(): HasMany
    {
        return $this->hasMany(DmiData::class);
    }

    public function stickyNotes(): HasMany
    {
        return $this->hasMany(StickyNote::class);
    }

    public function dailyMaintainedData(): HasMany
    {
        return $this->hasMany(DailyMaintainedData::class);
    }

    public function standardProductions(): HasMany
    {
        return $this->hasMany(StandardProduction::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}
