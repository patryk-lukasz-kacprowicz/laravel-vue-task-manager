<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /** @var array<string>  */
    protected $fillable = [
        'is_completed',
        'title',
        'description',
        'deadline_date',
        'completion_date',
    ];

    /** @var array<string, mixed>  */
    protected $casts = [
        'is_completed' => 'boolean',
        'deadline_date' => 'datetime',
        'completion_date' => 'datetime',
    ];
}
