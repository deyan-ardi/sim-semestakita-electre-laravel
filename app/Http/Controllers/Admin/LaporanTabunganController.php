<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Carbon\Carbon;
use App\Models\Tabungan;
use App\Models\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\RekapanPenarikanTabungan;
use App\Exports\RekapanPenarikanTabunganExport;

class LaporanTabunganController extends Controller
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
        $penarikan = RekapanPenarikanTabungan::all();
        // Log Activity
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'LaporanTabunganController.php',
            'action' => 'Halaman Awal Laporan Tabungan',
        ]);
        // End Log
        return view('admin.page.laporan-tabungan.index', ['penarikan' => $penarikan]);
    }
    public function search($id)
    {
        $penarikan = RekapanPenarikanTabungan::where('id', $id)->get();
        // Log Activity
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'LaporanTabunganController.php',
            'action' => 'Search Laporan Tabungan',
        ]);
        // End Log
        return view('admin.page.laporan-tabungan.index', ['penarikan' => $penarikan]);
    }
    public function filter(Request $request)
    {
        $request->validate([
            'tanggal_awal' => ['nullable', 'date'],
            'tanggal_akhir' => ['nullable', 'after:tanggal_awal'],
            'status' => ['nullable', 'string'],
        ]);

        if (empty($request->tanggal_awal) || empty($request->tanggal_akhir)) {
            return redirect(route('rekapan-tabungan'));
        }
        $start = Carbon::createFromFormat('Y-m-d', $request->tanggal_awal)->startOfDay()->toDateTimeString();
        $end = Carbon::createFromFormat('Y-m-d', $request->tanggal_akhir)->endOfDay()->toDateTimeString();
        $penarikan = RekapanPenarikanTabungan::where('created_at', '>=', $start)->Where('created_at', '<=', $end)->get();
        // Log Activity
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'LaporanTabunganController.php',
            'action' => 'Filter Laporan Tabungan',
        ]);
        // End Log
        return view('admin.page.laporan-tabungan.index', ['penarikan' => $penarikan]);
    }

    public function export(Request $request)
    {
        DB::beginTransaction();
        try {
            $penarikan = RekapanPenarikanTabungan::query();
            if (! empty($request->tanggal_awal) || ! empty($request->tanggal_akhir)) {
                $start = Carbon::createFromFormat('Y-m-d', $request->tanggal_awal)->startOfDay()->toDateTimeString();
                $end = Carbon::createFromFormat('Y-m-d', $request->tanggal_akhir)->endOfDay()->toDateTimeString();
                $penarikan->where('rekapan_penarikan_tabungan.created_at', '>=', $start)->Where('rekapan_penarikan_tabungan.created_at', '<=', $end);
            }
            $penarikan = $penarikan->select('rekapan_penarikan_tabungan.created_at', 'no_penarikan', 'users.name', 'users.no_member', 'users.no_rekening', 'total_penarikan')->join('users', 'users.id', '=', 'rekapan_penarikan_tabungan.user_id');
            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'LaporanTabunganController.php',
                'action' => 'Export Excel Laporan Tabungan',
            ]);
            // End Log
            DB::commit();
            return Excel::download(new RekapanPenarikanTabunganExport($penarikan), 'export_data_rekapan_penarikan_tabungan.xlsx');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Diekspor');
        }
    }

    public function cetak_by_id(RekapanPenarikanTabungan $penarikan)
    {
        DB::beginTransaction();
        try {
            $tabungan = Tabungan::where('user_id', $penarikan->user_id)->first();
            $pdf = \PDF::loadView('pdf.cetak-rekapan-penarikan', ['title' => 'Penarikan Tabungan', 'rekapan' => $penarikan, 'tabungan' => $tabungan])->setPaper('a4', 'landscape');
            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'target_user' => $penarikan->user->name,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'LaporanTabunganController.php',
                'action' => 'Cetak By Id Laporan Tabungan',
            ]);
            // End Log
            DB::commit();
            return  $pdf->download('Penarikan -' . ($penarikan->no_penarikan) . '.pdf');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Dicetak');
        }
    }
}
