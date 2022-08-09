<?php

namespace Database\Seeders;

use App\Models\User;
use Ramsey\Uuid\Uuid;
use App\Models\Kategori;
use App\Models\Tabungan;
use Faker\Factory as Faker;
use App\Models\RekapanIuran;
use App\Models\TagihanIuran;
use App\Models\RekapanSampah;
use App\Models\PembayaranRutin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\RekapanPenarikanTabungan;

class PelangganNasabahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(
            function () {
                $dateDueRange = ['-3', '-2', '-1', '+1', '+2'];
                $datenow = date('Y-m-d');
                $pembayaran = PembayaranRutin::create([
                    'id' => Uuid::uuid4(),
                    'nama_pembayaran' => 'Pembayaran Iuran',
                    'deskripsi' => 'Pembayaran Iuran',
                    'total_biaya' => 10000,
                    'tgl_generate' => 3,
                    'durasi_pembayaran' => 28,
                    'created_by' => 'Admin',
                    'updated_by' => 'Admin',
                ]);
                $kategori = Kategori::create(
                    [
                        'nama_kategori' => 'Botol',
                        'jenis_sampah' => 'nonorganik',
                        'harga_beli' => 50000,
                        'total_sampah' => 50,
                        'created_by' => 'Admin',
                        'updated_by' => 'Admin',
                    ],
                );
                $detailSampah = [
                    [
                        'nama_kategori' => $kategori->nama_kategori,
                        'harga_kategori' => $kategori->harga_beli,
                        'jumlah_sampah' => 10,
                        'sub_total' => 50000,
                    ],
                    [
                        'nama_kategori' => $kategori->nama_kategori,
                        'harga_kategori' => $kategori->harga_beli,
                        'jumlah_sampah' => 10,
                        'sub_total' => 50000,
                    ],
                ];

                $faker = Faker::create();
                for ($i = 0; $i <= 10; $i++) {
                    $mod = $i % 2;
                    $dateDueSelectedRange = $dateDueRange[rand(0, 3)];
                    $date = strtotime("$dateDueSelectedRange day");
                    $dateDue = date('Y-m-d', $date);
                    if ($datenow < $dateDue) {
                        $status = 'Unpaid';
                    } else {
                        $status = 'Overdue';
                    }
                    if ($mod == 0) {
                        $status = 'Paid';
                    }

                    $user = User::create([
                        'id' => Uuid::uuid4(),
                        'name' => $faker->name,
                        'email' => $faker->email,
                        'password' => Hash::make('12345678'),
                        'no_telp' => '081234500' . random_int(0, 200),
                        'alamat' => $faker->address,
                        'status_iuran' => $mod,
                        'no_member' => 'MB-' . strtoupper(\Str::random(8)),
                        'pembayaran_rutin_id' => $pembayaran->id,
                        'role' => rand(4, 5),
                    ]);

                    $daftarTagihan = TagihanIuran::create([
                        'id' => Uuid::uuid4(),
                        'no_tagihan' => 'TG-' . date('Ymd') . '-' . strtoupper(\Str::random(8)),
                        'user_id' => $user->id,
                        'tanggal' => $datenow,
                        'deskripsi' => 'Mantap',
                        'due_date' => $dateDue,
                        'status' => $status,
                        'sub_total' => $pembayaran->total_biaya,
                        'sub_total_denda' => 0,
                        'total_tagihan' => $pembayaran->total_biaya,
                    ]);

                    $rekapIuran = RekapanIuran::create([
                        'id' => Uuid::uuid4(),
                        'tanggal' => date('Y-m-d', strtotime($faker->timezone)),
                        'no_pembayaran' => 'TI-' . date('YmdHis') . '-' . strtoupper(\Str::random(6)),
                        'no_tagihan' => $daftarTagihan->no_tagihan,
                        'user_id' => $user->id,
                        'deskripsi' => 'Mantap Jiwa',
                        'total_tagihan' => $daftarTagihan->total_tagihan,
                    ]);

                    if ($user->role == 4) {
                        $nasabah = 'Y';
                    } else {
                        $nasabah = 'N';
                    }
                    $rekapSampah = RekapanSampah::create([
                        'id' => Uuid::uuid4(),
                        'user_id' => $user->id,
                        'kode_transaksi' => 'TS-' . date('YmdHis') . '-' . strtoupper(\Str::random(6)),
                        // 'is_nasabah' => $nasabah,
                        'total_sampah' => 10,
                        'total_beli' => 40000,
                        'created_by' => 'Admin',
                        'updated_by' => 'Admin',
                    ]);
                    $rekapSampah->detail_rekapan_sampah()->createMany($detailSampah);

                    Tabungan::create([
                        'id' => Uuid::uuid4(),
                        'user_id' => $user->id,
                        'debet' => 0,
                        'kredit' => 0,
                        'saldo' => 0,
                    ]);

                    RekapanPenarikanTabungan::create([
                        'id' => Uuid::uuid4(),
                        'user_id' => $user->id,
                        'no_penarikan' => 'TT-' . date('YmdHis') . '-' . strtoupper(\Str::random(6)),
                        'total_penarikan' => 10000,
                        'created_by' => 'Admin',
                        'updated_by' => 'Admin',
                    ]);
                }
            }
        );
    }
}
