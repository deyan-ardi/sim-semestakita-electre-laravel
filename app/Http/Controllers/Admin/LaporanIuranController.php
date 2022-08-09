<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\LogActivity;
use App\Models\RekapanIuran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\RekapanIuranExport;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class LaporanIuranController extends Controller
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
        $rekapan_iuran = RekapanIuran::all();
        // Log Activity
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'LaporanIuranController.php',
            'action' => 'Halaman Awal Laporan Iuran',
        ]);
        // End Log
        return view('admin.page.laporan-iuran.index', ['rekapan_iuran' => $rekapan_iuran]);
    }

    public function filter(Request $request)
    {
        $request->validate([
            'tanggal_awal' => ['nullable', 'date'],
            'tanggal_akhir' => ['nullable', 'after:tanggal_awal'],
            'status' => ['nullable', 'string'],
        ]);
        if (empty($request->tanggal_awal) || empty($request->tanggal_akhir)) {
            return redirect(route('rekapan-iuran'));
        }
        $rekapan_iuran = RekapanIuran::where('tanggal', '>=', $request->tanggal_awal)->Where('tanggal', '<=', $request->tanggal_akhir)->get();
        // Log Activity
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'LaporanIuranController.php',
            'action' => 'Filter Data Laporan iuran',
        ]);
        // End Log
        return view('admin.page.laporan-iuran.index', ['rekapan_iuran' => $rekapan_iuran]);
    }

    public function export(Request $request)
    {
        DB::beginTransaction();
        try {
            $rekapan_iuran = RekapanIuran::query();
            if (! empty($request->tanggal_awal) || ! empty($request->tanggal_akhir)) {
                $rekapan_iuran->where('tanggal', '>=', $request->tanggal_awal)->Where('tanggal', '<=', $request->tanggal_akhir);
            }
            $rekapan_iuran = $rekapan_iuran->select(DB::raw("DATE_FORMAT(rekapan_iuran.created_at, '%d-%b-%Y %H:%i') as formatted_dob"), 'users.name', 'users.no_member', 'rekapan_iuran.no_tagihan', 'rekapan_iuran.no_pembayaran', 'rekapan_iuran.deskripsi', 'rekapan_iuran.sub_total', 'rekapan_iuran.sub_total_denda', 'rekapan_iuran.status_denda', 'rekapan_iuran.total_tagihan')->join('users', 'users.id', '=', 'rekapan_iuran.user_id');
            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'LaporanIuranController.php',
                'action' => 'Export To Excel Laporan Iuran',
            ]);
            // End Log
            DB::commit();
            return Excel::download(new RekapanIuranExport($rekapan_iuran), 'export_data_rekapan_iuran.xlsx');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Diekspor');
        }
    }

    public function cetak_by_id(RekapanIuran $rekapan)
    {
        DB::beginTransaction();
        try {
            $pdf = \PDF::loadView('pdf.cetak-rekapan-iuran', ['title' => 'Pembayaran Tagihan Iuran', 'rekapan' => $rekapan])->setPaper('a4', 'landscape');
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'target_user' => $rekapan->user->name,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'LaporanIuranController.php',
                'action' => 'Cetak Single Data Laporan Iuran',
            ]);
            DB::commit();
            return  $pdf->download('Bukti Pembayaran Iuran.pdf');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Dicetak');
        }
    }
}
