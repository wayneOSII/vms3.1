<?php

namespace App\Http\Controllers;


use PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Certification;
use App\Models\SemesterStartAndEnd;

class PdfController extends Controller
{
    public function download(Certification $record)
    {
        // dd($record);
        $semester_start = SemesterStartAndEnd::pluck('semester_start')->first();
        $semester_end = SemesterStartAndEnd::pluck('semester_end')->first();
        $formatted_semster_end = Carbon::createFromFormat('Y-m-d', $semester_end);
        $formatted_semster_start = Carbon::createFromFormat('Y-m-d', $semester_start);

        $start_year = $formatted_semster_start->year;
        $start_month = $formatted_semster_start->month;
        $start_day = $formatted_semster_start->day;
        $start_rocyear = $start_year - 1911;

        $end_year = $formatted_semster_end->year;
        $end_month = $formatted_semster_end->month;
        $end_day = $formatted_semster_end->day;
        $end_rocyear = $end_year - 1911;

        $semester = $record->semester;
        $semester_parts = explode("-", $semester);

        $semester_firstPart = $semester_parts[0]; // "111"
        $semester_secondPart = $semester_parts[1]; // "2"


        $data = [
            // 'title' => 'Sample PDF',
            // 'content' => 'This is the content of the PDF file.',
            'title' => '樂青志工服務時數證明',
            'name' => $record->name,
            'student_id' => $record->student_id,
            'semester' => $semester_firstPart,
            'end_rocyear' => $end_rocyear,
            'end_month' => $end_month,
            'end_day' => $end_day,
            'start_rocyear' => $start_rocyear,
            'start_month' => $start_month,
            'start_day' => $start_day,
        ];

        
        // $htmlContent = view('pdf.template', $data)->render();

        //     echo $htmlContent;

        // $dompdf->loadHtml($htmlContent);

        // $dompdf->render();

        // $pdfOutput = $dompdf->output();
        // $response = new \Illuminate\Http\Response($pdfOutput, 200, [
        //     'Content-Type' => 'application/pdf',
        //     'Content-Disposition' => 'inline; filename="certificate.pdf"',
        // ]);

        // return $response;

       


        $pdf = PDF::loadView('pdf.template', $data);

        return $pdf->stream('certificate.pdf');
    }
}
