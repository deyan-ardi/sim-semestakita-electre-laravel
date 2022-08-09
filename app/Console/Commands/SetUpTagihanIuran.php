<?php

namespace App\Console\Commands;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Str;
use App\Models\TagihanIuran;
use App\Models\PembayaranRutin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SetUpTagihanIuran extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tagihan:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'For generate tagihan';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $pembayaranRutin = PembayaranRutin::all();
        $sekarang = Carbon::now()->format('Y-m-d');
        $tanggalSekarang = Carbon::now()->format('d');
        $bulanSekarang = Carbon::now()->format('m');

        if ($pembayaranRutin->count() <= 0) {
            return $this->info('Gagal Di Generate, Pembayaran Rutin Tidak Ada');
        }

        foreach ($pembayaranRutin as $item) {
            $tagihanBulanIni = TagihanIuran::join('users', 'users.id', 'tagihan_iuran.user_id')
                ->select('users.pembayaran_rutin_id', 'tagihan_iuran.*')
                ->whereMonth('tagihan_iuran.tanggal', $bulanSekarang)
                ->where('users.pembayaran_rutin_id', $item->id)
                ->where('users.status_iuran', 1)
                ->count();
            if ($tagihanBulanIni <= 0 && $item->tgl_generate == $tanggalSekarang) {
                DB::beginTransaction();
                try {
                    $idPembayaranRutin = $item->id;
                    $pembayaranRutin = PembayaranRutin::where('id', $idPembayaranRutin)->first();
                    $siswaYangDapatTagihan = User::where('pembayaran_rutin_id', $idPembayaranRutin)->where('status_iuran', 1)->get();
                    $total_denda = 0;
                    foreach ($siswaYangDapatTagihan as $siswa) {
                        $date = strtotime("+$pembayaranRutin->durasi_pembayaran day");
                        $dateDue = date('Y-m-d', $date); // 1 minggu setelah dibuat

                        $tagihan = [
                            'id' => Uuid::uuid4(),
                            'tanggal' => $sekarang,
                            'user_id' => $siswa->id,
                            'no_tagihan' => 'TG-' . date('Ymd') . '-' . strtoupper(Str::random(8)),
                            'deskripsi' => $pembayaranRutin->nama_pembayaran,
                            'due_date' => $dateDue,
                            'status' => 'Unpaid',
                            'sub_total' => $pembayaranRutin->total_biaya,
                            'sub_total_denda' => $total_denda,
                            'total_tagihan' => $pembayaranRutin->total_biaya + $total_denda,
                        ];

                        $daftar_tagihan = TagihanIuran::create($tagihan); // 1 record

                        if ($siswa->no_telp != '') {
                            $message = Controller::message_tagihan_baru($siswa->name, $daftar_tagihan->deskripsi, $daftar_tagihan->created_at, $daftar_tagihan->total_tagihan);
                            $wa_send = Controller::sendMessage($siswa->no_telp, $message);
                            if ($wa_send['kode'] != 200) {
                                Controller::email_tagihan_baru($siswa->email, $daftar_tagihan->deskripsi, $daftar_tagihan->created_at, $daftar_tagihan->total_tagihan, $siswa->name);
                                $this->info('Berhasil Ditambahkan, Namun Pesan Tidak Berhasil Dikirim, Digantikan Email');
                            }
                            $this->info('Berhasil Ditambahkan, Pesan Berhasil Dikirim');
                        } else {
                            Controller::email_tagihan_baru($siswa->email, $daftar_tagihan->deskripsi, $daftar_tagihan->created_at, $daftar_tagihan->total_tagihan, $siswa->name);
                            $this->info('Berhasil Ditambahkan, Email Berhasil Dikirim');
                        }
                    }
                    DB::commit();
                    return $this->info('Tagihan sukses dibuat');
                } catch (Exception $e) {
                    DB::rollback();
                    return $this->info('Internal Server Error:' . $e);
                }
            }
        }
    }
}
