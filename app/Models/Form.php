<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Form extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_of_labours',
        'UPS',
        'bad_quantity',
        'msi_id',
        'good_quantity',
        'total_quantity',
        'form_name',
        'sheet_quantity',
        'glmp_speed',
        'description',
        'mr_time',
        'sheet_size',
        'wast_qty',
        'mr_speed',
        'excepted_qty',
        'multiply_on_save',
        'coating',
        'is_manual',
        'schedule_date',
        'quality_verified',
        'verified',
        'status',
        'line_cleared_success',
        'form_side',
        'started_From',
        'form_status',
        'job_process',
        'button_history',
        'button_status',
        'ongoing_process_name',
        'stop_reason',
        'pause_reason',
        'l_v_time',
        'material_id',
        'section_id',
        'button_group_id',
        'login_detail_id',
        'created_by_id',
        'updated_by_id',
        'q_verified_vc_id',
        'status_id',
        'sub_status_id',
        'process_id',
    ];

    protected $casts = [
        'no_of_labours' => 'integer',
        'UPS' => 'integer',
        'coating' => 'boolean',
        'is_manual' => 'boolean',
        'schedule_date' => 'date',
        'quality_verified' => 'boolean',
        'verified' => 'boolean',
        'status' => 'boolean',
        'line_cleared_success' => 'boolean',
        'button_history' => 'array',
        'l_v_time' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function machines(): BelongsToMany
    {
        return $this->belongsToMany(Machine::class, 'form_machine')
            ->using(FormMachine::class)
            ->withPivot(['is_active', 'selected_at', 'deselected_at', 'duration_minutes'])
            ->withTimestamps();
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'form_user')
            ->using(FormUser::class)
            ->withPivot(['is_working', 'worked_at', 'finished_at', 'duration_minutes', 'notes'])
            ->withTimestamps();
    }

    /**
     * Get the currently active machine for this form
     */
    public function activeMachine()
    {
        return $this->machines()
            ->wherePivot('is_active', true)
            ->first();
    }

    /**
     * Get the currently working user for this form
     */
    public function workingUser()
    {
        return $this->users()
            ->wherePivot('is_working', true)
            ->first();
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function buttonGroup(): BelongsTo
    {
        return $this->belongsTo(ButtonGroup::class);
    }

    public function loginDetail(): BelongsTo
    {
        return $this->belongsTo(LoginDetail::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }

    public function qVerifiedVc(): BelongsTo
    {
        return $this->belongsTo(User::class, 'q_verified_vc_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    public function subStatus(): BelongsTo
    {
        return $this->belongsTo(SubStatus::class);
    }

    public function process(): BelongsTo
    {
        return $this->belongsTo(Process::class);
    }

    public function dmiData(): HasMany
    {
        return $this->hasMany(DmiData::class);
    }

    public function formButtonActions(): HasMany
    {
        return $this->hasMany(FormButtonAction::class);
    }

    public function lineClearance(): HasOne
    {
        return $this->hasOne(LineClearance::class);
    }

    public function manualQcVerifications(): HasMany
    {
        return $this->hasMany(ManualQcVerification::class);
    }

    public function stickyNotes(): HasMany
    {
        return $this->hasMany(StickyNote::class);
    }

    public function pressNotes(): HasMany
    {
        return $this->hasMany(PressNote::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopePending($query)
    {
        return $query->where('form_status', 'job_pending');
    }

    public function scopeStarted($query)
    {
        return $query->where('form_status', 'job_started');
    }

    public function scopeCompleted($query)
    {
        return $query->where('form_status', 'job_completed');
    }
}
