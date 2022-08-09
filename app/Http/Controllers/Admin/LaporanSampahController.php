<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Carbon\Carbon;
use App\Models\LogActivity;
use Illuminate\Http\Request;
use App\Models\RekapanSampah;
use Illuminate\Support\Facades\DB;
use App\Models\DetailRekapanSampah;
use Illuminate\Support\Facades\URL;
use App\Exports\RekapanSampahExport;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class LaporanSampahController extends Controller
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
        $rekapan = RekapanSampah::all();
        // Log Activity
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'LaporanSampahController.php',
            'action' => 'Halaman Awal Laporan Penyetoran Sampah Nasabah',
        ]);
        // End Log
        return view('admin.page.laporan-sampah.index', ['rekapan' => $rekapan]);
    }

    public function detail(RekapanSampah $rekapan)
    {
        $detail_rekapan = DetailRekapanSampah::where('rekapan_sampah_id', $rekapan->id)->get();
        // Log Activity
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'LaporanSampahController.php',
            'action' => 'Halaman Detail Laporan Penyetoran Sampah Nasabah',
        ]);
        // End Log
        return view('admin.page.laporan-sampah.detail', ['rekapan' => $rekapan, 'detail_rekapan' => $detail_rekapan]);
    }

    public function filter(Request $request)
    {
        $request->validate([
            'tanggal_awal' => ['nullable', 'date'],
            'tanggal_akhir' => ['nullable', 'after:tanggal_awal'],
            'status' => ['nullable', 'string'],
        ]);

        if (empty($request->tanggal_awal) || empty($request->tanggal_akhir)) {
            return redirect(route('rekapan-sampah'));
        }
        $start = Carbon::createFromFormat('Y-m-d', $request->tanggal_awal)->startOfDay()->toDateTimeString();
        $end = Carbon::createFromFormat('Y-m-d', $request->tanggal_akhir)->endOfDay()->toDateTimeString();
        $rekapan = RekapanSampah::where('created_at', '>=', $start)->Where('created_at', '<=', $end)->get();
        // Log Activity
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'LaporanSampahController.php',
            'action' => 'Filter Data Laporan Penyetoran Sampah Nasabah',
        ]);
        // End Log
        return view('admin.page.laporan-sampah.index', ['rekapan' => $rekapan]);
    }

    public function search($id)
    {
        $rekapan = RekapanSampah::where('id', '=', $id)->get();
        // Log Activity
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'LaporanSampahController.php',
            'action' => 'Search Data Laporan Penyetoran Sampah Nasabah',
        ]);
        // End Log
        return view('admin.page.laporan-sampah.index', ['rekapan' => $rekapan]);
    }
    public function export(Request $request)
    {
        DB::beginTransaction();
        try {
            $rekapan = RekapanSampah::query();
            if (! empty($request->tanggal_awal) || ! empty($request->tanggal_akhir)) {
                $start = Carbon::createFromFormat('Y-m-d', $request->tanggal_awal)->startOfDay()->toDateTimeString();
                $end = Carbon::createFromFormat('Y-m-d', $request->tanggal_akhir)->endOfDay()->toDateTimeString();
                $rekapan->where('rekapan_sampah.created_at', '>=', $start)->Where('rekapan_sampah.created_at', '<=', $end);
            }
            $rekapan = $rekapan->select('rekapan_sampah.created_at', 'kode_transaksi', 'users.no_member', 'users.name', 'total_sampah', 'total_beli')->join('users', 'users.id', '=', 'rekapan_sampah.user_id');
            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'LaporanSampahController.php',
                'action' => 'Export Excel Laporan Penyetoran Sampah Nasabah',
            ]);
            // End Log
            DB::commit();
            return Excel::download(new RekapanSampahExport($rekapan), 'export_data_rekapan_sampah.xlsx');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Diekspor');
        }
    }

    public function cetak_by_id(RekapanSampah $rekapan)
    {
        DB::beginTransaction();
        try {
            $pdf = \PDF::loadView('pdf.cetak-rekapan-sampah', ['title' => 'Penyetoran Sampah', 'rekapan' => $rekapan])->setPaper('a4', 'landscape');
            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'LaporanSampahController.php',
                'action' => 'Cetak Laporan Penyetoran Sampah Nasabah Per Id',
            ]);
            // End Log
            DB::commit();
            return  $pdf->download('Penyetoran -' . ($rekapan->kode_transaksi) . '.pdf');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Dicetak');
        }
    }

    public function cetak_pdf(RekapanSampah $rekapan)
    {
        DB::beginTransaction();
        try {
            $pdf = \PDF::loadView('pdf.cetak-rekapan-sampah', ['title' => 'Penyetoran Sampah', 'rekapan' => $rekapan])->setPaper('a4', 'landscape');
            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'LaporanSampahController.php',
                'action' => 'Cetak PDF Laporan Penyetoran Sampah Nasabah',
            ]);
            // End Log
            DB::commit();
            return  $pdf->download('Penyetoran -' . ($rekapan->kode_transaksi) . '.pdf');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Dicetak');
        }
    }
}
