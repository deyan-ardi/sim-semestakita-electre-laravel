<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Kategori;
use App\Models\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class KategoriController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kategoriSampah = Kategori::all();
        $jumlahKategori = Kategori::count();
        $organik = Kategori::where('jenis_sampah', 'organik')->get()->count();
        $nonorganik = Kategori::where('jenis_sampah', 'nonorganik')->get()->count();
        $tigaT = Kategori::where('jenis_sampah', 'B3')->get()->count();
        $residu = Kategori::where('jenis_sampah', 'residu')->get()->count();
        // Log Activity
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'KategoriController.php',
            'action' => 'Halaman Index Kategori Sampah',
        ]);
        // End Log
        return view('admin.page.kategori.index', compact(['kategoriSampah', 'jumlahKategori', 'organik', 'nonorganik', 'tigaT', 'residu']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Log Activity
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'KategoriController.php',
            'action' => 'Mengakses Form Tambah Kategori Sampah',
        ]);
        // End Log
        return view('admin.page.kategori.tambah');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => ['required', 'unique:kategori', 'string', 'max:255'],
            'harga_beli' => ['required'],
            'total_sampah' => ['required'],
        ]);
        DB::beginTransaction();
        try {
            Kategori::create([
                'nama_kategori' => ucWords(strtolower($request->nama_kategori)),
                'jenis_sampah' => $request->jenis_sampah,
                'harga_beli' => $request->harga_beli,
                'total_sampah' => $request->total_sampah,
                'created_by' => Auth::user()->name,
                'updated_by' => Auth::user()->name,
            ]);
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'KategoriController.php',
                'action' => 'Store Data Kategori Sampah Baru',
            ]);
            DB::commit();
            return redirect()->back()->with('success', 'Data Berhasil Ditambahkan');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Ditambahkan');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $kategoriSampah = Kategori::find($id);
        // Log Activity
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'KategoriController.php',
            'action' => 'Mengakses Halaman Edit Kategori Sampah',
        ]);
        // End Log
        return view('admin.page.kategori.edit', compact(['kategoriSampah']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $kategoriSampah = Kategori::find($id);
        if (ucWords(strtolower($kategoriSampah->nama_kategori)) == ucWords(strtolower($request->nama_kategori))) {
            $valid_nama_kategori =  ['required', 'string', 'max:255'];
        } else {
            $valid_nama_kategori =  ['required', 'unique:kategori', 'string', 'max:255'];
        }
        $request->validate([
            'nama_kategori' => $valid_nama_kategori,
            'harga_beli' => ['required'],
            'total_sampah' => ['required'],
        ]);
        DB::beginTransaction();
        try {
            $kategoriSampah->update([
                'nama_kategori' => ucWords(strtolower($request->nama_kategori)),
                'jenis_sampah' => $request->jenis_sampah,
                'harga_beli' => $request->harga_beli,
                'total_sampah' => $request->total_sampah,
                'updated_by' => Auth::user()->name,
            ]);
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'KategoriController.php',
                'action' => 'Submit Update Data Kategori Sampah Yang Dipilih',
            ]);
            DB::commit();
            return redirect()->back()->with('success', 'Data Berhasil Diubah');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Diubah');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $kategoriSampah = Kategori::find($id);
            if ($kategoriSampah->total_sampah > 0) {
                return redirect()->back()->with('error', 'Kategori Sampah Masih Memiliki Stok Sampah');
            }
            $kategoriSampah->delete();
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'KategoriController.php',
                'action' => 'Hapus Kategori Sampah Yang Dipilih',
            ]);
            DB::commit();
            return redirect()->back()->with('success', 'Data Berhasil Dihapus');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Dihapus');
        }
    }
}
