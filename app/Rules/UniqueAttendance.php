<?php

namespace App\Rules;

use Closure;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Rule;
use App\Models\CurrentAcademicYearAndSemester;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueAttendance implements Rule
{
    public $message;

    public function passes($attribute, $value)
    {
        $user = Auth::user();
        $currentAcademicYearAndSemester = CurrentAcademicYearAndSemester::pluck('CurrentAcademicYearAndSemester')->first();
        $currentWeek = CurrentAcademicYearAndSemester::pluck('week')->first();
        // $currentWeekNumber = intval(preg_replace('/\D/', '', $currentWeek));

        $existingAttendance = Attendance::where('semester', $currentAcademicYearAndSemester)
            ->where('student_id', $user->student_id)
            ->where('week', $value)
            ->first();
        
        if (!$existingAttendance) {
            return true;
        }else{
            $this->message = '已有出席紀錄';
            return false;
        }

    }

    public function message()
    {
        return $this->message;
    }
}
