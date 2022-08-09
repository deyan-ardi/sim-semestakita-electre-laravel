<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tabungan;
use App\Models\Pengajuan;
use App\Models\LogActivity;
use App\Models\RekapanIuran;
use App\Models\TagihanIuran;
use App\Models\RekapanHarian;
use App\Models\RekapanSampah;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\RekapanPenarikanTabungan;

class HomeController extends Controller
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
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $penjemputan = Pengajuan::all();
        $tabungan = Tabungan::all();
        $iuran = RekapanIuran::all();
        $rekap_harian = RekapanHarian::where('status', 'Keluar')->get();
        $tagihan = TagihanIuran::all();
        $all_penarikan = RekapanPenarikanTabungan::all();
        $sampah_harian = RekapanHarian::all();
        $all_tunggakan = TagihanIuran::where('status', 'OVERDUE')->orWhere('status', 'UNPAID')->where(DB::raw('YEAR(updated_at)'), \Carbon\Carbon::now()->format('Y'))->get();
        $all_pelunasan = TagihanIuran::where('status', 'PAID')->where(DB::raw('YEAR(updated_at)'), \Carbon\Carbon::now()->format('Y'))->get();
        $all_penyetoran = RekapanSampah::where(DB::raw('YEAR(updated_at)'), \Carbon\Carbon::now()->format('Y'))->get();
        // Arr tunggakan
        $arr_tunggakan = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        foreach ($all_tunggakan as $t) {
            if (\Carbon\Carbon::parse($t->tanggal)->format('m') == '01') {
                $arr_tunggakan[0]++;
            } elseif (\Carbon\Carbon::parse($t->tanggal)->format('m') == '02') {
                $arr_tunggakan[1]++;
            } elseif (\Carbon\Carbon::parse($t->tanggal)->format('m') == '03') {
                $arr_tunggakan[2]++;
            } elseif (\Carbon\Carbon::parse($t->tanggal)->format('m') == '04') {
                $arr_tunggakan[3]++;
            } elseif (\Carbon\Carbon::parse($t->tanggal)->format('m') == '05') {
                $arr_tunggakan[4]++;
            } elseif (\Carbon\Carbon::parse($t->tanggal)->format('m') == '06') {
                $arr_tunggakan[5]++;
            } elseif (\Carbon\Carbon::parse($t->tanggal)->format('m') == '07') {
                $arr_tunggakan[6]++;
            } elseif (\Carbon\Carbon::parse($t->tanggal)->format('m') == '08') {
                $arr_tunggakan[7]++;
            } elseif (\Carbon\Carbon::parse($t->tanggal)->format('m') == '09') {
                $arr_tunggakan[8]++;
            } elseif (\Carbon\Carbon::parse($t->tanggal)->format('m') == '10') {
                $arr_tunggakan[9]++;
            } elseif (\Carbon\Carbon::parse($t->tanggal)->format('m') == '11') {
                $arr_tunggakan[10]++;
            } else {
                $arr_tunggakan[11]++;
            }
        }
        $arr_tunggakan = implode(',', $arr_tunggakan);

        // Arr pelunasan
        $arr_pelunasan = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        foreach ($all_pelunasan as $t) {
            if (\Carbon\Carbon::parse($t->updated_at)->format('m') == '01') {
                $arr_pelunasan[0]++;
            } elseif (\Carbon\Carbon::parse($t->updated_at)->format('m') == '02') {
                $arr_pelunasan[1]++;
            } elseif (\Carbon\Carbon::parse($t->updated_at)->format('m') == '03') {
                $arr_pelunasan[2]++;
            } elseif (\Carbon\Carbon::parse($t->updated_at)->format('m') == '04') {
                $arr_pelunasan[3]++;
            } elseif (\Carbon\Carbon::parse($t->updated_at)->format('m') == '05') {
                $arr_pelunasan[4]++;
            } elseif (\Carbon\Carbon::parse($t->updated_at)->format('m') == '06') {
                $arr_pelunasan[5]++;
            } elseif (\Carbon\Carbon::parse($t->updated_at)->format('m') == '07') {
                $arr_pelunasan[6]++;
            } elseif (\Carbon\Carbon::parse($t->updated_at)->format('m') == '08') {
                $arr_pelunasan[7]++;
            } elseif (\Carbon\Carbon::parse($t->updated_at)->format('m') == '09') {
                $arr_pelunasan[8]++;
            } elseif (\Carbon\Carbon::parse($t->updated_at)->format('m') == '10') {
                $arr_pelunasan[9]++;
            } elseif (\Carbon\Carbon::parse($t->updated_at)->format('m') == '11') {
                $arr_pelunasan[10]++;
            } else {
                $arr_pelunasan[11]++;
            }
        }
        $arr_pelunasan = implode(',', $arr_pelunasan);

        // Arr penyetoran
        $arr_penyetoran = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        foreach ($all_penyetoran as $t) {
            if (\Carbon\Carbon::parse($t->updated_at)->format('m') == '01') {
                $arr_penyetoran[0]++;
            } elseif (\Carbon\Carbon::parse($t->updated_at)->format('m') == '02') {
                $arr_penyetoran[1]++;
            } elseif (\Carbon\Carbon::parse($t->updated_at)->format('m') == '03') {
                $arr_penyetoran[2]++;
            } elseif (\Carbon\Carbon::parse($t->updated_at)->format('m') == '04') {
                $arr_penyetoran[3]++;
            } elseif (\Carbon\Carbon::parse($t->updated_at)->format('m') == '05') {
                $arr_penyetoran[4]++;
            } elseif (\Carbon\Carbon::parse($t->updated_at)->format('m') == '06') {
                $arr_penyetoran[5]++;
            } elseif (\Carbon\Carbon::parse($t->updated_at)->format('m') == '07') {
                $arr_penyetoran[6]++;
            } elseif (\Carbon\Carbon::parse($t->updated_at)->format('m') == '08') {
                $arr_penyetoran[7]++;
            } elseif (\Carbon\Carbon::parse($t->updated_at)->format('m') == '09') {
                $arr_penyetoran[8]++;
            } elseif (\Carbon\Carbon::parse($t->updated_at)->format('m') == '10') {
                $arr_penyetoran[9]++;
            } elseif (\Carbon\Carbon::parse($t->updated_at)->format('m') == '11') {
                $arr_penyetoran[10]++;
            } else {
                $arr_penyetoran[11]++;
            }
        }
        $arr_penyetoran = implode(',', $arr_penyetoran);

        // Arr penarikan
        $arr_penarikan = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        foreach ($all_penarikan as $t) {
            if (\Carbon\Carbon::parse($t->updated_at)->format('m') == '01') {
                $arr_penarikan[0] = $arr_penarikan[0] + $t->total_penarikan;
            } elseif (\Carbon\Carbon::parse($t->updated_at)->format('m') == '02') {
                $arr_penarikan[1] = $arr_penarikan[1] + $t->total_penarikan;
            } elseif (\Carbon\Carbon::parse($t->updated_at)->format('m') == '03') {
                $arr_penarikan[2] = $arr_penarikan[2] + $t->total_penarikan;
            } elseif (\Carbon\Carbon::parse($t->updated_at)->format('m') == '04') {
                $arr_penarikan[3] = $arr_penarikan[3] + $t->total_penarikan;
            } elseif (\Carbon\Carbon::parse($t->updated_at)->format('m') == '05') {
                $arr_penarikan[4] = $arr_penarikan[4] + $t->total_penarikan;
            } elseif (\Carbon\Carbon::parse($t->updated_at)->format('m') == '06') {
                $arr_penarikan[5] = $arr_penarikan[5] + $t->total_penarikan;
            } elseif (\Carbon\Carbon::parse($t->updated_at)->format('m') == '07') {
                $arr_penarikan[6] = $arr_penarikan[6] + $t->total_penarikan;
            } elseif (\Carbon\Carbon::parse($t->updated_at)->format('m') == '08') {
                $arr_penarikan[7] = $arr_penarikan[7] + $t->total_penarikan;
            } elseif (\Carbon\Carbon::parse($t->updated_at)->format('m') == '09') {
                $arr_penarikan[8] = $arr_penarikan[8] + $t->total_penarikan;
            } elseif (\Carbon\Carbon::parse($t->updated_at)->format('m') == '10') {
                $arr_penarikan[9] = $arr_penarikan[9] + $t->total_penarikan;
            } elseif (\Carbon\Carbon::parse($t->updated_at)->format('m') == '11') {
                $arr_penarikan[10] = $arr_penarikan[10] + $t->total_penarikan;
            } else {
                $arr_penarikan[11] = $arr_penarikan[11] + $t->total_penarikan;
            }
        }
        $arr_penarikan = implode(',', $arr_penarikan);

        // Log Activity
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'HomeController.php',
            'action' => 'Mengakses Halaman Dashboard',
        ]);
        // End Log

        return view('admin.page.dashboard.index', ['rekap_harian' => $rekap_harian, 'penjemputan' => $penjemputan, 'tabungan' => $tabungan, 'iuran' => $iuran, 'tagihan' => $tagihan, 'arr_tunggakan' => $arr_tunggakan, 'arr_pelunasan' => $arr_pelunasan, 'arr_penyetoran' => $arr_penyetoran, 'harian' => $sampah_harian, 'penarikan' => $all_penarikan, 'arr_penarikan' => $arr_penarikan]);
    }
}
