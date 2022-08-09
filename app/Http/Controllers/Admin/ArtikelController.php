<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Ramsey\Uuid\Uuid;
use App\Models\Artikel;
use App\Models\LogActivity;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArtikelController extends Controller
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
        $jumlahArtikel = Artikel::count();
        $dataArtikel = Artikel::all();
        // Log Activity
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'ArtikelController.php',
            'action' => 'Mengakses Halaman List Artikel',
        ]);
        // End Log
        return view('admin.page.artikel.index', compact(['dataArtikel', 'jumlahArtikel']));
    }

    public function create()
    {
        // Log Activity
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'ArtikelController.php',
            'action' => 'Mengakses Halaman Buat Artikel Baru',
        ]);
        // End Log
        return view('admin.page.artikel.tambah', );
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => ['required', 'string', 'unique:artikel', 'max:100'],
            'gambar' => ['required', 'mimes:png,jpeg', 'max:2048', 'image'],
            'kategori' => ['required'],
            'konten' => ['required'],
        ]);
        DB::beginTransaction();
        try {
            if ($request->file('gambar')) {
                $imagePath = $request->file('gambar');
                $path = $imagePath->store('artikel', 'public');
            } else {
                $path = null;
            }
            Artikel::create([
                'id' => Uuid::uuid4(),
                'judul' => ucWords($request->judul),
                'slug' => Str::slug($request->judul),
                'gambar' => $path,
                'kategori' => $request->kategori,
                'stok' => $request->stok,
                'harga' => $request->harga,
                'konten' => $request->konten,
                'created_by' => Auth::user()->name,
                'updated_by' => Auth::user()->name,
            ]);
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'ArtikelController.php',
                'action' => 'Menyimpan Artikel Baru',
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
        $artikel = Artikel::findOrFail($id);
        // Log Activity
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'ArtikelController.php',
            'action' => 'Mengakses Halaman Ubah Artikel',
        ]);
        // End Log
        return view('admin.page.artikel.edit', compact(['artikel']));
    }

    public function update(Request $request, $id)
    {
        $artikel = Artikel::find($id);
        if ($artikel->judul == $request->judul) {
            $valid_judul = ['required', 'string', 'max:100'];
        } else {
            $valid_judul = ['required', 'string', 'unique:artikel', 'max:100'];
        }
        $request->validate([
            'judul' => $valid_judul,
            'gambar' => ['mimes:png,jpeg', 'max:2048', 'image'],
            'kategori' => ['required'],
            'konten' => ['required'],
        ]);

        DB::beginTransaction();
        try {
            if (! empty($artikel->gambar)) {
                if ($request->file('gambar')) {
                    Storage::delete('public/' . $artikel->gambar);
                    $gambarPath = $request->file('gambar');
                    $path = $gambarPath->store('artikel', 'public');
                } else {
                    $path = $artikel->gambar;
                }
            } else {
                if ($request->file('gambar')) {
                    $gambarPath = $request->file('gambar');
                    $path = $gambarPath->store('artikel', 'public');
                } else {
                    $path = null;
                }
            }

            $artikel->judul = ucWords($request->judul);
            $artikel->slug = Str::slug($request->judul);
            $artikel->gambar = $path;
            $artikel->kategori = $request->kategori;
            $artikel->stok = $request->stok;
            $artikel->harga = $request->harga;
            $artikel->konten = $request->konten;
            $artikel->updated_by = Auth::user()->name;
            $artikel->save();
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'ArtikelController.php',
                'action' => 'Menyimpan Perubahan Artikel',
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
            $artikel = Artikel::findOrFail($id);
            if (! empty($artikel->gambar)) {
                Storage::delete('public/' . $artikel->gambar);
            }
            $artikel->delete();
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'ArtikelController.php',
                'action' => 'Menghapus Artikel',
            ]);
            DB::commit();
            return redirect()->back()->with('success', 'Data Berhasil Dihapus');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Dihapus');
        }
    }
}
