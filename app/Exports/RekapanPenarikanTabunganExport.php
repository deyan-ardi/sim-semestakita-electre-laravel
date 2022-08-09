<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RekapanPenarikanTabunganExport implements FromQuery, WithHeadings
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
        return ['Tanggal', 'No Penarikan', 'Nama Nasabah', 'No Member', 'No Rekening', 'Total Penarikan'];
    }
}
