<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PressNote extends Model
{
    use HasFactory;

    protected $fillable = ['note', 'status', 'form_id', 'order_id', 'created_by_id'];

    protected $casts = ['status' => 'boolean', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    public function form(): BelongsTo { return $this->belongsTo(Form::class); }
    public function order(): BelongsTo { return $this->belongsTo(Order::class); }
    public function createdBy(): BelongsTo { return $this->belongsTo(User::class, 'created_by_id'); }
    public function scopeActive($query) { return $query->where('status', true); }
}
