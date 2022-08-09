<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class NasabahOneExport implements FromQuery, WithHeadings
{
    protected $id;

    public function __construct($id)
    {
        $this->ids = $id;
    }

    public function query()
    {
        $user = User::select('no_member', 'name', 'no_rekening', 'email', 'no_telp', 'alamat')->where('id', $this->ids);
        return $user;
    }

    public function headings(): array
    {
        return ['No Member', 'Nama', 'No Rekening',  'Email', 'Nomer Telepon', 'Alamat'];
    }
}
