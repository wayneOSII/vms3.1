<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Attendance;
use App\Models\AttendanceVerificationCode;
use App\Models\Reservation;
use App\Rules\VerificationCodeMatch;
use Illuminate\Support\Facades\Auth;
use App\Models\CurrentAcademicYearAndSemester;

class CheckAttend extends Component
{
    public $verificationCode;

    public function render()
    {
        return view('livewire.check-attend');
    }

    public function checkAttendance()
    {
        $this->validate([
            'verificationCode' => ['required', new VerificationCodeMatch],
        ]);

        // // If the validation passes, you can perform any additional logic here.
        // // For example, mark attendance, redirect to another page, etc.
        // $user = Auth::user();
        // $currentAcademicYearAndSemester = CurrentAcademicYearAndSemester::pluck('CurrentAcademicYearAndSemester')->first();
        // $currentWeek = CurrentAcademicYearAndSemester::pluck('week')->first();
        
        // // Update the attendance status for matching records
        // $updatedRows = Attendance::where('student_id', $user->student_id)
        // ->where('semester', $currentAcademicYearAndSemester)
        // ->where('week', $currentWeek)
        // ->update(['attendance_status' => '出席','attendance_time' => now()]);

        $currentAcademicYearAndSemester = CurrentAcademicYearAndSemester::pluck('CurrentAcademicYearAndSemester')->first();
        $user = Auth::user();
        $code = AttendanceVerificationCode::pluck('code')->first();

        // 獲取所有需要生成出席記錄的預約，並檢查是否屬於當前學期
        $reservation = Reservation::where('semester', $currentAcademicYearAndSemester)
            ->where('student_id', $user->student_id)->first();

        // Fetch the last week number from the Attendance model
        $lastWeek = CurrentAcademicYearAndSemester::pluck('week')->first();

        // Extract the numeric part of the week number
        $INITlastWeekNumber = intval(preg_replace('/\D/', '', $lastWeek));

        if($INITlastWeekNumber <= 3){
            $lastWeekNumber = $INITlastWeekNumber;
        }else{
            $lastWeekNumber = 1;
        }

        // Generate the week number with the "第X週" format
        $weekNumber = '第' . ($lastWeekNumber) . '週';

        // foreach ($reservations as $reservation) {
            $EndOfAttendance = Attendance::where('reservation_id', $reservation->id)
                // ->where('created_at', $today)
                ->where('semester', $currentAcademicYearAndSemester)
                ->where('week', '第18週')
                ->first();
            $existingAttendance = Attendance::where('reservation_id', $reservation->id)
                ->where('week', $weekNumber)
                ->first();

            // 檢查是否已經生成了出席記錄
            if (!$EndOfAttendance && $reservation->semester == $currentAcademicYearAndSemester && !$existingAttendance) {
                // Get the cleanarea value from the related Area model
                $cleanArea = $reservation->area->cleanarea;

                // 新增出席記錄，包括 cleanarea 和週數 欄位的值
                Attendance::create([
                    'semester' => $reservation->semester,
                    'reservation_id' => $reservation->id,
                    'student_id' => $reservation->student_id,
                    'attendance_status' => '出席', // 表示尚未出席
                    'attendance_time' => now(), // 或設定一個預設值表示尚未出席時間
                    'cleanarea' => $cleanArea, // Add the cleanarea value to the Attendance record
                    'week' => $weekNumber, // Add the week number to the Attendance record
                    'code' =>$code,
                ]);
                session()->flash('success', 'Attendance updated successfully.');
            }
        // }

        // if ($updatedRows > 0) {
        //     session()->flash('success', 'Attendance updated successfully.');
        // } else {
        //     session()->flash('error', 'No records were updated.');
        // }
    }
}
