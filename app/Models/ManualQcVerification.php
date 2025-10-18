<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ManualQcVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'form_id',
        'shift_id',
        'process_id',
        'order_id',
        'qc_master_id',
        'q_verified_vc_id',
        'line_verified_id',
        'q_verified',
        'q_v_time',
        'h_verified',
        'Am_1',
        'Am_2',
        'Am_3',
        'Am_4',
        'Am_5',
        'note',
        'l_verified',
        'l_v_time',
        'history',
        'remark',
        'verified_by_id',
        'status',
    ];

    protected $casts = [
        'q_verified' => 'boolean',
        'h_verified' => 'boolean',
        'Am_1' => 'boolean',
        'Am_2' => 'boolean',
        'Am_3' => 'boolean',
        'Am_4' => 'boolean',
        'Am_5' => 'boolean',
        'l_verified' => 'boolean',
        'status' => 'boolean',
        'q_v_time' => 'datetime',
        'l_v_time' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    public function process(): BelongsTo
    {
        return $this->belongsTo(Process::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function qcMaster(): BelongsTo
    {
        return $this->belongsTo(QcMaster::class);
    }

    public function qVerifiedVc(): BelongsTo
    {
        return $this->belongsTo(User::class, 'q_verified_vc_id');
    }

    public function lineVerified(): BelongsTo
    {
        return $this->belongsTo(User::class, 'line_verified_id');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}
