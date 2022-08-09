<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Ramsey\Uuid\Uuid;
use App\Models\Config;
use App\Models\LogActivity;
use App\Models\ConfKriteria;
use App\Models\TagihanIuran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables as DataTablesDataTables;

class KonfigurasiController extends Controller
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
        $all_config = Config::all();
        $kriteria = ConfKriteria::all();
        // Log Activity
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'KonfigurasiController.php',
            'action' => 'Halaman Awal Konfigurasi Sistem',
        ]);
        // End Log
        return view('admin.page.konfigurasi.index', compact('all_config', 'kriteria'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'value' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'string', 'in:active,deactive'],
        ]);
        DB::beginTransaction();
        try {
            $conf = Config::where('id', $id)->firstOrFail();
            $conf->value = $request->value;
            $conf->status = $request->status;
            $conf->save();

            if ($conf->key = 'denda') {
                $tagihan = TagihanIuran::where('due_date', '<', date('Y-m-d'))->get();
                if ($tagihan->count() > 0) {
                    if ($request->status == 'active') {
                        $total_denda = $request->value;
                    } else {
                        $total_denda = 0;
                    }
                    foreach ($tagihan as $t) {
                        if ($t->status == 'UNPAID' || $t->status == 'OVERDUE') {
                            $t->status = 'OVERDUE';
                            $t->sub_total_denda = $total_denda;
                            $t->total_tagihan = $t->sub_total + $total_denda;
                            $t->save();
                        }
                    }
                }
            }

            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'KonfigurasiController.php',
                'action' => 'Mengubah Konfigurasi Sistem',
            ]);
            DB::commit();
            return redirect()->back()->with('success', 'Data Berhasil Diubah');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Diubah');
        }
    }
    public function getAll()
    {
        $kriteria = ConfKriteria::orderBy('created_at', 'asc')->get();
        return DataTablesDataTables::of($kriteria)
            ->addIndexColumn()
            ->editColumn('created_at', function ($model) {
                return $model->created_at->diffForHumans();
            })
            ->make(true);
    }
    public function createKriteria()
    {
        // Log Activity
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'KonfigurasiController.php',
            'action' => 'Akses Halaman Tambah Kriteria Penilaian',
        ]);
        // End Log
        return view('admin.page.konfigurasi.kriteria-penilaian.create');
    }

    public function storeKriteria(Request $request)
    {
        $request->validate([
            'nama_kriteria' => 'required|string',
        ]);
        DB::beginTransaction();
        try {
            ConfKriteria::create([
                'id' => Uuid::uuid4(),
                'nama_kriteria' => ucWords(strtolower($request->nama_kriteria)),
            ]);
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'KonfigurasiController.php',
                'action' => 'Menambah Data Kriteria Penilaian',
            ]);
            DB::commit();
            return redirect()->back()->with('success', 'Data Berhasil Ditambahkan');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Ditambahkan');
        }
    }
    public function editKriteria($id)
    {
        $find = ConfKriteria::findOrFail($id);
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'KonfigurasiController.php',
            'action' => 'Akses Halaman Ubah Kriteria Penilaian',
        ]);
        return view('admin.page.konfigurasi.kriteria-penilaian.edit', compact('find'));
    }

    public function updateKriteria(Request $request, $id)
    {
        $request->validate([
            'nama_kriteria' => 'required|string',
        ]);
        DB::beginTransaction();
        try {
            $find = ConfKriteria::findOrFail($id);
            $find->nama_kriteria = ucWords(strtolower($request->nama_kriteria));
            $find->save();
            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'KonfigurasiController.php',
                'action' => 'Mengubah Data Kriteria Penilaian',
            ]);
            // End Log
            DB::commit();
            return redirect()->back()->with('success', 'Data Berhasil Diubah');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Diubah');
        }
    }

    public function destroyKriteria($id)
    {
        DB::beginTransaction();
        try {
            $find = ConfKriteria::findOrFail($id);
            $find->delete();
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'KonfigurasiController.php',
                'action' => 'Menghapus Data Kriteria Penilaian',
            ]);
            DB::commit();
            return redirect()->back()->with('success', 'Data Berhasil Dihapus');
        } catch (Exception $e) {
            DB::rollback();
            dd($e);
            return redirect()->back()->with('error', 'Data Gagal Dihapus');
        }
    }
}
