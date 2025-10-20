<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_card_no',
        'prod_details',
        'job_details',
        'delivery_date',
        'production_start',
        'finish_size',
        'finish_quantity',
        'remark',
        'client_name',
        'title',
        'description',
        'priority',
        'status',
        'pro_name',
        'ref_no',
        'closed',
        'material_id',
        'process_id',
        'created_by_id',
    ];

    protected $casts = [
        'closed' => 'boolean',
        'delivery_date' => 'date',
        'production_start' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    public function process(): BelongsTo
    {
        return $this->belongsTo(Process::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeOpen($query)
    {
        return $query->where('closed', false);
    }
}
