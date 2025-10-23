<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * FormUser Pivot Model
 * 
 * Tracks user (operator) assignments to forms with work status and timestamps
 * Multiple users can be assigned, but only one can be working (is_working = true) at a time
 * 
 * @property int $id
 * @property int $form_id
 * @property int $user_id
 * @property bool $is_working
 * @property \Carbon\Carbon|null $worked_at
 * @property \Carbon\Carbon|null $finished_at
 * @property int|null $duration_minutes
 * @property string|null $notes
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class FormUser extends Pivot
{
    /**
     * The table associated with the model.
     */
    protected $table = 'form_user';

    /**
     * Indicates if the IDs are auto-incrementing.
     */
    public $incrementing = true;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'form_id',
        'user_id',
        'is_working',
        'worked_at',
        'finished_at',
        'duration_minutes',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_working' => 'boolean',
        'worked_at' => 'datetime',
        'finished_at' => 'datetime',
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
     * Get the user that is assigned.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get only working user assignments.
     */
    public function scopeWorking($query)
    {
        return $query->where('is_working', true);
    }

    /**
     * Scope to get user assignments for a specific form.
     */
    public function scopeForForm($query, $formId)
    {
        return $query->where('form_id', $formId);
    }

    /**
     * Scope to get form assignments for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to get assignments with work history (worked_at is not null).
     */
    public function scopeWithHistory($query)
    {
        return $query->whereNotNull('worked_at');
    }

    /**
     * Mark this user as working (currently active).
     * Marks all other users for this form as not working.
     */
    public function startWorking(): bool
    {
        // Mark all other users as not working for this form
        static::where('form_id', $this->form_id)
            ->where('id', '!=', $this->id)
            ->where('is_working', true)
            ->update([
                'is_working' => false,
                'finished_at' => now(),
            ]);

        // Mark this user as working
        $this->is_working = true;
        $this->worked_at = now();
        $this->finished_at = null;

        return $this->save();
    }

    /**
     * Mark this user as finished working.
     */
    public function stopWorking(?string $notes = null): bool
    {
        $this->is_working = false;
        $this->finished_at = now();

        if ($notes) {
            $this->notes = $notes;
        }

        // Calculate duration if worked_at exists
        if ($this->worked_at) {
            $duration = now()->diffInMinutes($this->worked_at);
            $this->duration_minutes = ($this->duration_minutes ?? 0) + $duration;
        }

        return $this->save();
    }

    /**
     * Check if this user is currently working.
     */
    public function isWorking(): bool
    {
        return $this->is_working;
    }

    /**
     * Get the total duration this user worked (in minutes).
     */
    public function getTotalDuration(): int
    {
        $total = $this->duration_minutes ?? 0;

        // Add current session if still working
        if ($this->is_working && $this->worked_at) {
            $total += now()->diffInMinutes($this->worked_at);
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
     * Get the currently working user for a form.
     */
    public static function getWorkingUser($formId): ?self
    {
        return static::where('form_id', $formId)
            ->where('is_working', true)
            ->first();
    }

    /**
     * Switch the working user for a form.
     */
    public static function switchUser($formId, $newUserId): self
    {
        // Get or create the assignment
        $assignment = static::firstOrCreate(
            [
                'form_id' => $formId,
                'user_id' => $newUserId,
            ],
            [
                'is_working' => false,
                'worked_at' => null,
            ]
        );

        // Start working (will stop others)
        $assignment->startWorking();

        return $assignment;
    }

    /**
     * Get work history for a user.
     */
    public static function getUserWorkHistory($userId, $limit = 10)
    {
        return static::with(['form.section.order'])
            ->where('user_id', $userId)
            ->whereNotNull('worked_at')
            ->orderBy('worked_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get work report for a form.
     */
    public static function getFormWorkReport($formId)
    {
        return static::with('user')
            ->where('form_id', $formId)
            ->whereNotNull('worked_at')
            ->orderBy('worked_at', 'desc')
            ->get()
            ->map(function ($assignment) {
                return [
                    'user_id' => $assignment->user_id,
                    'user_name' => $assignment->user->name,
                    'worked_at' => $assignment->worked_at,
                    'finished_at' => $assignment->finished_at,
                    'duration_minutes' => $assignment->duration_minutes,
                    'duration_formatted' => $assignment->getFormattedDuration(),
                    'is_working' => $assignment->is_working,
                    'notes' => $assignment->notes,
                ];
            });
    }

    /**
     * Get daily work report for a user.
     */
    public static function getDailyUserReport($userId, $date = null)
    {
        $date = $date ?? now()->toDateString();

        return static::with(['form.section.order'])
            ->where('user_id', $userId)
            ->whereDate('worked_at', $date)
            ->get()
            ->map(function ($assignment) {
                return [
                    'form_id' => $assignment->form_id,
                    'form_name' => $assignment->form->form_name,
                    'section' => $assignment->form->section->section_id,
                    'order' => $assignment->form->section->order->job_card_no,
                    'worked_at' => $assignment->worked_at,
                    'finished_at' => $assignment->finished_at,
                    'duration_minutes' => $assignment->duration_minutes,
                    'duration_formatted' => $assignment->getFormattedDuration(),
                    'notes' => $assignment->notes,
                ];
            });
    }

    /**
     * Get total work time for a user in a date range.
     */
    public static function getUserTotalTime($userId, $startDate, $endDate)
    {
        $assignments = static::where('user_id', $userId)
            ->whereBetween('worked_at', [$startDate, $endDate])
            ->get();

        $totalMinutes = $assignments->sum(function ($assignment) {
            return $assignment->getTotalDuration();
        });

        $hours = floor($totalMinutes / 60);
        $minutes = $totalMinutes % 60;

        return [
            'total_minutes' => $totalMinutes,
            'hours' => $hours,
            'minutes' => $minutes,
            'formatted' => "{$hours}h {$minutes}m",
        ];
    }
}
