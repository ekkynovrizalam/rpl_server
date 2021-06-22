<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\ReportsExport;
use Maatwebsite\Excel\Facades\Excel;


class ReportController extends Controller
{
    public static function dailyReport($kelas,$start_date,$finish_date)
    {
        return Excel::store(new ReportsExport($kelas,$start_date,$finish_date), $kelas."-".$start_date." - ".$finish_date.'.xlsx','google');
    }
}
