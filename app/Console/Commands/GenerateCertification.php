<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\Certification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\CurrentAcademicYearAndSemester;
use App\Models\User;

class GenerateCertification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-certification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate student certification records';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currentAcademicYearAndSemester = CurrentAcademicYearAndSemester::pluck('CurrentAcademicYearAndSemester')->first();

        $attendanceCounts = Attendance::select('student_id', 'semester', DB::raw('count(*) as count'))
            ->where('semester', $currentAcademicYearAndSemester)
            ->groupBy('student_id', 'semester')
            ->having('count', '>=', 3)
            ->get();

        foreach ($attendanceCounts as $attendanceCount) {
            $studentId = $attendanceCount->student_id;
            $semester = $attendanceCount->semester;
            $count = $attendanceCount->count;

            $sutdentName = User::where('student_id', $studentId)
                ->pluck('name')
                ->first();

            $existingdata = Certification::where('student_id', $studentId)
                ->where('semester', $currentAcademicYearAndSemester)
                ->first();

            if (!$existingdata) {
                // 新增出席記錄，包括 cleanarea 和週數 欄位的值
                Certification::create([
                    'semester' => $semester,
                    'student_id' => $studentId,
                    'name' => $sutdentName,
                ]);
            }

        }

        $this->info('Certification records generated successfully!');
    }
}
