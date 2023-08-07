<?php

namespace App\Rules;

use Closure;
use Carbon\Carbon;
use App\Models\Attendance;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Rule;
use App\Models\AttendanceVerificationCode;
use App\Models\CurrentAcademicYearAndSemester;
use Illuminate\Contracts\Validation\ValidationRule;

class VerificationCodeMatch implements Rule
{
    public $message;

    public function passes($attribute, $value)
    {
        // Retrieve the first record from the AttendanceVerificationCode model
        $firstRecord = AttendanceVerificationCode::first();

        if (!$firstRecord) {
            // If no record exists, return false (code doesn't match)
            // $this->message = '驗證碼不正確或已失效。';
            return false;
        }

        // Get the code value from the first record and compare it with the input value
        $codeMatches = $value === $firstRecord->code;

        if (!$codeMatches) {
            // $this->message = '驗證碼不正確或已失效。';
            return false;
        }

        // Check if the created_at time of the first record is within 3 minutes from now
        $createdAt = Carbon::parse($firstRecord->created_at);
        $currentTime = Carbon::now();

        // Check if the difference in minutes between the two timestamps is less than or equal to 3
        // return $currentTime->diffInMinutes($createdAt) <= 3;

        if($currentTime->diffInMinutes($createdAt) <= 3) {
            $time_valid = true;
            //return true;
        }else{
            $time_valid = false;
            // return false;
        }
        
        
        $user = Auth::user();
        $reservation = Reservation::where('student_id', $user->student_id)->first();
        $today = Carbon::today();

        $weekofday = $reservation->area->weekday;
        $weekofdayNumber = intval(preg_replace('/\D/', '', $weekofday));
        // 獲取今天是星期幾，0表示星期日，1表示星期一，以此類推
        $currentdayNumber = $today->dayOfWeek;


        $currentAcademicYearAndSemester = CurrentAcademicYearAndSemester::pluck('CurrentAcademicYearAndSemester')->first();
        $existingattendance = Attendance::where('semester', $currentAcademicYearAndSemester)->where('student_id', $user->student_id)->latest()->first();
        
        $todayDate = $today->toDateString();
        if ($existingattendance) {
            $existingattendanceDateData = $existingattendance->created_at;
            // 將時間字串轉換成 Carbon 物件
            $existingattendanceDateCarbon = Carbon::parse($existingattendanceDateData);
            $existingattendanceDate = $existingattendanceDateCarbon->toDateString();
        }else{
            $existingattendanceDate = null;
        }
        
        

        if($weekofdayNumber == $currentdayNumber) {
            $day_valid = true;
            //return true;
        }else{
            $day_valid = false; 
            //return false;
        }

        // if(!$existingattendance) {
        //     $data_valid = true;
        //     //return true;
        // }else{
        //     $data_valid = false;
        //     //return false;
        // }

        if ($existingattendanceDate === $todayDate) {
            $data_valid = false;
        }else{
            $data_valid = true;
        }

        if($time_valid && $day_valid && $data_valid){
            return true;
        }elseif($time_valid == false && $day_valid && $data_valid){
            $this->message = '驗證碼不正確或已失效。'; 
            return false;
        }elseif($time_valid && $day_valid == false && $data_valid){
            $this->message = '日期簽到錯誤';
            return false;
        }elseif($time_valid && $day_valid && $data_valid == false){
            $this->message = '已簽到搂 親';
            return false;
        }
    }

    public function message()
    {
        // return '驗證碼不正確或已失效。';
        // return $this->message;
        if ($this->message) {
            return $this->message;
        }else{
            return '驗證碼不正確或已失效。';
        }
    }
}
