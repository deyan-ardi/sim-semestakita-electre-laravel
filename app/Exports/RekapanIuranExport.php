<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RekapanIuranExport implements FromQuery, WithHeadings
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
        return ['Tanggal Dibayar', 'Nama', 'No Member',  'No Tagihan','No Pembayaran', 'Nama Tagihan', 'Sub Total Tagihan','Sub Total Denda','Status Denda','Total Akhir Tagihan'];
    }
}
