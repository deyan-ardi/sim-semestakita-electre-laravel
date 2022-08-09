<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RekomendasiExport implements FromQuery, WithHeadings
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
        return ['Ranking (Paling Direkomendasikan)', 'Periode', 'No Member', 'Nama Pelanggan/Nasabah', 'Hasil Electre'];
    }
}
