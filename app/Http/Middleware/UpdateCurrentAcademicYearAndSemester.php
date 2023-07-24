<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateCurrentAcademicYearAndSemester
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 檢查 Session 中是否已經存在計算後的學期值
        if (!session()->has('current_academic_year_semester')) {
            // 如果 Session 中不存在，則計算學期並存入 Session
            $currentYear = (int)date('Y');
            $currentMonth = (int)date('n');

            if ($currentMonth >= 8 && $currentMonth <= 12) {
                $academicYear = $currentYear - 1911;
                $semester = 1;
            } elseif($currentMonth == 1) {
                $academicYear = $currentYear - 1912;
                $semester = 1;
            } else {
                $academicYear = $currentYear - 1912;
                $semester = 2;
            }

            $result = $academicYear . '-' . $semester;
            session(['current_academic_year_semester' => $result]);
        }

        return $next($request);
    }
}
