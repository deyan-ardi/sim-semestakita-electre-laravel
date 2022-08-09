<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TabunganExport implements FromQuery, WithHeadings
{
    protected $id;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function query()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return ['No Member', 'Nama Nasabah', 'Total Debet (Dana Masuk)',  'Total Kredit (Dana Keluar)', 'Total Saldo','Update Terakhir'];
    }
}
