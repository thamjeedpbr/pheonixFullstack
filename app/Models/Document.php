<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    use HasFactory;

    protected $fillable = ['group_id', 'form_id', 'verified', 'qc_id', 'file_path', 'created_by_id'];

    protected $casts = ['verified' => 'boolean', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    public function createdBy(): BelongsTo { return $this->belongsTo(User::class, 'created_by_id'); }
}
