<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\SemesterStartAndEnd;
use App\Models\CurrentAcademicYearAndSemester;

class UpdateAcademicYearAndSemester extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'academic:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the current academic year and semester in the database';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // Check if the academic year and semester exist in the database
        $academicYearSemester = CurrentAcademicYearAndSemester::first();

        // Calculate the academic year and semester
        $currentYear = (int) date('Y');
        $currentMonth = (int) date('n');

        if ($currentMonth >= 8 && $currentMonth <= 12) {
            $academicYear = $currentYear - 1911;
            $semester = 1;
        } elseif ($currentMonth == 1) {
            $academicYear = $currentYear - 1912;
            $semester = 1;
        } else {
            $academicYear = $currentYear - 1912;
            $semester = 2;
        }

        $result = $academicYear . '-' . $semester;

        // Fetch the last week number from the Attendance model
        $lastWeek = $academicYearSemester->week;

        // Extract the numeric part of the week number
        $INITlastWeekNumber = intval(preg_replace('/\D/', '', $lastWeek));

        if($INITlastWeekNumber == 3){
            $lastWeekNumber = 1;
        }else{
            $lastWeekNumber = $INITlastWeekNumber + 1;
        }

        // Generate the week number with the "第X週" format
        $weekNumber = '第' . ($lastWeekNumber) . '週';

        if (!$academicYearSemester) {
            // If the record doesn't exist, save the calculated values to the database
            CurrentAcademicYearAndSemester::create([
                'CurrentAcademicYearAndSemester' => $result,
                'week' => '第1週',
            ]);
        } elseif ($academicYearSemester->CurrentAcademicYearAndSemester !== $result) {
            // If the values differ, update the existing record
            $academicYearSemester->update([
                'CurrentAcademicYearAndSemester' => $result,
            ]);
        }

        // 獲取當前日期
        $today = Carbon::now();
        // 使用 format() 方法來指定日期格式
        $currentdate = $today->format('Y-m-d');

        $semester_start = SemesterStartAndEnd::pluck('semester_start')->first();

        // 獲取今天是星期幾，0表示星期日，1表示星期一，以此類推
        $currentdayNumber = $today->dayOfWeek;

        if ($currentdate == $semester_start) {
            $academicYearSemester->update([
                'week' => '第1週',
            ]);
        }

        //不超過18週及今天為禮拜一才更新
        if ($INITlastWeekNumber != 3 && $currentdayNumber == 1) {
            $academicYearSemester->update([
                'week' => $weekNumber,
            ]);
        }

        $this->info('Academic year and semester updated successfully.');
    }
}
