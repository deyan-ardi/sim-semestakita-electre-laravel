<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\User;
use App\Models\LogActivity;
use Illuminate\Http\Request;
use App\Models\PembayaranRutin;
use Ramsey\Uuid\Nonstandard\Uuid;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class IuranController extends Controller
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
        $pembayaranRutin = PembayaranRutin::all();
        $jumlahIuranRutin = PembayaranRutin::count();
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'IuranController.php',
            'action' => 'Halaman Index Iuran',
        ]);
        return view('admin.page.iuran.index', compact(['pembayaranRutin', 'jumlahIuranRutin']));
    }

    public function create()
    {
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'IuranController.php',
            'action' => 'Mengakses Halaman Form Tambah Iuran',
        ]);
        return view('admin.page.iuran.tambah');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pembayaran' => ['required', 'unique:pembayaran_rutin', 'string', 'max:255'],
            'deskripsi' => ['required'],
            'total_biaya' => ['required'],
            'tgl_generate' => ['required'],
            'durasi_pembayaran' => ['required'],
        ]);
        DB::beginTransaction();
        try {
            PembayaranRutin::create([
                'id' => Uuid::uuid4(),
                'nama_pembayaran' => ucWords($request->nama_pembayaran),
                'deskripsi' => ucWords($request->deskripsi),
                'total_biaya' => $request->total_biaya,
                'tgl_generate' => $request->tgl_generate,
                'durasi_pembayaran' => $request->durasi_pembayaran,
                'created_by' => Auth::user()->name,
                'updated_by' => Auth::user()->name,
            ]);
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'IuranController.php',
                'action' => 'Store Data Iuran Baru',
            ]);
            DB::commit();
            return redirect()->back()->with('success', 'Data Berhasil Ditambahkan');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Ditambahkan');
        }
    }

    public function edit($id)
    {
        $pembayaranRutin = PembayaranRutin::find($id);
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'IuranController.php',
            'action' => 'Mengakses Form Edit Iuran',
        ]);
        return view('admin.page.iuran.edit', compact(['pembayaranRutin']));
    }

    public function update(Request $request, $id)
    {
        $pembayaranRutin = PembayaranRutin::find($id);
        if ($pembayaranRutin->nama_pembayaran == $request->nama_pembayaran) {
            $valid_nama_pembayaran =  ['required', 'string', 'max:255'];
        } else {
            $valid_nama_pembayaran =  ['required', 'unique:pembayaran_rutin', 'string', 'max:255'];
        }

        $request->validate([
            'nama_pembayaran' => $valid_nama_pembayaran,
            'deskripsi' => ['required'],
            'total_biaya' => ['required'],
            'tgl_generate' => ['required'],
            'durasi_pembayaran' => ['required'],
        ]);
        DB::beginTransaction();
        try {
            $pembayaranRutin->update([
                'nama_pembayaran' => ucWords($request->nama_pembayaran),
                'deskripsi' => ucWords($request->deskripsi),
                'total_biaya' => $request->total_biaya,
                'tgl_generate' => $request->tgl_generate,
                'durasi_pembayaran' => $request->durasi_pembayaran,
                'updated_by' => Auth::user()->name,
            ]);
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'IuranController.php',
                'action' => 'Update Data Iuran Yang Dipilih',
            ]);
            DB::commit();
            return redirect()->back()->with('success', 'Data Berhasil Diubah');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Diubah');
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $pembayaranRutin = PembayaranRutin::where('id', $id)->firstOrFail();
            $user = User::where('pembayaran_rutin_id', $id)->get()->count();
            if ($user > 0) {
                return redirect()->back()->with('error', 'Tidak Bisa Menghapus Tagihan Yang Aktif Dibayar Nasabah/Pelanggan');
            }
            $pembayaranRutin->delete();
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'IuranController.php',
                'action' => 'Menghapus Data Iuran',
            ]);
            DB::commit();
            return redirect()->back()->with('success', 'Data Berhasil Dihapus');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Dihapus');
        }
    }
}
