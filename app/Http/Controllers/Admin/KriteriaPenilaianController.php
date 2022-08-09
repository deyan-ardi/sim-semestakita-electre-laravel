<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\Models\Config;
use App\Models\Kriteria;
use App\Models\LogActivity;
use App\Models\ConfKriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables as DataTablesDataTables;

class KriteriaPenilaianController extends Controller
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

    public function index(Request $request)
    {
        if (! empty($request->bulan) && ! empty($request->tahun)) {
            $request->validate([
                'bulan' => 'required|date_format:F',
                'tahun' => 'required|date_format:Y',
            ]);
        }

        if (! empty($request->bulan) && ! empty($request->tahun)) {
            $periode = $request->bulan . ' ' . $request->tahun;
            $count = Kriteria::where('periode', Carbon::parse($periode)->format('F Y'))->orderBy('urutan', 'asc')->get();
            $total_bobot = Kriteria::where('periode', Carbon::parse($periode)->format('F Y'))->orderBy('urutan', 'asc')->sum('bobot');
        } else {
            $count = Kriteria::where('periode', Carbon::now()->format('F Y'))->orderBy('urutan', 'asc')->get();
            $total_bobot = Kriteria::where('periode', Carbon::now()->format('F Y'))->orderBy('urutan', 'asc')->sum('bobot');
        }
        $all_kriteria = ConfKriteria::orderBy('created_at', 'asc')->get();
        $config = Config::where('key', 'unpublish-time')->first();
        // Log Activity
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'KriteriaController.php',
            'action' => 'Akses Halaman Kriteria Penilaian',
        ]);
        // End Log
        return view('admin.page.kriteria-penilaian.index', compact('count', 'total_bobot', 'all_kriteria', 'config'));
    }

    public function getAll(Request $request)
    {
        if (! empty($request->bulan) && ! empty($request->tahun)) {
            $periode = $request->bulan . ' ' . $request->tahun;
            $kriteria = Kriteria::where('periode', Carbon::parse($periode)->format('F Y'))->orderBy('urutan', 'asc')->get();
        } else {
            $kriteria = Kriteria::where('periode', Carbon::now()->format('F Y'))->orderBy('urutan', 'asc')->get();
        }
        return DataTablesDataTables::of($kriteria)
            ->addIndexColumn()
            ->editColumn('created_at', function ($model) {
                return $model->created_at->diffForHumans();
            })
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_checkbox.*' => 'required',
            'id_checkbox' => 'required|min:1',
            'bulan' => 'required|date_format:F',
            'tahun' => 'required|date_format:Y',
        ]);
        DB::beginTransaction();
        try {
            // Delete First
            $periode =  Carbon::parse($request->bulan . ' ' . $request->tahun)->format('F Y');
            $find = Kriteria::where('periode', $periode)->get();
            if ($find->count() > 0) {
                foreach ($find as $item) {
                    if ($item->publish == 1) {
                        return redirect()->back()->with('error', 'Data Kriteria Sudah Dipublish, Tidak Dapat Menambahkan Data Kriteria Baru');
                    }
                    $item->delete();
                }
            }

            // Insert New
            for ($i = 0; $i < count($request->id_checkbox); $i++) {
                $id_checkbox = $request->id_checkbox[$i];
                $getKriteria = ConfKriteria::find($id_checkbox);
                Kriteria::create([
                    'id' => Uuid::uuid4(),
                    'periode' => $periode,
                    'urutan' => $i + 1,
                    'nama_kriteria' => ucWords(strtolower($getKriteria->nama_kriteria)),
                    'bobot' => 0,
                ]);
            }

            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'KriteriaController.php',
                'action' => 'Menambah Data Kriteria Penilaian',
            ]);
            // End Log
            DB::commit();
            return redirect()->back()->with('success', 'Data Berhasil Ditambahkan');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Ditambahkan');
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'id.*' => 'required',
            'id' => 'required',
            'bobot.*' => 'required',
            'bobot' => 'required|between:0,100',
        ]);
        DB::beginTransaction();
        try {
            // Insert New
            $bobot = 0;
            for ($i = 0; $i < count($request->id); $i++) {
                $bobot = $bobot + $request->bobot[$i];
            }
            if ($bobot > 100) {
                return redirect()->back()->with('error', 'Total Bobot Kriteria Melebihi 100%');
            }

            if ($bobot < 100) {
                return redirect()->back()->with('error', 'Total Bobot Kriteria Kurang Dari 100%');
            }

            for ($i = 0; $i < count($request->id); $i++) {
                $id = $request->id[$i];
                $kriteria = Kriteria::where('id', $id)->first();
                if (! empty($kriteria)) {
                    $kriteria->bobot = $request->bobot[$i];
                    $kriteria->save();
                }
            }
            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'KriteriaController.php',
                'action' => 'Mengubah Data Kriteria Penilaian',
            ]);
            // End Log
            DB::commit();
            return redirect()->back()->with('success', 'Data Berhasil Diubah');
        } catch (Exception $e) {
            DB::rollback();
            dd($e);
            return redirect()->back()->with('error', 'Data Gagal Diubah');
        }
    }

    public function destroyAll(Request $request)
    {
        $request->validate([
            'bulan' => 'required|date_format:F',
            'tahun' => 'required|date_format:Y',
        ]);
        DB::beginTransaction();
        try {
            $periode =  Carbon::parse($request->bulan . ' ' . $request->tahun)->format('F Y');
            $find = Kriteria::where('periode', $periode)->get();
            if ($find->count() > 0) {
                foreach ($find as $item) {
                    if ($item->publish == 1) {
                        return redirect()->back()->with('error', 'Data Kriteria Sudah Dipublish, Tidak Dapat Mengsongkan Data Kriteria');
                    }
                    $item->delete();
                }
            }
            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'KriteriaController.php',
                'action' => 'Mengosongkan Data Kriteria Penilaian',
            ]);
            // End Log
            DB::commit();
            return redirect()->back()->with('success', 'Data Kriteria Penilaian Berhasil Dikosongkan');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Diubah');
        }
    }

    public function publishAll(Request $request)
    {
        $request->validate([
            'bulan' => 'required|date_format:F',
            'tahun' => 'required|date_format:Y',
        ]);
        DB::beginTransaction();
        try {
            $periode =  Carbon::parse($request->bulan . ' ' . $request->tahun)->format('F Y');
            $total_bobot = Kriteria::where('periode', Carbon::parse($periode)->format('F Y'))->orderBy('urutan', 'asc')->sum('bobot');
            if ($total_bobot != 100) {
                return redirect()->back()->with('error', 'Gagal Mempublish, Total Bobot Kriteria Harus 100%');
            }

            $find = Kriteria::where('periode', $periode)->get();
            if ($find->count() > 0) {
                foreach ($find as $item) {
                    if ($item->publish == '1') {
                        return redirect()->back()->with('error', 'Data Kriteria Sudah Dipublish, Tidak Dapat Mempublish Ulang Data Kriteria');
                    }
                    $item->publish = '1';
                    $item->save();
                }
            }
            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'KriteriaController.php',
                'action' => 'Mempublish Data Kriteria Penilaian',
            ]);
            // End Log
            DB::commit();
            return redirect()->back()->with('success', 'Data Kriteria Penilaian Berhasil Dipublish');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Dipublish');
        }
    }

    public function unpublishAll(Request $request)
    {
        $request->validate([
            'bulan' => 'required|date_format:F',
            'tahun' => 'required|date_format:Y',
        ]);
        DB::beginTransaction();
        try {
            $config = Config::where('key', 'unpublish-time')->first();
            $periode =  Carbon::parse($request->bulan . ' ' . $request->tahun)->format('F Y');
            $find = Kriteria::where('periode', $periode)->get();
            if ($find->count() > 0) {
                foreach ($find as $item) {
                    if ($item->publish == '1') {
                        if ($config->status == 'active') {
                            if (\Carbon\Carbon::parse($item->updated_at)->addMinute($config->value)->format('Y-m-d H:i:s') >= \Carbon\Carbon::now()->format('Y-m-d H:i:s')) {
                                $item->publish = '0';
                                $item->save();
                            } else {
                                DB::rollback();
                                return redirect()->back()->with('error', 'Tidak Dapat Melakukan Unpublish');
                            }
                        } else {
                            DB::rollback();
                            abort(404);
                        }
                    }
                }
            }
            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'KriteriaController.php',
                'action' => 'Unpublish Data Kriteria Penilaian',
            ]);
            // End Log
            DB::commit();
            return redirect()->back()->with('success', 'Data Kriteria Penilaian Berhasil Di Unpublish');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Di Unpublish');
        }
    }
}
