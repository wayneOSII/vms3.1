<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\CurrentAcademicYearAndSemester;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ListExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $currentAcademicYearAndSemester = CurrentAcademicYearAndSemester::pluck('CurrentAcademicYearAndSemester')->first();
       // 查詢有至少 3 筆記錄的學生
       $qualifiedStudents = Attendance::where('semester', $currentAcademicYearAndSemester)
            ->where('attendance_status', '出席')
            ->select('student_id')
            ->groupBy('student_id')
            ->havingRaw('COUNT(student_id) >= 3')
            ->get();

        // 從資料庫中取得符合資格的學生的詳細資料
        $data = User::whereIn('student_id', $qualifiedStudents->pluck('student_id'))
            ->select('student_id', 'name')
            ->get();

        return $data;

        // // 在這裡返回要匯出的資料集合
        // return collect([
        //     ['John Doe', 30, 'john@example.com'],
        //     ['Jane Smith', 28, 'jane@example.com'],
        // ]);
    }

    public function headings(): array
    {
        // 定義表格的標題列
        return ['學號', '姓名'];
    }
}
