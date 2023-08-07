<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Attendance;
use App\Models\Reservation;
use Illuminate\Console\Command;
use App\Models\CurrentAcademicYearAndSemester;

class GenerateAttendance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-attendance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate attendance records for reservations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        session()->start();
        // 獲取當前學期的值，假設是使用 session 存儲的
        $currentAcademicYearAndSemester = CurrentAcademicYearAndSemester::pluck('CurrentAcademicYearAndSemester')->first();

        // 獲取今天日期
        $today = Carbon::now();

        // 獲取所有需要生成出席記錄的預約，並檢查是否屬於當前學期
        $reservations = Reservation::all();

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

        foreach ($reservations as $reservation) {
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
                
                //
                $weekofday = $reservation->area->weekday;
                $weekofdayNumber = intval(preg_replace('/\D/', '', $weekofday));
                // 獲取今天是星期幾，0表示星期日，1表示星期一，以此類推
                $currentdayNumber = $today->dayOfWeek;
                //

                if ($weekofdayNumber === $currentdayNumber) {
                    // 新增出席記錄，包括 cleanarea 和週數 欄位的值
                    Attendance::create([
                        'semester' => $reservation->semester,
                        'reservation_id' => $reservation->id,
                        'student_id' => $reservation->student_id,
                        'attendance_status' => '缺席', // 表示尚未出席
                        'attendance_time' => null, // 或設定一個預設值表示尚未出席時間
                        'cleanarea' => $cleanArea, // Add the cleanarea value to the Attendance record
                        'week' => $weekNumber, // Add the week number to the Attendance record
                        'code' => null,
                    ]);
                }
                
            }
        }

        $this->info('Attendance records generated successfully!');
    }
}

// function getCurrentAcademicYearAndSemester() {
//     // 取得當前的年份和月份
//     $currentYear = 2024;
//     $currentMonth = (int)date('n');

//     // 判斷學年度和學期
//     if ($currentMonth >= 8 && $currentMonth <= 12) {
//         // 如果當前月份是 8 月到 12 月，表示為下學期的一部分
//         // 學年度即為當前年份 - 1911，學期為 2
//         $academicYear = $currentYear - 1911;
//         $semester = 1;
//     } elseif($currentMonth == 1) {
//         $academicYear = $currentYear - 1912;
//         $semester = 1;
//     } else {
//         // 其他月份表示為上學期或假期
//         // 學年度即為當前年份 - 1912，學期為 1
//         $academicYear = $currentYear - 1912;
//         $semester = 2;
//     }

//     // 組成字串
//     $result = $academicYear .'-'. $semester;

//     return $result;
// }