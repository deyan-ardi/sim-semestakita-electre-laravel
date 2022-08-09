<?php

namespace App\Http\Controllers\API;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use App\Models\Config;
use App\Models\Kriteria;
use App\Models\LogActivity;
use Illuminate\Http\Request;
use InvalidArgumentException;
use App\Models\RekapanPenilaian;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\DetailRekapanPenilaian;
use Illuminate\Support\Facades\Validator;
use App\Models\PengangkutanPenilaianHarian;
use App\Models\DetailPengangkutanPenilaianHarian;

class PengangkutanPenilaianController extends Controller
{
    public function getAllPengangkutan()
    {
        DB::beginTransaction();
        try {
            $penilaian = PengangkutanPenilaianHarian::select('users.id as id_user', 'users.name', 'users.no_member', 'pengangkutan_penilaian_harian.*')->join('users', 'pengangkutan_penilaian_harian.user_id', '=', 'users.id')->whereDate('tanggal_angkut_penilaian', Carbon::now()->format('Y-m-d'))->get();
            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'API\PengangkutanPenilaianController.php',
                'action' => 'Mengambil Data Pengangkutan Penilaian Hari Ini',
            ]);
            // End Log
            DB::commit();
            return ResponseFormatter::success(
                $penilaian,
                'Data Pengangkutan Penilaian Hari Ini Didapatkan',
            );
        } catch (Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'success' => false,
                'message' => $e,
            ], 'Internal Server Error', 500);
        }
    }

    public function filter($date)
    {
        DB::beginTransaction();
        try {
            $date = Carbon::parse($date)->format('Y-m-d');
            $penilaian = PengangkutanPenilaianHarian::select('users.id as id_user', 'users.name', 'users.no_member', 'pengangkutan_penilaian_harian.*')->join('users', 'pengangkutan_penilaian_harian.user_id', '=', 'users.id')->whereDate('tanggal_angkut_penilaian', $date)->get();
            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'API\PengangkutanPenilaianController.php',
                'action' => 'Mengambil Data Pengangkutan Penilaian Hasil Filter Hari Ini',
            ]);
            // End Log
            DB::commit();
            return ResponseFormatter::success(
                $penilaian,
                'Data Pengangkutan Penilaian Iuran Hasil Filter didapat'
            );
        } catch (InvalidArgumentException $e) {
            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'API\PengangkutanPenilaianController.php',
                'action' => 'Gagal Mengambil Data Pengangkutan Penilaian Iuran, Filter Data Bukan Tanggal',
            ]);
            // End Log
            DB::rollback();
            return ResponseFormatter::error(['error' => $e], 'Bukan Tanggal', 400);
        } catch (Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'success' => false,
                'message' => $e,
            ], 'Internal Server Error', 500);
        }
    }

    public function storeData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_user' => 'required',
            'id_kriteria.*' => 'required',
            'nilai_kriteria.*' => 'required|in:iya,tidak',
        ]);
        if ($validator->fails()) {
            $validator->validate();
            return ResponseFormatter::error([
                'success' => false,
                'message' => 'Data tidak valid',
            ], 'Bad Request', 400);
        }
        DB::beginTransaction();
        try {
            $id_user = $request->id_user;
            // Cek apakah usernya ada
            $find_user = User::where('id', $id_user)->where('status_iuran', '=', 1)->where(function ($query) {
                $query->where('role', 4);
                $query->orWhere('role', 5);
            })->first();

            // Get Config Data
            $config = Config::where('key', 'hari-penilaian')->first();
            $kriteria_total = Kriteria::where('periode', Carbon::now()->format('F Y'))->where('publish', '1')->orderBy('urutan', 'ASC')->count();
            if ($kriteria_total > 0) {
                //Cek dlu berapa kali dia melakukan penilaian
                $penilaian_harian = PengangkutanPenilaianHarian::where('user_id', $find_user->id)->whereDate('tanggal_angkut_penilaian', Carbon::now()->format('Y-m-d'))->count();
                if ($penilaian_harian > 0) {
                    DB::rollback();
                    return ResponseFormatter::error([
                        'success' => false,
                        'message' => 'Penilaian untuk ditanggal ini sudah ada sebelumnya',
                    ], 'Bad Request', 400);
                }
                $penilaian_bulanan = PengangkutanPenilaianHarian::where('user_id', $find_user->id)->whereMonth('tanggal_angkut_penilaian', Carbon::now()->format('m'))->whereYear('tanggal_angkut_penilaian', Carbon::now()->format('Y'))->get();
                if ($config->status == 'active' && $penilaian_bulanan->count() + 1 > $config->value) {
                    DB::rollback();
                    return ResponseFormatter::error([
                        'success' => false,
                        'message' => 'Penilaian untuk pelanggan/nasabah yang dipindai sudah melebihi batas',
                    ], 'Bad Request', 400);
                }
                // Tambahkan Pengangkutan Penilaian Harian
                $penilaian = PengangkutanPenilaianHarian::create([
                    'id' => Uuid::uuid4(),
                    'user_id' => $find_user->id,
                    'pegawai_id' => Auth::user()->id,
                    'tanggal_angkut_penilaian' => Carbon::now()->format('Y-m-d H:i:s'),
                ]);

                // Buat Detail Pengangkutan Penilaian Harian
                for ($i = 0; $i < count($request->id_kriteria); $i++) {
                    DetailPengangkutanPenilaianHarian::create([
                        'id' => Uuid::uuid4(),
                        'pengangkutan_penilaian_harian_id' => $penilaian->id,
                        'kriteria_id' => $request->id_kriteria[$i],
                        'nilai_kriteria' => $request->nilai_kriteria[$i],
                    ]);
                }

                // Update Total Penilaian
                $find_rekapan = RekapanPenilaian::where('user_id', $find_user->id)->where('periode', Carbon::now()->format('F Y'))->first();
                $count_penilaian_bulanan = PengangkutanPenilaianHarian::where('user_id', $find_user->id)->whereMonth('tanggal_angkut_penilaian', Carbon::now()->format('m'))->whereYear('tanggal_angkut_penilaian', Carbon::now()->format('Y'))->count();
                if (! empty($find_rekapan)) {
                    DetailRekapanPenilaian::where('rekapan_penilaian_id', $find_rekapan->id)->delete();

                    // Rekapan
                    $find_rekapan->jumlah_penilaian = $count_penilaian_bulanan;
                    $find_rekapan->save();

                    // Detail Kriteria
                    $kriteria = Kriteria::where('periode', Carbon::now()->format('F Y'))->where('publish', '1')->orderBy('urutan', 'ASC')->get();
                    foreach ($kriteria as $item) {
                        $detail_pengangkutan = DetailPengangkutanPenilaianHarian::join('pengangkutan_penilaian_harian', 'pengangkutan_penilaian_harian.id', '=', 'detail_pengangkutan_penilaian_harian.pengangkutan_penilaian_harian_id')->where('kriteria_id', $item->id)->where('nilai_kriteria', 'iya')->where('pengangkutan_penilaian_harian.user_id', $find_user->id)->whereMonth('pengangkutan_penilaian_harian.tanggal_angkut_penilaian', Carbon::now()->format('m'))->whereYear('tanggal_angkut_penilaian', Carbon::now()->format('Y'))->count();

                        DetailRekapanPenilaian::create([
                            'id' => Uuid::uuid4(),
                            'rekapan_penilaian_id' => $find_rekapan->id,
                            'kriteria_id' => $item->id,
                            'total_nilai' => $detail_pengangkutan == 0 ? 1 : $detail_pengangkutan,
                        ]);
                    }
                } else {
                    $rekapan_penilaian = RekapanPenilaian::create([
                        'id' => Uuid::uuid4(),
                        'user_id' => $find_user->id,
                        'periode' => Carbon::now()->format('F Y'),
                        'jumlah_penilaian' => $count_penilaian_bulanan,
                    ]);

                    // Kriteria
                    $kriteria = Kriteria::where('periode', Carbon::now()->format('F Y'))->where('publish', '1')->orderBy('urutan', 'ASC')->get();
                    foreach ($kriteria as $item) {
                        $detail_pengangkutan = DetailPengangkutanPenilaianHarian::join('pengangkutan_penilaian_harian', 'pengangkutan_penilaian_harian.id', '=', 'detail_pengangkutan_penilaian_harian.pengangkutan_penilaian_harian_id')->where('kriteria_id', $item->id)->where('nilai_kriteria', 'iya')->where('pengangkutan_penilaian_harian.user_id', $find_user->id)->whereMonth('pengangkutan_penilaian_harian.tanggal_angkut_penilaian', Carbon::now()->format('m'))->whereYear('tanggal_angkut_penilaian', Carbon::now()->format('Y'))->count();

                        DetailRekapanPenilaian::create([
                            'id' => Uuid::uuid4(),
                            'rekapan_penilaian_id' => $rekapan_penilaian->id,
                            'kriteria_id' => $item->id,
                            'total_nilai' => $detail_pengangkutan == 0 ? 1 : $detail_pengangkutan,
                        ]);
                    }
                }
                // Send Whatsapp To User When Finish Transaction
                $message = Controller::message_pengangkutan($find_user->name, $penilaian->created_at, Auth::user()->name);
                if ($find_user->no_telp != '') {
                    // send message to whatsapp number
                    Controller::sendMessage($find_user->no_telp, $message);
                    Controller::email_pengangkutan($penilaian->created_at, Auth::user()->name, $find_user->email, $find_user->name);
                } else {
                    Controller::email_pengangkutan($penilaian->created_at, Auth::user()->name, $find_user->email, $find_user->name);
                }

                // Send Notif For Website
                $pesan_notif = Controller::notif_pengangkutan($penilaian->created_at, Auth::user()->name);
                Controller::storeNotification($find_user->id, 'angkut', 'Pengangkutan Sampah', $pesan_notif);
                // End Send Notif When Finish Transaction

                $penilaian_all = PengangkutanPenilaianHarian::whereDate('tanggal_angkut_penilaian', Carbon::now()->format('Y-m-d'))->get();

                // Log Activity
                LogActivity::create([
                    'ip_address' => request()->ip(),
                    'user_id' => Auth::user()->id,
                    'previous_url' => URL::previous(),
                    'current_url' => URL::current(),
                    'file' => 'API\PengangkutanPenilaianController.php',
                    'action' => 'Menambahkan Data Pengangkutan Penilaian Harian',
                ]);
                // End Log

                DB::commit();
                return ResponseFormatter::success(
                    $penilaian_all,
                    'Data Pengangkutan Penilaian Iuran Berhasil Ditambahkan',
                );
            }
            DB::rollback();
            return ResponseFormatter::error([
                'success' => false,
                'message' => 'Data Kriteria Penilaian Harian Tidak Ditemukan',
            ], 'Bad Request', 400);
        } catch (Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'success' => false,
                'message' => $e,
            ], 'Internal Server Error', 500);
        }
    }
}
