<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrentAcademicYearAndSemester extends Model
{
    use HasFactory;

    protected $fillable = [
        'CurrentAcademicYearAndSemester',
        'week',
    ];
}
