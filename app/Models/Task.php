<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

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
     * @param Builder $query
     * @param $status
     *
     * @return void
     */
    public function scopeFilterByStatus(Builder $query, $status = null): void {
        if (strlen($status) > 0 || $status === 0 || $status === '0') {
            $query->where('is_completed', (bool) $status);
        }
    }

    /**
     * @param Builder $query
     * @param $priority
     *
     * @return void
     */
    public function scopeFilterByPriority(Builder $query, $priority = null): void {
        if ($priority) {
            $query->where('priority', $priority);
        }
    }

    /**
     * @param Builder $query
     * @param $column
     * @param $direction
     *
     * @return void
     */
    public function scopeApplySort(Builder $query, $column = 'created_at', $direction = 'desc'): void {
        $validColumns = ['created_at', 'deadline_date', 'priority', 'title'];
        $validDirections = ['asc', 'desc'];

        if (in_array($column, $validColumns) && in_array($direction, $validDirections)) {
            $query->orderBy($column, $direction);
        }
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
