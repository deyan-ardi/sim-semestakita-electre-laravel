<?php

namespace App\Http\Controllers\API;

use Exception;
use Carbon\Carbon;
use App\Models\Kategori;
use App\Models\RekapanIuran;
use Illuminate\Http\Request;
use App\Models\RekapanHarian;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use App\Models\DetailRekapanSampah;
use App\Http\Controllers\Controller;
use App\Models\PengangkutanPenilaianHarian;

class StatistikSampahController extends Controller
{
    public function sampahKeseluruhan()
    {
        DB::beginTransaction();
        try {
            $all_sampah = Kategori::all();
            $jml_nonorganik = 0;
            $jml_organik = 0;
            $jml_B3 = 0;
            $jml_residu = 0;
            foreach ($all_sampah as $all) {
                if ($all->jenis_sampah == 'organik') {
                    $jml_organik = $jml_organik + $all->total_sampah;
                } elseif ($all->jenis_sampah == 'nonorganik') {
                    $jml_nonorganik = $jml_nonorganik + $all->total_sampah;
                } elseif ($all->jenis_sampah == 'B3') {
                    $jml_B3 = $jml_B3 + $all->total_sampah;
                } else {
                    $jml_residu = $jml_residu + $all->total_sampah;
                }
            }
            $tot_all_sampah = $jml_nonorganik + $jml_organik + $jml_B3 + $jml_residu;
            $jmlh_akhir_nonorganik = number_format($jml_nonorganik == 0 || $tot_all_sampah == 0 ? 0 : $jml_nonorganik / $tot_all_sampah * 100, 2);
            $jmlh_akhir_organik = number_format($jml_organik == 0 || $tot_all_sampah == 0 ? 0 : $jml_organik / $tot_all_sampah * 100, 2);
            $jmlh_akhir_B3 = number_format($jml_B3 == 0 || $tot_all_sampah == 0 ? 0 : $jml_B3 / $tot_all_sampah * 100, 2);
            $jmlh_akhir_residu = number_format($jml_residu == 0 || $tot_all_sampah == 0 ? 0 : $jml_residu / $tot_all_sampah * 100, 2);
            $penilaian = PengangkutanPenilaianHarian::whereDate('tanggal_angkut_penilaian', Carbon::now()->format('Y-m-d'))->count();
            $all_rekapan_iuran_now = RekapanIuran::whereDate('created_at', Carbon::now()->format('Y-m-d'))->orderBy('created_at', 'DESC')->sum('total_tagihan');
            $data = [
                'total_seluruh_sampah' => $tot_all_sampah,
                'persentase_nonorganik' =>  $jmlh_akhir_nonorganik,
                'persentase_organik' => $jmlh_akhir_organik,
                'persentase_B3' => $jmlh_akhir_B3,
                'persentase_residu' => $jmlh_akhir_residu,
                'total_nonorganik' => $jml_nonorganik,
                'total_organik' => $jml_organik,
                'total_B3' => $jml_B3,
                'total_residu' => $jml_residu,
                'tagihan_hari_ini' => $all_rekapan_iuran_now,
                'angkut_sampah_hari_ini' => $penilaian,
            ];
            DB::commit();
            return ResponseFormatter::success(
                $data,
                'Data Statistik Umum Sampah Keseluruhan, Tagihan Hari Ini, dan Angkut Sampah Hari Ini Didapat'
            );
        } catch (Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'success' => false,
                'message' => $e,
            ], 'Internal Server Error', 500);
        }
    }

    public function filterKategori($kategori = null)
    {
        DB::beginTransaction();
        try {
            if ($kategori == null) {
                return ResponseFormatter::error([
                    'success' => false,
                    'message' => 'Parameter Tidak Lengkap',
                ], 'Authentication Failed', 401);
            }
            $all_sampah_by_kategori = Kategori::where('jenis_sampah', $kategori)->get();
            DB::commit();
            return ResponseFormatter::success(
                $all_sampah_by_kategori,
                'Data Statistik Kategori Sampah ' . ucWords($kategori) . ' Didapat'
            );
        } catch (Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'success' => false,
                'message' => $e,
            ], 'Internal Server Error', 500);
        }
    }

    public function sampahHarian(Request $request)
    {
        DB::beginTransaction();
        try {
            $date =  $request->date != null ? Carbon::parse($request->date) : Carbon::today();
            $all_sampah_nabung = DetailRekapanSampah::whereDate('created_at', '=', $date)->get();
            $all_sampah_keluar = RekapanHarian::with('detail_rekapan_harian')->where('status', 'Keluar')->whereDate('created_at', '=', $date)->get();
            $all_sampah_masuk = RekapanHarian::with('detail_rekapan_harian')->where('status', 'Masuk')->whereDate('created_at', '=', $date)->get();
            $data = [];
            // Statistik nabung sampah harian
            $nabung_organik = 0;
            $nabung_nonorganik = 0;
            $nabung_B3 = 0;
            $nabung_residu = 0;
            foreach ($all_sampah_nabung as $nabung) {
                if ($nabung->jenis_sampah   == 'organik') {
                    $nabung_organik = $nabung_organik + $nabung->jumlah_sampah;
                } elseif ($nabung->jenis_sampah == 'nonorganik') {
                    $nabung_nonorganik = $nabung_nonorganik + $nabung->jumlah_sampah;
                } elseif ($nabung->jenis_sampah == 'B3') {
                    $nabung_B3 = $nabung_B3 + $nabung->jumlah_sampah;
                } elseif ($nabung->jenis_sampah == 'residu') {
                    $nabung_residu = $nabung_residu + $nabung->jumlah_sampah;
                }
            }
            $nabung_push = [
                // 'tanggal' => $date->isoFormat('D MMMM Y'),
                'nabung_sampah' => [
                    'organik' => $nabung_organik,
                    'anorganik' => $nabung_nonorganik,
                    'B3' => $nabung_B3,
                    'residu' => $nabung_residu,
                ],
            ];
            array_push($data, $nabung_push);
            // Statistik all sampah keluar
            $keluar_organik = 0;
            $keluar_nonorganik = 0;
            $keluar_B3 = 0;
            $keluar_residu = 0;
            foreach ($all_sampah_keluar as $keluar) {
                foreach ($keluar->detail_rekapan_harian as $detail_keluar) {
                    if ($detail_keluar->jenis_sampah == 'organik') {
                        $keluar_organik = $keluar_organik + $detail_keluar->jumlah_sampah;
                    } elseif ($detail_keluar->jenis_sampah == 'nonorganik') {
                        $keluar_nonorganik = $keluar_nonorganik + $detail_keluar->jumlah_sampah;
                    } elseif ($detail_keluar->jenis_sampah == 'B3') {
                        $keluar_B3 = $keluar_B3 + $detail_keluar->jumlah_sampah;
                    } elseif ($detail_keluar->jenis_sampah == 'residu') {
                        $keluar_residu = $keluar_residu + $detail_keluar->jumlah_sampah;
                    }
                }
            }
            $keluar_push = [
                // 'tanggal' => $date->isoFormat('D MMMM Y'),
                'sampah_keluar' => [
                    'organik' => $keluar_organik,
                    'anorganik' => $keluar_nonorganik,
                    'B3' => $keluar_B3,
                    'residu' => $keluar_residu,
                ],
            ];
            array_push($data, $keluar_push);

            // Statistik all sampah masuk
            $masuk_organik = 0;
            $masuk_nonorganik = 0;
            $masuk_B3 = 0;
            $masuk_residu = 0;
            foreach ($all_sampah_masuk as $masuk) {
                foreach ($masuk->detail_rekapan_harian as $detail_masuk) {
                    if ($detail_masuk->jenis_sampah == 'organik') {
                        $masuk_organik = $masuk_organik + $detail_masuk->jumlah_sampah;
                    } elseif ($detail_masuk->jenis_sampah == 'nonorganik') {
                        $masuk_nonorganik = $masuk_nonorganik + $detail_masuk->jumlah_sampah;
                    } elseif ($detail_masuk->jenis_sampah == 'B3') {
                        $masuk_B3 = $masuk_B3 + $detail_masuk->jumlah_sampah;
                    } elseif ($detail_masuk->jenis_sampah == 'residu') {
                        $masuk_residu = $masuk_residu + $detail_masuk->jumlah_sampah;
                    }
                }
            }
            $masuk_push = [
                // 'tanggal' => $date->isoFormat('D MMMM Y'),
                'sampah_masuk' => [
                    'organik' => $masuk_organik,
                    'anorganik' => $masuk_nonorganik,
                    'B3' => $masuk_B3,
                    'residu' => $masuk_residu,
                ],
            ];
            array_push($data, $masuk_push);
            DB::commit();
            return ResponseFormatter::success(
                $data,
                'Data Statistik Umum Sampah Tanggal ' . $date->isoFormat('D MMMM Y') . ' Didapat'
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
