<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\ReportsExport;
use Maatwebsite\Excel\Facades\Excel;


class ReportController extends Controller
{
    public function dailyReport($kelas,$start_date,$finish_date)
    {
        return Excel::download(new ReportsExport, 'invoices.xlsx');
    }
}
