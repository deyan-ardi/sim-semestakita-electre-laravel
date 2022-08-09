<?php

namespace App\Http\Controllers\Admin;

use App\Models\LogActivity;
use Illuminate\Http\Request;
use App\Models\PengaduanUser;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PengaduanUserController extends Controller
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
        $pengaduan = PengaduanUser::all();
        // Log Activity
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'PengaduanController.php',
            'action' => 'Halaman Awal Pengaduan',
        ]);
        // End Log
        return view('admin.page.pengaduan.index', ['notifikasi' => $pengaduan]);
    }

    public function detail($id)
    {
        $find = PengaduanUser::with('user')->where('id', $id)->firstOrFail();
        // Log Activity
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'PengaduanController.php',
            'action' => 'Detail Pengaduan',
        ]);
        // End Log
        return view('admin.page.pengaduan.detail', ['find' => $find]);
    }
    public function filter(Request $request)
    {
        $request->validate([
            'tanggal_awal' => ['required', 'date', 'date_format:Y-m-d'],
            'tanggal_akhir' => ['required', 'after:tanggal_awal', 'date', 'date_format:Y-m-d'],
        ]);

        $pengaduan = PengaduanUser::where('updated_at', '>=', $request->tanggal_awal . ' 00:00:00')->where('updated_at', '<=', $request->tanggal_akhir . ' 23:59:00')->get();
        // Log Activity
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'PengaduanController.php',
            'action' => 'Filter Pengaduan',
        ]);
        // End Log
        return view('admin.page.pengaduan.index', ['notifikasi' => $pengaduan]);
    }
}
