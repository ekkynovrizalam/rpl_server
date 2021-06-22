<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\report;

class ReportController extends Controller
{
    public function dailyReport($kelas,$start_date,$finish_date)
    {
	$report = new report();
        dd($report->resumeByClass('SI4202','2021-02-01','2021-07-01'));
    }
}
