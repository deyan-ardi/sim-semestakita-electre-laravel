<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use App\Models\Config;
use App\Models\Kriteria;
use App\Models\Tabungan;
use Faker\Factory as Faker;
use App\Models\ConfKriteria;
use App\Models\PembayaranRutin;
use Illuminate\Database\Seeder;
use App\Models\RekapanPenilaian;
use Illuminate\Support\Facades\Hash;
use App\Models\DetailRekapanPenilaian;
use Illuminate\Support\Facades\Schema;
use App\Models\PengangkutanPenilaianHarian;
use App\Models\DetailPengangkutanPenilaianHarian;

class DummyElectreSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        ConfKriteria::truncate();
        Schema::enableForeignKeyConstraints();
        $csvFileKriteriaMentah = fopen(base_path('/database/seeders/electre/kriteria_mentah.csv'), 'r');
        $firstlineKriteriaMentah = true;
        $i = 1;
        while (($dataKriteria = fgetcsv($csvFileKriteriaMentah, 2000, ',')) !== false) {
            if (! $firstlineKriteriaMentah) {
                ConfKriteria::create([
                    'id' => Uuid::uuid4(),
                    'nama_kriteria' => $dataKriteria['0'],
                    'created_at' => Carbon::now()->addMinute($i),
                    'updated_at' => Carbon::now()->addMinute($i),
                ]);
                $i++;
            }
            $firstlineKriteriaMentah = false;
        }
        fclose($csvFileKriteriaMentah);

        $faker = Faker::create('id_ID');
        $tagihan = PembayaranRutin::create([
            'id' => Uuid::uuid4(),
            'nama_pembayaran' => 'Tagihan Iuran Sampah',
            'deskripsi' => 'Tagihan Iuran Sampah Bulanan',
            'tgl_generate' => '7',
            'total_biaya' => '10000',
            'durasi_pembayaran' => '20',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        Schema::disableForeignKeyConstraints();
        User::truncate();
        Schema::enableForeignKeyConstraints();
        $csvFileUser = fopen(base_path('/database/seeders/electre/nasabah_pelanggan.csv'), 'r');
        $firstlineUser = true;
        while (($dataUser = fgetcsv($csvFileUser, 2000, ',')) !== false) {
            if (! $firstlineUser) {
                $userAdd =   User::create([
                    'id' => Uuid::uuid4(),
                    'no_member' => 'MB-' . date('Ymd') . '-' . random_int(0, 999),
                    'name' => $dataUser[0],
                    'email' => $faker->email,
                    'password' => Hash::make('12345678'),
                    'password_whatsapp' => Hash::make('12345678'),
                    // 'no_telp' => '081915' . random_int(100000, 999999),
                    'pembayaran_rutin_id' => $tagihan->id,
                    'status_iuran' => 1,
                    'role' => random_int(4, 5),
                ]);

                Tabungan::create([
                    'id' => Uuid::uuid4(),
                    'user_id' => $userAdd->id,
                    'saldo' => '0',
                    'debet' => '0',
                    'kredit' => '0',
                ]);
            }
            $firstlineUser = false;
        }
        fclose($csvFileUser);

        for ($i = 1; $i <= 6; $i++) {
            if ($i == 1) {
                $data =
                    [
                        'key' => 'denda',
                        'name' => 'Denda Keterlambatan',
                        'value' => 10000,
                        'status' => 'deactive',
                    ];
            } elseif ($i == 2) {
                $data =
                    [
                        'key' => 'penjemputan',
                        'name' => 'Biaya Penjemputan Berdasarkan Jarak Per Km',
                        'value' => 10000,
                        'status' => 'deactive',
                    ];
            } elseif ($i == 3) {
                $data =
                    [
                        'key' => 'sisa-tabungan',
                        'name' => 'Minimum Sisa Tabungan',
                        'value' => 10000,
                        'status' => 'deactive',
                    ];
            } elseif ($i == 4) {
                $data =
                    [
                        'key' => 'minimal-penilaian',
                        'name' => 'Minimal Jumlah Penilaian',
                        'value' => 12,
                        'status' => 'active',
                    ];
            } elseif ($i == 5) {
                $data =
                    [
                        'key' => 'unpublish-time',
                        'name' => 'Rentang Waktu Unpublish',
                        'value' => 30,
                        'status' => 'active',
                    ];
            } else {
                $data =
                    [
                        'key' => 'hari-penilaian',
                        'name' => 'Lama Hari Penilaian',
                        'value' => 15,
                        'status' => 'active',
                    ];
            }
            Config::create($data);
        }
        //setting
        $banyak_bulan = 1;
        $banyak_penilaian = 0;
        for ($jml_data = 0; $jml_data < $banyak_bulan; $jml_data++) {
            $kriteria_mentah = ConfKriteria::orderBy('created_at', 'asc')->get();
            foreach ($kriteria_mentah as $i => $item) {
                Kriteria::create([
                    'id' => Uuid::uuid4(),
                    'periode' => $jml_data == 0 ? Carbon::now()->format('F Y') : Carbon::now()->addMonth()->format('F Y'),
                    'urutan' => $i + 1,
                    'nama_kriteria' => ucWords(strtolower($item->nama_kriteria)),
                    'bobot' => 10,
                    'publish' => '1',
                ]);
            }

            $config = Config::where('key', 'hari-penilaian')->first();
            $userGet = User::where('role', 4)->orWhere('role', 5)->get();
            foreach ($userGet as $user) {
                $kriteria = Kriteria::orderBy('urutan', 'asc')->get();
                for ($j = 1; $j <= $config->value - $banyak_penilaian; $j++) {
                    //create random datetime this month
                    if ($jml_data == 0) {
                        $start = Carbon::now()->startOfMonth();
                        $end = Carbon::now()->endOfMonth();
                    } else {
                        $start = Carbon::now()->addMonth()->startOfMonth();
                        $end = Carbon::now()->addMonth()->endOfMonth();
                    }
                    $datetime = Carbon::createFromTimestamp(rand($start->timestamp, $end->timestamp));
                    $penilaian = PengangkutanPenilaianHarian::create([
                        'id' => Uuid::uuid4(),
                        'tanggal_angkut_penilaian' => $datetime,
                        'user_id' => $user->id,
                        'pegawai_id' => $user->id,
                    ]);

                    $status = ['iya', 'tidak'];
                    foreach ($kriteria as $item) {
                        DetailPengangkutanPenilaianHarian::create([
                            'id' => Uuid::uuid4(),
                            'pengangkutan_penilaian_harian_id' => $penilaian->id,
                            'kriteria_id' => $item->id,
                            'nilai_kriteria' => $status[array_rand($status)],
                        ]);
                    }
                }

                // Update Total Penilaian
                if ($jml_data == 0) {
                    $periode = Carbon::now()->format('F Y');
                    $bulan = Carbon::now()->format('m');
                    $tahun = Carbon::now()->format('Y');
                    $find_rekapan = RekapanPenilaian::where('user_id', $user->id)->where('periode', $periode)->first();
                    $count_penilaian_bulanan = PengangkutanPenilaianHarian::where('user_id', $user->id)->whereMonth('tanggal_angkut_penilaian', $bulan)->whereYear('tanggal_angkut_penilaian', $tahun)->count();
                    if (! empty($find_rekapan)) {
                        DetailRekapanPenilaian::where('rekapan_penilaian_id', $find_rekapan->id)->delete();
                        // Rekapan
                        $find_rekapan->jumlah_penilaian = $count_penilaian_bulanan;
                        $find_rekapan->save();

                        // Detail Kriteria
                        $kriteria = Kriteria::where('periode', $periode)->orderBy('urutan', 'asc')->get();
                        foreach ($kriteria as $item) {
                            $detail_pengangkutan = DetailPengangkutanPenilaianHarian::join('pengangkutan_penilaian_harian', 'pengangkutan_penilaian_harian.id', '=', 'detail_pengangkutan_penilaian_harian.pengangkutan_penilaian_harian_id')->where('kriteria_id', $item->id)->where('nilai_kriteria', 'iya')->where('pengangkutan_penilaian_harian.user_id', $user->id)->whereMonth('pengangkutan_penilaian_harian.tanggal_angkut_penilaian', $bulan)->whereYear('tanggal_angkut_penilaian', $tahun)->count();

                            DetailRekapanPenilaian::create([
                                'id' => Uuid::uuid4(),
                                'rekapan_penilaian_id' => $find_rekapan->id,
                                'kriteria_id' => $item->id,
                                'total_nilai' => $detail_pengangkutan,
                            ]);
                        }
                    } else {
                        $rekapan_penilaian = RekapanPenilaian::create([
                            'id' => Uuid::uuid4(),
                            'user_id' => $user->id,
                            'periode' => $periode,
                            'jumlah_penilaian' => $count_penilaian_bulanan,
                        ]);

                        // Kriteria
                        $kriteria = Kriteria::where('periode', $periode)->orderBy('urutan', 'asc')->get();
                        foreach ($kriteria as $item) {
                            $detail_pengangkutan = DetailPengangkutanPenilaianHarian::join('pengangkutan_penilaian_harian', 'pengangkutan_penilaian_harian.id', '=', 'detail_pengangkutan_penilaian_harian.pengangkutan_penilaian_harian_id')->where('kriteria_id', $item->id)->where('nilai_kriteria', 'iya')->where('pengangkutan_penilaian_harian.user_id', $user->id)->whereMonth('pengangkutan_penilaian_harian.tanggal_angkut_penilaian', $bulan)->whereYear('tanggal_angkut_penilaian', $tahun)->count();

                            DetailRekapanPenilaian::create([
                                'id' => Uuid::uuid4(),
                                'rekapan_penilaian_id' => $rekapan_penilaian->id,
                                'kriteria_id' => $item->id,
                                'total_nilai' => $detail_pengangkutan,
                            ]);
                        }
                    }
                } else {
                    $periode = Carbon::now()->addMonth()->format('F Y');
                    $bulan = Carbon::now()->addMonth()->format('m');
                    $tahun = Carbon::now()->addMonth()->format('Y');
                    $find_rekapan = RekapanPenilaian::where('user_id', $user->id)->where('periode', $periode)->first();
                    $count_penilaian_bulanan = PengangkutanPenilaianHarian::where('user_id', $user->id)->whereMonth('tanggal_angkut_penilaian', $bulan)->whereYear('tanggal_angkut_penilaian', $tahun)->count();
                    if (! empty($find_rekapan)) {
                        DetailRekapanPenilaian::where('rekapan_penilaian_id', $find_rekapan->id)->delete();
                        // Rekapan
                        $find_rekapan->jumlah_penilaian = $count_penilaian_bulanan;
                        $find_rekapan->save();

                        // Detail Kriteria
                        $kriteria = Kriteria::where('periode', $periode)->orderBy('urutan', 'asc')->get();
                        foreach ($kriteria as $item) {
                            $detail_pengangkutan = DetailPengangkutanPenilaianHarian::join('pengangkutan_penilaian_harian', 'pengangkutan_penilaian_harian.id', '=', 'detail_pengangkutan_penilaian_harian.pengangkutan_penilaian_harian_id')->where('kriteria_id', $item->id)->where('nilai_kriteria', 'iya')->where('pengangkutan_penilaian_harian.user_id', $user->id)->whereMonth('pengangkutan_penilaian_harian.tanggal_angkut_penilaian', $bulan)->whereYear('tanggal_angkut_penilaian', $tahun)->count();

                            DetailRekapanPenilaian::create([
                                'id' => Uuid::uuid4(),
                                'rekapan_penilaian_id' => $find_rekapan->id,
                                'kriteria_id' => $item->id,
                                'total_nilai' => $detail_pengangkutan,
                            ]);
                        }
                    } else {
                        $rekapan_penilaian = RekapanPenilaian::create([
                            'id' => Uuid::uuid4(),
                            'user_id' => $user->id,
                            'periode' => $periode,
                            'jumlah_penilaian' => $count_penilaian_bulanan,
                        ]);

                        // Kriteria
                        $kriteria = Kriteria::where('periode', $periode)->orderBy('urutan', 'asc')->get();
                        foreach ($kriteria as $item) {
                            $detail_pengangkutan = DetailPengangkutanPenilaianHarian::join('pengangkutan_penilaian_harian', 'pengangkutan_penilaian_harian.id', '=', 'detail_pengangkutan_penilaian_harian.pengangkutan_penilaian_harian_id')->where('kriteria_id', $item->id)->where('nilai_kriteria', 'iya')->where('pengangkutan_penilaian_harian.user_id', $user->id)->whereMonth('pengangkutan_penilaian_harian.tanggal_angkut_penilaian', $bulan)->whereYear('tanggal_angkut_penilaian', $tahun)->count();

                            DetailRekapanPenilaian::create([
                                'id' => Uuid::uuid4(),
                                'rekapan_penilaian_id' => $rekapan_penilaian->id,
                                'kriteria_id' => $item->id,
                                'total_nilai' => $detail_pengangkutan,
                            ]);
                        }
                    }
                }
            }
        }
    }
}
