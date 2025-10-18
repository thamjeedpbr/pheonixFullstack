<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class StandardProduction extends Model
{
    use HasFactory;

    protected $fillable = ['range_from', 'range_to', 'name', 'color', 'coated', 'make_ready_time', 'make_ready_sheet', 'average_speed', 'status'];

    protected $casts = ['coated' => 'boolean', 'status' => 'boolean', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    public function materials(): BelongsToMany { return $this->belongsToMany(Material::class, 'standard_production_material'); }
    public function machines(): BelongsToMany { return $this->belongsToMany(Machine::class, 'standard_production_machine'); }
    public function scopeActive($query) { return $query->where('status', true); }
}
