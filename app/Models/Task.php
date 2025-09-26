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
    ];

    /** @var array<string, mixed>  */
    protected $casts = [
        'is_completed' => 'boolean',
    ];
}
