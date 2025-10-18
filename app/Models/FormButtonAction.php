<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormButtonAction extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'form_id',
        'button_group_id',
        'action',
        'reason',
        'performed_by_id',
        'login_detail_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public function buttonGroup(): BelongsTo
    {
        return $this->belongsTo(ButtonGroup::class);
    }

    public function performedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by_id');
    }

    public function loginDetail(): BelongsTo
    {
        return $this->belongsTo(LoginDetail::class);
    }
}
