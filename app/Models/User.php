<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'user_name',
        'name',
        'phone_no',
        'status',
        'password',
        'is_super_user',
        'api_key',
        'permission_id',
        'login_detail_id',
        'last_login_time',
        'user_type',
    ];

    protected $hidden = [
        'password',
        'api_key',
        'remember_token',
    ];

    protected $casts = [
        'status' => 'boolean',
        'is_super_user' => 'boolean',
        'last_login_time' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'password' => 'hashed',
    ];

    const TYPE_OPERATOR = 'operator';
    const TYPE_SUPER_WISER = 'super_wiser';
    const TYPE_ADMIN = 'admin';

    public function permission(): BelongsTo
    {
        return $this->belongsTo(UserPermission::class, 'permission_id');
    }

    public function loginDetail(): BelongsTo
    {
        return $this->belongsTo(LoginDetail::class, 'login_detail_id');
    }

    public function machines(): BelongsToMany
    {
        return $this->belongsToMany(Machine::class, 'machine_user');
    }

    public function forms(): BelongsToMany
    {
        return $this->belongsToMany(Form::class, 'form_user');
    }

    public function loginDetails(): HasMany
    {
        return $this->hasMany(LoginDetail::class);
    }

    public function createdOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'created_by_id');
    }

    public function createdForms(): HasMany
    {
        return $this->hasMany(Form::class, 'created_by_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeSuperUsers($query)
    {
        return $query->where('is_super_user', true);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('user_type', $type);
    }
}
