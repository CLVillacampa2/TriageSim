<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TriageSession extends Model
{
    use HasFactory;

    protected $table = 'triage_sessions';

    protected $fillable = [
        'student_id',
        'student_name',
        'cohort',
        'scenario',
        'latency',
        'efficiency',
        'accuracy',
    ];

    protected $casts = [
        'latency' => 'float',
        'efficiency' => 'integer',
        'accuracy' => 'integer',
    ];
}
