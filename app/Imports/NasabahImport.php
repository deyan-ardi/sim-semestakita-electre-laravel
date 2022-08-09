<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Tabungan;
use App\Models\PembayaranRutin;
use Ramsey\Uuid\Nonstandard\Uuid;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class NasabahImport implements ToModel, WithHeadingRow, SkipsOnError, WithValidation
{
    use Importable;
    use SkipsErrors;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $pembayaranRutin = PembayaranRutin::first();
        $pembayaranRutinId = $pembayaranRutin->id;
        $user = User::create([
            'id'       => Uuid::uuid4(),
            'no_member' => 'MB-' . date('Ymd') . '-' . rand(1000, 9999),
            'no_rekening' => $row['no_rekening'],
            'name'     => $row['name'],
            'email'    => $row['email'],
            'password' => Hash::make($row['password']),
            'no_telp'  => $row['no_telp'],
            'alamat'   => $row['alamat'],
            'pembayaran_rutin_id' => $pembayaranRutinId,
            'role'     => 4,
            'status_iuran' => 1,
        ]);

        // Tambahkan tabungan
        Tabungan::create([
            'id' => Uuid::uuid4(),
            'user_id' => $user->id,
            'debet' => 0,
            'kredit' => 0,
            'saldo' => 0,
        ]);

        return $user;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'email' => ['required', 'unique:users', 'email'],
            'password' => ['required', 'min:8'],
            'no_rekening' => ['required', 'numeric', 'unique:users', 'digits_between:10,15'],
            'no_telp' => ['required', 'numeric', 'digits_between:8,15', 'unique:users'],
            'alamat' => ['required'],
        ];
    }
}
