<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyTask extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'machine_id', 'shift_id', 'status', 'clean_ink', 'clean_cylinder', 'clean_all_impression', 'clean_technotrans', 'clean_all_air_filters', 'check_water_pressure', 'check_grease_point'];

    protected $casts = ['status' => 'boolean', 'clean_ink' => 'boolean', 'clean_cylinder' => 'boolean', 'clean_all_impression' => 'boolean', 'clean_technotrans' => 'boolean', 'clean_all_air_filters' => 'boolean', 'check_water_pressure' => 'boolean', 'check_grease_point' => 'boolean', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    public function machine(): BelongsTo { return $this->belongsTo(Machine::class); }
    public function shift(): BelongsTo { return $this->belongsTo(Shift::class); }
    public function scopeActive($query) { return $query->where('status', true); }
}
