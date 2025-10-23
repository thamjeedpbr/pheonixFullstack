<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * FormMachine Pivot Model
 * 
 * Tracks machine assignments to forms with activity status and timestamps
 * Only one machine can be active (is_active = true) per form at a time
 * 
 * @property int $id
 * @property int $form_id
 * @property int $machine_id
 * @property bool $is_active
 * @property \Carbon\Carbon|null $selected_at
 * @property \Carbon\Carbon|null $deselected_at
 * @property int|null $duration_minutes
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class FormMachine extends Pivot
{
    /**
     * The table associated with the model.
     */
    protected $table = 'form_machine';

    /**
     * Indicates if the IDs are auto-incrementing.
     */
    public $incrementing = true;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'form_id',
        'machine_id',
        'is_active',
        'selected_at',
        'deselected_at',
        'duration_minutes',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_active' => 'boolean',
        'selected_at' => 'datetime',
        'deselected_at' => 'datetime',
        'duration_minutes' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the form that owns this assignment.
     */
    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    /**
     * Get the machine that is assigned.
     */
    public function machine(): BelongsTo
    {
        return $this->belongsTo(Machine::class);
    }

    /**
     * Scope to get only active machine assignments.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get machine assignments for a specific form.
     */
    public function scopeForForm($query, $formId)
    {
        return $query->where('form_id', $formId);
    }

    /**
     * Scope to get machine assignments for a specific machine.
     */
    public function scopeForMachine($query, $machineId)
    {
        return $query->where('machine_id', $machineId);
    }

    /**
     * Mark this machine as active (currently selected).
     * Deactivates all other machines for this form.
     */
    public function activate(): bool
    {
        // Deactivate all other machines for this form
        static::where('form_id', $this->form_id)
            ->where('id', '!=', $this->id)
            ->where('is_active', true)
            ->update([
                'is_active' => false,
                'deselected_at' => now(),
            ]);

        // Activate this machine
        $this->is_active = true;
        $this->selected_at = now();
        $this->deselected_at = null;

        return $this->save();
    }

    /**
     * Mark this machine as inactive (deselected).
     */
    public function deactivate(): bool
    {
        $this->is_active = false;
        $this->deselected_at = now();

        // Calculate duration if selected_at exists
        if ($this->selected_at) {
            $duration = now()->diffInMinutes($this->selected_at);
            $this->duration_minutes = ($this->duration_minutes ?? 0) + $duration;
        }

        return $this->save();
    }

    /**
     * Check if this machine is currently active.
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Get the total duration this machine was used (in minutes).
     */
    public function getTotalDuration(): int
    {
        $total = $this->duration_minutes ?? 0;

        // Add current session if still active
        if ($this->is_active && $this->selected_at) {
            $total += now()->diffInMinutes($this->selected_at);
        }

        return $total;
    }

    /**
     * Get formatted duration string.
     */
    public function getFormattedDuration(): string
    {
        $minutes = $this->getTotalDuration();
        $hours = floor($minutes / 60);
        $mins = $minutes % 60;

        if ($hours > 0) {
            return "{$hours}h {$mins}m";
        }

        return "{$mins}m";
    }

    /**
     * Get the currently active machine for a form.
     */
    public static function getActiveMachine($formId): ?self
    {
        return static::where('form_id', $formId)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Switch active machine for a form.
     */
    public static function switchMachine($formId, $newMachineId): self
    {
        // Get or create the assignment
        $assignment = static::firstOrCreate(
            [
                'form_id' => $formId,
                'machine_id' => $newMachineId,
            ],
            [
                'is_active' => false,
                'selected_at' => null,
            ]
        );

        // Activate it (will deactivate others)
        $assignment->activate();

        return $assignment;
    }
}
