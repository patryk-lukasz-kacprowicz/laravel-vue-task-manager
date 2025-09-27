<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    /** @var array<string>  */
    protected $fillable = [
        'user_id',
        'is_completed',
        'title',
        'description',
        'priority',
        'deadline_date',
        'completion_date',
    ];

    /** @var array<string, mixed>  */
    protected $casts = [
        'is_completed' => 'boolean',
        'deadline_date' => 'datetime',
        'completion_date' => 'datetime',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
