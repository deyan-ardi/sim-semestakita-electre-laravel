<?php

namespace App\Http\Controllers\API;

use Exception;
use Carbon\Carbon;
use App\Models\LogActivity;
use App\Models\PemilahAktif;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RekomendasiController extends Controller
{
    public function allRekomendasi($bulan, $tahun)
    {
        DB::beginTransaction();
        try {
            if ($bulan && $tahun) {
                $periode = $bulan . ' ' . $tahun;
                $pemilah_aktif = PemilahAktif::select('users.id as id_user', 'users.name', 'users.no_member', 'pemilah_aktif.*')->join('users', 'pemilah_aktif.user_id', '=', 'users.id')->where('periode', Carbon::parse($periode)->format('F Y'))->where('publish', '1')->orderBy('ranking', 'ASC')->get();
            } else {
                $pemilah_aktif = PemilahAktif::select('users.id as id_user', 'users.name', 'users.no_member', 'pemilah_aktif.*')->join('users', 'pemilah_aktif.user_id', '=', 'users.id')->where('periode', Carbon::now()->format('F Y'))->where('publish', '1')->orderBy('ranking', 'ASC')->get();
            }
            if ($pemilah_aktif->count() <= 0) {
                DB::rollBack();
                return ResponseFormatter::error([
                    'success' => false,
                    'message' => 'Data tidak ditemukan atau Pemilah Aktif Belum di Publish',
                ], 'Not Found', 404);
            }
            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'API\RekomendasiController.php',
                'action' => 'Mengambil Data Rekapan Rekomendasi',
            ]);
            // End Log
            DB::commit();
            return ResponseFormatter::success(
                $pemilah_aktif,
                'Data Rekomendasi Berhasil Didapatkan'
            );
        } catch (Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'success' => false,
                'message' => $e,
            ], 'Internal Server Error', 500);
        }
    }
}
