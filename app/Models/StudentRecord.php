<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentRecord extends Model
{
    use HasFactory;

    // The table associated with the model
    protected $table = 'student_records';

    // Attributes that can be securely mass-assigned via API payloads
    protected $fillable = [
        'student_id',
        'student_name',
        'cohort',
        'scenario',
        'latency',
        'efficiency',
        'accuracy',
    ];
}