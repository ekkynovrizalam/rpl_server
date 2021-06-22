<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\ReportExport;

class ReportController extends Controller
{
    public function dailyReport($kelas,$start_date,$finish_date)
    {
        return Excel::store(new ReportExport, 'invoices.xlsx', 's3');
    }
}
