<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyMaintainedData extends Model
{
    use HasFactory;

    protected $fillable = ['clean_ink', 'clean_cylinder', 'clean_all_impression', 'clean_technotrans', 'clean_all_air_filters', 'check_water_pressure', 'check_grease_point', 'task_id', 'machine_id', 'shift_id', 'login_detail_id', 'remark', 'status', 'created_by_id'];

    protected $casts = ['clean_ink' => 'boolean', 'clean_cylinder' => 'boolean', 'clean_all_impression' => 'boolean', 'clean_technotrans' => 'boolean', 'clean_all_air_filters' => 'boolean', 'check_water_pressure' => 'boolean', 'check_grease_point' => 'boolean', 'status' => 'boolean', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    public function task(): BelongsTo { return $this->belongsTo(DailyTask::class); }
    public function machine(): BelongsTo { return $this->belongsTo(Machine::class); }
    public function shift(): BelongsTo { return $this->belongsTo(Shift::class); }
    public function loginDetail(): BelongsTo { return $this->belongsTo(LoginDetail::class); }
    public function createdBy(): BelongsTo { return $this->belongsTo(User::class, 'created_by_id'); }
    public function scopeActive($query) { return $query->where('status', true); }
}
