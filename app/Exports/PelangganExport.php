<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PelangganExport implements FromQuery, WithHeadings
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
        return ['No Member', 'Nama', 'Email', 'Nomer Telepon','Tagihan Iuran', 'Alamat'];
    }
}
