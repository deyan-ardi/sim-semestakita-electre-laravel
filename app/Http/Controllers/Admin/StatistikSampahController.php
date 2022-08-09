<?php

namespace App\Http\Controllers\Admin;

use App\Models\Kategori;
use App\Models\LogActivity;
use App\Models\RekapanHarian;
use App\Models\DetailRekapanSampah;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class StatistikSampahController extends Controller
{
    public function __construct()
    {
        // Delete All Tmp When Go To This Controller
        $this->middleware(function ($request, $next) {
            $this->removeSessionTmpAll();
            $this->removeSessionChangeProfil();
            return $next($request);
        });
    }

    public function index()
    {
        $all_sampah = Kategori::all();
        $all_sampah_organik = Kategori::where('jenis_sampah', 'organik')->get();
        $all_sampah_nonorganik = Kategori::where('jenis_sampah', 'nonorganik')->get();
        $all_sampah_B3 = Kategori::where('jenis_sampah', 'B3')->get();
        $all_sampah_residu = Kategori::where('jenis_sampah', 'residu')->get();

        // Statistik organik
        $organik_name = [];
        $organik_jml = [];
        foreach ($all_sampah_organik as $organik) {
            array_push($organik_name, '"' . $organik->nama_kategori . '"');
            array_push($organik_jml, $organik->total_sampah);
        }
        $organik_name = '[' . implode(',', $organik_name) . ']';
        $organik_jml = implode(',', $organik_jml);

        // Statistik nonorganik
        $nonorganik_name = [];
        $nonorganik_jml = [];
        foreach ($all_sampah_nonorganik as $nonorganik) {
            array_push($nonorganik_name, '"' . $nonorganik->nama_kategori . '"');
            array_push($nonorganik_jml, $nonorganik->total_sampah);
        }
        $nonorganik_name = '[' . implode(',', $nonorganik_name) . ']';
        $nonorganik_jml = implode(',', $nonorganik_jml);

        // Statistik B3
        $B3_name = [];
        $B3_jml = [];
        foreach ($all_sampah_B3 as $B3) {
            array_push($B3_name, '"' . $B3->nama_kategori . '"');
            array_push($B3_jml, $B3->total_sampah);
        }
        $B3_name = '[' . implode(',', $B3_name) . ']';
        $B3_jml = implode(',', $B3_jml);

        // Statistik Residu
        $residu_name = [];
        $residu_jml = [];
        foreach ($all_sampah_residu as $residu) {
            array_push($residu_name, '"' . $residu->nama_kategori . '"');
            array_push($residu_jml, $residu->total_sampah);
        }
        $residu_name = '[' . implode(',', $residu_name) . ']';
        $residu_jml = implode(',', $residu_jml);

        // Log Activity
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'StatistikController.php',
            'action' => 'Halaman Statistik Keseluruhan Sampah',
        ]);
        // End Log
        return view('admin.page.statistik.index', ['all_sampah' => $all_sampah, 'organik_name' => $organik_name, 'organik_jml' => $organik_jml, 'nonorganik_name' => $nonorganik_name, 'nonorganik_jml' => $nonorganik_jml, 'B3_name' => $B3_name, 'B3_jml' => $B3_jml, 'residu_name' => $residu_name, 'residu_jml' => $residu_jml]);
    }

    public function harian()
    {
        $all_sampah_nabung = DetailRekapanSampah::whereDate('created_at', '=', date('Y-m-d'))->get();
        $all_sampah_keluar = RekapanHarian::with('detail_rekapan_harian')->where('status', 'Keluar')->whereDate('created_at', '=', date('Y-m-d'))->get();
        $all_sampah_masuk = RekapanHarian::with('detail_rekapan_harian')->where('status', 'Masuk')->whereDate('created_at', '=', date('Y-m-d'))->get();
        // Statistik nabung sampah harian
        $nabung_jml = [0, 0, 0, 0];
        foreach ($all_sampah_nabung as $nabung) {
            if ($nabung->jenis_sampah == 'organik') {
                $nabung_jml[0] = $nabung_jml[0] + $nabung->jumlah_sampah;
            } elseif ($nabung->jenis_sampah == 'nonorganik') {
                $nabung_jml[1] = $nabung_jml[1] + $nabung->jumlah_sampah;
            } elseif ($nabung->jenis_sampah == 'B3') {
                $nabung_jml[2] = $nabung_jml[2] + $nabung->jumlah_sampah;
            } elseif ($nabung->jenis_sampah == 'residu') {
                $nabung_jml[3] = $nabung_jml[3] + $nabung->jumlah_sampah;
            }
        }
        $nabung_jml = implode(',', $nabung_jml);
        // Statistik all sampah keluar
        $keluar_jml = [0, 0, 0, 0];
        foreach ($all_sampah_keluar as $keluar) {
            foreach ($keluar->detail_rekapan_harian as $detail_keluar) {
                if ($detail_keluar->jenis_sampah == 'organik') {
                    $keluar_jml[0] = $keluar_jml[0] + $detail_keluar->jumlah_sampah;
                } elseif ($detail_keluar->jenis_sampah == 'nonorganik') {
                    $keluar_jml[1] = $keluar_jml[1] + $detail_keluar->jumlah_sampah;
                } elseif ($detail_keluar->jenis_sampah == 'B3') {
                    $keluar_jml[2] = $keluar_jml[2] + $detail_keluar->jumlah_sampah;
                } elseif ($detail_keluar->jenis_sampah == 'residu') {
                    $keluar_jml[3] = $keluar_jml[3] + $detail_keluar->jumlah_sampah;
                }
            }
        }
        $keluar_jml = implode(',', $keluar_jml);

        // Statistik all sampah masuk
        $masuk_jml = [0, 0, 0, 0];
        foreach ($all_sampah_masuk as $masuk) {
            foreach ($masuk->detail_rekapan_harian as $detail_masuk) {
                if ($detail_masuk->jenis_sampah == 'organik') {
                    $masuk_jml[0] = $masuk_jml[0] + $detail_masuk->jumlah_sampah;
                } elseif ($detail_masuk->jenis_sampah == 'nonorganik') {
                    $masuk_jml[1] = $masuk_jml[1] + $detail_masuk->jumlah_sampah;
                } elseif ($detail_masuk->jenis_sampah == 'B3') {
                    $masuk_jml[2] = $masuk_jml[2] + $detail_masuk->jumlah_sampah;
                } elseif ($detail_masuk->jenis_sampah == 'residu') {
                    $masuk_jml[3] = $masuk_jml[3] + $detail_masuk->jumlah_sampah;
                }
            }
        }
        $masuk_jml = implode(',', $masuk_jml);
        // Log Activity
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'StatistikController.php',
            'action' => 'Halaman Statistik Harian Sampah',
        ]);
        // End Log
        return view('admin.page.statistik.harian', ['all_nabung' => $all_sampah_nabung, 'all_keluar' => $all_sampah_keluar, 'all_masuk' => $all_sampah_masuk, 'jml_nabung' => $nabung_jml, 'jml_keluar' => $keluar_jml, 'jml_masuk' => $masuk_jml]);
    }
}
