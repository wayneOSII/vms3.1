<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SemesterStartAndEnd extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'semester_start',
        'semester_end',
    ];
}
