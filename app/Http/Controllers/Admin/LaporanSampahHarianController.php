<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\LogActivity;
use Illuminate\Http\Request;
use App\Models\RekapanHarian;
use Illuminate\Support\Facades\DB;
use App\Models\DetailRekapanHarian;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RekapanSampahHarianExport;

class LaporanSampahHarianController extends Controller
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
        $rekapan = RekapanHarian::all();
        // Log Activity
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'LaporanSampahHarianController.php',
            'action' => 'Halaman Awal Laporan Sampah Harian',
        ]);
        // End Log
        return view('admin.page.laporan-sampah-harian.index', ['rekapan' => $rekapan]);
    }

    public function detail(RekapanHarian $rekapan)
    {
        $detail_rekapan = DetailRekapanHarian::where('rekapan_harian_id', $rekapan->id)->get();
        // Log Activity
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'LaporanSampahHarianController.php',
            'action' => 'Detail Laporan Sampah Harian',
        ]);
        // End Log
        return view('admin.page.laporan-sampah-harian.detail', ['rekapan' => $rekapan, 'detail_rekapan' => $detail_rekapan]);
    }

    public function filter(Request $request)
    {
        $request->validate([
            'tanggal_awal' => ['nullable', 'date'],
            'tanggal_akhir' => ['nullable', 'after:tanggal_awal'],
            'status' => ['nullable', 'string'],
        ]);

        $status = strtoupper($request->status);
        if (empty($request->tanggal_awal) || empty($request->tanggal_akhir)) {
            if ($status == 'SEMUA') {
                return redirect(route('rekapan-harian'));
            }
            $rekapan = RekapanHarian::where('status', $status)->get();
        } else {
            if ($status == 'SEMUA') {
                $rekapan = RekapanHarian::where('tanggal', '>=', $request->tanggal_awal)->where('tanggal', '<=', $request->tanggal_akhir)->get();
            } else {
                $rekapan = RekapanHarian::where('tanggal', '>=', $request->tanggal_awal)->where('tanggal', '<=', $request->tanggal_akhir)->where('status', $status)->get();
            }
        }
        // Log Activity
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'LaporanSampahHarianController.php',
            'action' => 'Filter Laporan Sampah Harian',
        ]);
        // End Log
        return view('admin.page.laporan-sampah-harian.index', ['rekapan' => $rekapan]);
    }

    public function export(Request $request)
    {
        DB::beginTransaction();
        try {
            $rekapan = RekapanHarian::query();
            $status = strtoupper($request->status);
            if (empty($request->tanggal_awal) || empty($request->tanggal_akhir)) {
                if ($status == 'MASUK' || $status == 'KELUAR') {
                    $rekapan->where('status', $request->status);
                }
            } else {
                if ($status == 'SEMUA') {
                    $rekapan->where('tanggal', '>=', $request->tanggal_awal)->where('tanggal', '<=', $request->tanggal_akhir);
                } else {
                    $rekapan->where('tanggal', '>=', $request->tanggal_awal)->where('tanggal', '<=', $request->tanggal_akhir)->where('status', $request->status);
                }
            }
            $rekapan = $rekapan->select('tanggal', 'status', 'kode_transaksi', 'created_by', 'total_sampah')->orderBy('created_at', 'DESC');
            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'LaporanSampahHarianController.php',
                'action' => 'Export Excel Laporan Sampah Harian',
            ]);
            // End Log
            DB::commit();
            return Excel::download(new RekapanSampahHarianExport($rekapan), 'export_data_rekapan_sampah_harian.xlsx');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Diekspor');
        }
    }

    public function cetak_by_id(RekapanHarian $rekapan)
    {
        DB::beginTransaction();
        try {
            $pdf = \PDF::loadView('pdf.cetak-rekapan-harian', ['title' => 'Rekapan Harian ' . $rekapan->status, 'rekapan' => $rekapan])->setPaper('a4', 'landscape');
            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'LaporanSampahHarianController.php',
                'action' => 'Cetak Single PDF Laporan Sampah Harian',
            ]);
            // End Log
            DB::commit();
            return  $pdf->download('Rekapan Harian Sampah ' . $rekapan->status . '-' . ($rekapan->kode_transaksi) . '.pdf');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Dicetak');
        }
    }

    public function cetak_pdf(RekapanHarian $rekapan)
    {
        DB::beginTransaction();
        try {
            $pdf = \PDF::loadView('pdf.cetak-rekapan-harian', ['title' => 'Rekapan Harian ' . $rekapan->status, 'rekapan' => $rekapan])->setPaper('a4', 'landscape');
            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'LaporanSampahHarianController.php',
                'action' => 'Cetak PDF Laporan Sampah Harian',
            ]);
            // End Log
            DB::commit();
            return  $pdf->download('Rekapan Harian Sampah ' . $rekapan->status . '-' . ($rekapan->kode_transaksi) . '.pdf');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Dicetak');
        }
    }
}
