<?php

namespace App\Rules;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Rule;
use App\Models\CurrentAcademicYearAndSemester;
use App\Models\Reservation;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueReservation implements Rule
{
    public $message;

    public function passes($attribute, $value)
    {
        $user = Auth::user();
        $currentAcademicYearAndSemester = CurrentAcademicYearAndSemester::pluck('CurrentAcademicYearAndSemester')->first();
        $currentWeek = CurrentAcademicYearAndSemester::pluck('week')->first();
        // $currentWeekNumber = intval(preg_replace('/\D/', '', $currentWeek));

        $existingReservation = Reservation::where('semester', $currentAcademicYearAndSemester)
            ->where('student_id', $user->student_id)
            ->first();
        
        if (!$existingReservation) {
            return true;
        }else{
            $this->message = '本學期已有預約紀錄';
            return false;
        }

    }

    public function message()
    {
        return $this->message;
    }
}
