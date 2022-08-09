<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\LogActivity;
use Illuminate\Http\Request;
use App\Models\SystemVersion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SistemInfoController extends Controller
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
        $sistem_info = SystemVersion::all();
        // Log Activity
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'SystemVersionController.php',
            'action' => 'Halaman Awal Aplikasi Baru',
        ]);
        // End Log
        return view('admin.page.sistem-info.index', compact('sistem_info'));
    }

    public function create()
    {
        // Log Activity
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'SystemVersionController.php',
            'action' => 'Halaman Buat Versi Aplikasi Baru',
        ]);
        // End Log
        return view('admin.page.sistem-info.tambah');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => ['required', 'string'],
            'date' => ['required', 'date'],
            'nama' => ['required', 'string'],
            'konten' => ['required', 'string'],
        ]);
        DB::beginTransaction();
        try {
            SystemVersion::create([
                'kode_versi' => ucwords($request->kode),
                'nama_versi' => ucwords($request->nama),
                'tanggal_rilis' => $request->date,
                'konten' => $request->konten,
            ]);
            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'SystemVersionController.php',
                'action' => 'Store Data Versi Aplikasi Baru',
            ]);
            // End Log

            DB::commit();
            return redirect()->back()->with('success', 'Data Berhasil Ditambahkan');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Ditambahkan');
        }
    }

    public function edit($id)
    {
        $find = SystemVersion::where('id', $id)->firstOrFail();

        // Log Activity
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'SystemVersionController.php',
            'action' => 'Halaman Edit Versi Aplikasi Baru',
        ]);
        // End Log
        return view('admin.page.sistem-info.edit', compact('find'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode' => ['required', 'string'],
            'date' => ['required', 'date'],
            'nama' => ['required', 'string'],
            'konten' => ['required', 'string'],
        ]);
        DB::beginTransaction();
        try {
            $find = SystemVersion::where('id', $id)->firstOrFail();
            $find->kode_versi = ucwords($request->kode);
            $find->nama_versi = ucwords($request->nama);
            $find->tanggal_rilis = $request->date;
            $find->konten = $request->konten;
            $find->save();

            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'SystemVersionController.php',
                'action' => 'Update Data Versi Aplikasi Baru',
            ]);
            // End Log

            DB::commit();
            return redirect()->back()->with('success', 'Data Gagal Diubah');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Diubah');
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $find = SystemVersion::where('id', $id)->firstOrFail();
            $find->delete();
            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'SystemVersionController.php',
                'action' => 'Delete Data Versi Aplikasi Baru',
            ]);
            // End Log
            DB::commit();
            return redirect()->back()->with('success', 'Data Berhasil Dihapus');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Dihapus');
        }
    }

    public function detail_info()
    {
        $all = SystemVersion::orderBy('created_at', 'DESC')->get();

        return view('admin.page.sistem-info.detail', compact('all'));
    }
}
