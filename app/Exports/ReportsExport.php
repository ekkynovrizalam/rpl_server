<?php

namespace App\Exports;

use App\Models\report;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportsExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $report = new report();
        return $report->resumeByClass('SI4202','2021-02-01','2021-07-01');
    }

    public function headings(): array
    {
        return ["NIM", "Kelas", "TIM","Jumlah Report"];
    }
}
