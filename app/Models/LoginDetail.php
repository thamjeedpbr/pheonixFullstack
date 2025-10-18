<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LoginDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'machine_id',
        'user_id',
        'shift_id',
        'log_out_time',
    ];

    protected $casts = [
        'status' => 'boolean',
        'log_out_time' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function machine(): BelongsTo
    {
        return $this->belongsTo(Machine::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    public function forms(): HasMany
    {
        return $this->hasMany(Form::class, 'login_detail_id');
    }

    public function formButtonActions(): HasMany
    {
        return $this->hasMany(FormButtonAction::class, 'login_detail_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeLoggedIn($query)
    {
        return $query->whereNull('log_out_time');
    }
}
