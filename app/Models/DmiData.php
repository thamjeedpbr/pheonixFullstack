<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DmiData extends Model
{
    use HasFactory;

    protected $table = 'dmi_data';

    protected $fillable = [
        'status',
        'is_gen',
        'is_manual',
        'remark',
        'ups',
        'ended_status',
        'start_time',
        'end_time',
        'speed',
        'employee_count',
        'time',
        'good_lm',
        'bad_lm',
        'order_id',
        'active_description',
        'general_maintance',
        'status_id',
        'sub_status_id',
        'form_id',
        'machine_id',
        'machine_type_id',
        'created_by_id',
    ];

    protected $casts = [
        'status' => 'boolean',
        'is_gen' => 'boolean',
        'is_manual' => 'boolean',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    public function subStatus(): BelongsTo
    {
        return $this->belongsTo(SubStatus::class);
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public function machine(): BelongsTo
    {
        return $this->belongsTo(Machine::class);
    }

    public function machineType(): BelongsTo
    {
        return $this->belongsTo(MachineType::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}
