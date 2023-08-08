<?php

namespace App\Http\Controllers;

use App\Exports\ListExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{
    public function generateExcel()
    {
        return Excel::download(new ListExport, 'list.xlsx');
    }
}
