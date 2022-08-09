<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\User;
use App\Models\Tabungan;
use App\Models\LogActivity;
use App\Models\RekapanSampah;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\RekapanPenarikanTabungan;

class DetailTabunganController extends Controller
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

    public function index(User $user, $status)
    {
        if ($user->role == 4) {
            $penarikan = RekapanPenarikanTabungan::where('user_id', $user->id)->get();
            $tabungan = Tabungan::where('user_id', $user->id)->firstOrfail();
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'DetailTabunganController.php',
                'action' => 'Halaman Index Detail Tabungan',
            ]);
            return view('admin.page.detail-tabungan.detail', ['penarikan' => $penarikan, 'back' => $status, 'user' => $user, 'tabungan' => $tabungan]);
        }
        abort(404);
    }

    public function history(User $user)
    {
        if ($user->role == 4) {
            $rekapan_sampah = RekapanSampah::where('user_id', $user->id)->orderBy('created_at', 'DESC')->get();
            $rekapan_penarikan = RekapanPenarikanTabungan::where('user_id', $user->id)->orderBy('created_at', 'DESC')->get();
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'DaftarTagihanController.php',
                'action' => 'History Detail Tabungan',
            ]);
            return view('admin.page.detail-tabungan.history', ['rekapan_sampah' => $rekapan_sampah, 'rekapan_penarikan' => $rekapan_penarikan, 'user' => $user]);
        }
        abort(404);
    }

    public function print_pdf(User $user)
    {
        DB::beginTransaction();
        try {
            $rekapan_sampah = RekapanSampah::where('user_id', $user->id)->orderBy('created_at', 'DESC')->get();
            $rekapan_penarikan = RekapanPenarikanTabungan::where('user_id', $user->id)->orderBy('created_at', 'DESC')->get();
            $pdf = \PDF::loadView('pdf.cetak-history-tabungan', ['title' => 'History Penarikan Tabungan', 'rekapan_sampah' => $rekapan_sampah, 'rekapan_penarikan' => $rekapan_penarikan, 'user' => $user])->setPaper('a4', 'landscape');
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'DaftarTagihanController.php',
                'action' => 'Print PDF Data',
            ]);
            DB::commit();
            return $pdf->download('History Penarikan Tabungan.pdf');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal Mencetak Data');
        }
    }
}
