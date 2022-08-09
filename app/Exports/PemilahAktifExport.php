<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PemilahAktifExport implements FromQuery, WithHeadings
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
        return ['Ranking Rekomendasi', 'Periode', 'No Member', 'Nama Pelanggan/Nasabah', 'Total Point', 'Alasan Terpilih'];
    }
}
