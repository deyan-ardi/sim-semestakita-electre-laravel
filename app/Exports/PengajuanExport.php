<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PengajuanExport implements FromQuery, WithHeadings
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
        return ['Tanggal Penjemputan', 'Nama Pelanggan', 'No Telepon',  'Alamat Asal', 'Lokasi Ambil Sampah','Jarak (Km)','Biaya Penjemputan (IDR)','Status','Diinput Oleh'];
    }
}
