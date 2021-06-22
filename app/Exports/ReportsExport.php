<?php

namespace App\Exports;

use App\Models\report;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportsExport implements FromCollection,WithHeadings
{
    public function __construct($kelas,$start_date,$finish_date)
    {
        $this->kelas = $kelas,
        $this->start_date = $start_date,
        $this->finish_date = $finish_date;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $report = new report();
        return $report->resumeByClass($this->kelas,$this->start_date,$this->finish_date);
    }

    public function headings(): array
    {
        return ["NIM", "Kelas", "TIM","Jumlah Report"];
    }
}
