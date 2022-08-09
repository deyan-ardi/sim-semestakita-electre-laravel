<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use App\Models\Config;
use App\Models\Kriteria;
use App\Models\LogActivity;
use App\Models\PemilahAktif;
use Illuminate\Http\Request;
use App\Models\RekapanPenilaian;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\DetailRekapanPenilaian;
use App\Models\PengangkutanPenilaianHarian;
use App\Models\DetailPengangkutanPenilaianHarian;
use Yajra\DataTables\DataTables as DataTablesDataTables;

class PengangkutanPenilaianController extends Controller
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
        $request->validate([
            'tanggal_awal' => ['nullable', 'date', 'date_format:Y-m-d'],
            'tanggal_akhir' => ['nullable', 'date', 'date_format:Y-m-d'],
        ]);
        if ($request->tanggal_awal && $request->tanggal_akhir) {
            if ($request->status == 'all') {
                $penilaian = PengangkutanPenilaianHarian::where('tanggal_angkut_penilaian', '>=', $request->tanggal_awal . ' 00:00:00')->where('tanggal_angkut_penilaian', '<=', $request->tanggal_akhir . ' 23:59:00')->count();
            } else {
                $penilaian = PengangkutanPenilaianHarian::where('tanggal_angkut_penilaian', '>=', $request->tanggal_awal . ' 00:00:00')->where('tanggal_angkut_penilaian', '<=', $request->tanggal_akhir . ' 23:59:00')->count();
            }
        } else {
            $penilaian = PengangkutanPenilaianHarian::whereDate('tanggal_angkut_penilaian', Carbon::now()->format('Y-m-d'))->count();
        }
        // Log Activity
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'PengangkutanPenilaianController.php',
            'action' => 'Akses Halaman Pengangkutan Penilaian',
        ]);
        // End Log
        return view('admin.page.pengangkutan-penilaian.index', compact('penilaian'));
    }

    public function getAll(Request $request)
    {
        if ($request->tanggal_awal && $request->tanggal_akhir) {
            if ($request->status == 'all') {
                $penilaian = PengangkutanPenilaianHarian::where('tanggal_angkut_penilaian', '>=', $request->tanggal_awal . ' 00:00:00')->where('tanggal_angkut_penilaian', '<=', $request->tanggal_akhir . ' 23:59:00')->get();
            } else {
                $penilaian = PengangkutanPenilaianHarian::where('tanggal_angkut_penilaian', '>=', $request->tanggal_awal . ' 00:00:00')->where('tanggal_angkut_penilaian', '<=', $request->tanggal_akhir . ' 23:59:00')->get();
            }
        } else {
            $penilaian = PengangkutanPenilaianHarian::whereDate('tanggal_angkut_penilaian', Carbon::now()->format('Y-m-d'))->get();
        }
        return DataTablesDataTables::of($penilaian)
            ->addIndexColumn()
            ->editColumn('user', function ($model) {
                return $model->user->name;
            })
            ->editColumn('pegawai', function ($model) {
                return $model->pegawai->name;
            })
            ->editColumn('tanggal_angkut_penilaian', function ($model) {
                return Carbon::parse($model->tanggal_angkut_penilaian)->format('d F Y H:i:s');
            })
            ->editColumn('action', function ($model) {
                $pemilah_aktif = PemilahAktif::where(
                    'periode',
                    Carbon::parse($model->tanggal_angkut_penilaian)->format('F Y')
                )->where('publish', '1')->get();

                if ($pemilah_aktif->count() > 0) {
                    $status = 'disabled';
                } else {
                    $status = 'enabled';
                }
                return view('admin.page.pengangkutan-penilaian._action', compact('model', 'status'));
            })
            ->make(true);
    }

    public function detail(Request $request)
    {
        $find = PengangkutanPenilaianHarian::where('id', $request->id)->first();
        if (!empty($find)) {
            $detail = DetailPengangkutanPenilaianHarian::select('detail_pengangkutan_penilaian_harian.*')->join('kriteria', 'kriteria.id', '=', 'detail_pengangkutan_penilaian_harian.kriteria_id')->where('pengangkutan_penilaian_harian_id', $find->id)->where('kriteria.urutan', '!=', null)->orderBy('kriteria.urutan', 'asc')->get();
        } else {
            $detail = false;
            $find = false;
        }
        return view('admin.page.pengangkutan-penilaian._detail', compact('find', 'detail'));
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $find = PengangkutanPenilaianHarian::findOrFail($id);
            $find_user = User::where('id', $find->user_id)->firstOrFail();
            $bulan = Carbon::parse($find->tanggal_angkut_penilaian)->format('m');
            $tahun = Carbon::parse($find->tanggal_angkut_penilaian)->format('Y');
            $periode = Carbon::parse($find->tanggal_angkut_penilaian)->format('F Y');
            $find->delete(); //sementara ini dlu

            $find_rekapan = RekapanPenilaian::where('user_id', $find_user->id)->where('periode', $periode)->first();
            $count_penilaian_bulanan = PengangkutanPenilaianHarian::where('user_id', $find_user->id)->whereMonth('tanggal_angkut_penilaian', $bulan)->whereYear('tanggal_angkut_penilaian', $tahun)->count();
            $pemilah_aktif = PemilahAktif::where(
                'periode',
                Carbon::parse($periode)->format('F Y')
            )->where('publish', '1')->get();

            if ($pemilah_aktif->count() > 0) {
                DB::rollback();
                return redirect()->back()->with('error', 'Data Pemilah Aktif Sudah Di Publish, Tidak Dapat Dihapus');
            }

            if (!empty($find_rekapan)) {
                DetailRekapanPenilaian::where('rekapan_penilaian_id', $find_rekapan->id)->delete();
                // Rekapan
                $find_rekapan->jumlah_penilaian = $count_penilaian_bulanan;
                $find_rekapan->save();

                // Detail Kriteria
                $kriteria = Kriteria::where('periode', $periode)->where('publish', '1')->orderBy('urutan', 'asc')->get();
                if (empty($kriteria) || $kriteria->count() <= 0) {
                    DB::rollBack();
                    return redirect()->back()->with('error', "Kriteria Untuk Bulan $periode Belum Disetel, Silahkan Atur Terlebih Dahulu");
                }
                foreach ($kriteria as $item) {
                    $detail_pengangkutan = DetailPengangkutanPenilaianHarian::join('pengangkutan_penilaian_harian', 'pengangkutan_penilaian_harian.id', '=', 'detail_pengangkutan_penilaian_harian.pengangkutan_penilaian_harian_id')->where('kriteria_id', $item->id)->where('nilai_kriteria', 'iya')->where('pengangkutan_penilaian_harian.user_id', $find_user->id)->whereMonth('pengangkutan_penilaian_harian.tanggal_angkut_penilaian', $bulan)->whereYear('tanggal_angkut_penilaian', $tahun)->count();

                    DetailRekapanPenilaian::create([
                        'id' => Uuid::uuid4(),
                        'rekapan_penilaian_id' => $find_rekapan->id,
                        'kriteria_id' => $item->id,
                        'total_nilai' => $detail_pengangkutan,
                    ]);
                }
            } else {
                $rekapan_penilaian = RekapanPenilaian::create([
                    'id' => Uuid::uuid4(),
                    'user_id' => $find_user->id,
                    'periode' => $periode,
                    'jumlah_penilaian' => $count_penilaian_bulanan,
                ]);

                // Kriteria
                $kriteria = Kriteria::where('periode', $periode)->where('publish', '1')->orderBy('urutan', 'asc')->get();
                if (empty($kriteria) || $kriteria->count() <= 0) {
                    DB::rollBack();
                    return redirect()->back()->with('error', "Kriteria Untuk Bulan $periode Belum Disetel, Silahkan Atur Terlebih Dahulu");
                }
                foreach ($kriteria as $item) {
                    $detail_pengangkutan = DetailPengangkutanPenilaianHarian::join('pengangkutan_penilaian_harian', 'pengangkutan_penilaian_harian.id', '=', 'detail_pengangkutan_penilaian_harian.pengangkutan_penilaian_harian_id')->where('kriteria_id', $item->id)->where('nilai_kriteria', 'iya')->where('pengangkutan_penilaian_harian.user_id', $find_user->id)->whereMonth('pengangkutan_penilaian_harian.tanggal_angkut_penilaian', $bulan)->whereYear('tanggal_angkut_penilaian', $tahun)->count();

                    DetailRekapanPenilaian::create([
                        'id' => Uuid::uuid4(),
                        'rekapan_penilaian_id' => $rekapan_penilaian->id,
                        'kriteria_id' => $item->id,
                        'total_nilai' => $detail_pengangkutan,
                    ]);
                }
            }
            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'target_user' => $find_user->name,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'PengangkutanPenilaianController.php',
                'action' => 'Menghapus Data Pengangkutan Penilaian Harian',
            ]);
            // End Log
            DB::commit();
            return redirect()->back()->with('success', 'Data Berhasil Dihapus');
        } catch (Exception $e) {
            DB::rollback();
            dd($e);
            return redirect()->back()->with('error', 'Data Gagal Dihapus');
        }
    }

    public function destroyAll(Request $request)
    {
        DB::beginTransaction();
        try {
            $findAll = PengangkutanPenilaianHarian::whereIn('id', $request->id_user)->get();
            foreach ($findAll as $find) {
                $bulan = Carbon::parse($find->tanggal_angkut_penilaian)->format('m');
                $tahun = Carbon::parse($find->tanggal_angkut_penilaian)->format('Y');
                $periode = Carbon::parse($find->tanggal_angkut_penilaian)->format('F Y');
                $find_user = User::where('id', $find->user_id)->firstOrFail();
                $find->delete();

                $find_rekapan = RekapanPenilaian::where('user_id', $find_user->id)->where('periode', $periode)->first();
                $count_penilaian_bulanan = PengangkutanPenilaianHarian::where('user_id', $find_user->id)->whereMonth('tanggal_angkut_penilaian', $bulan)->whereYear('tanggal_angkut_penilaian', $tahun)->count();

                $pemilah_aktif = PemilahAktif::where(
                    'periode',
                    Carbon::parse($periode)->format('F Y')
                )->where('publish', '1')->get();

                if ($pemilah_aktif->count() > 0) {
                    DB::rollback();
                    return response()->json(['success' => false, 'info' => "Data Pemilah Aktif Sudah Di Publish, Tidak Dapat Dihapus"]);
                }

                if (!empty($find_rekapan)) {
                    DetailRekapanPenilaian::where('rekapan_penilaian_id', $find_rekapan->id)->delete();
                    // Rekapan
                    $find_rekapan->jumlah_penilaian = $count_penilaian_bulanan;
                    $find_rekapan->save();

                    // Detail Kriteria
                    $kriteria = Kriteria::where('periode', $periode)->where('publish', '1')->orderBy('urutan', 'asc')->get();
                    if (empty($kriteria) || $kriteria->count() <= 0) {
                        DB::rollBack();
                        return response()->json(['success' => false, 'info' => "Kriteria Untuk Bulan $periode Belum Disetel, Silahkan Atur Terlebih Dahulu"]);
                    }

                    foreach ($kriteria as $item) {
                        $detail_pengangkutan = DetailPengangkutanPenilaianHarian::join('pengangkutan_penilaian_harian', 'pengangkutan_penilaian_harian.id', '=', 'detail_pengangkutan_penilaian_harian.pengangkutan_penilaian_harian_id')->where('kriteria_id', $item->id)->where('nilai_kriteria', 'iya')->where('pengangkutan_penilaian_harian.user_id', $find_user->id)->whereMonth('pengangkutan_penilaian_harian.tanggal_angkut_penilaian', $bulan)->whereYear('tanggal_angkut_penilaian', $tahun)->count();

                        DetailRekapanPenilaian::create([
                            'id' => Uuid::uuid4(),
                            'rekapan_penilaian_id' => $find_rekapan->id,
                            'kriteria_id' => $item->id,
                            'total_nilai' => $detail_pengangkutan,
                        ]);
                    }
                } else {
                    $rekapan_penilaian = RekapanPenilaian::create([
                        'id' => Uuid::uuid4(),
                        'user_id' => $find_user->id,
                        'periode' => $periode,
                        'jumlah_penilaian' => $count_penilaian_bulanan,
                    ]);

                    // Kriteria
                    $kriteria = Kriteria::where('periode', $periode)->where('publish', '1')->orderBy('urutan', 'asc')->get();
                    if (empty($kriteria) || $kriteria->count() <= 0) {
                        DB::rollBack();
                        return response()->json(['success' => false, 'info' => "Kriteria Untuk Bulan $periode Belum Disetel, Silahkan Atur Terlebih Dahulu"]);
                    }

                    foreach ($kriteria as $item) {
                        $detail_pengangkutan = DetailPengangkutanPenilaianHarian::join('pengangkutan_penilaian_harian', 'pengangkutan_penilaian_harian.id', '=', 'detail_pengangkutan_penilaian_harian.pengangkutan_penilaian_harian_id')->where('kriteria_id', $item->id)->where('nilai_kriteria', 'iya')->where('pengangkutan_penilaian_harian.user_id', $find_user->id)->whereMonth('pengangkutan_penilaian_harian.tanggal_angkut_penilaian', $bulan)->whereYear('tanggal_angkut_penilaian', $tahun)->count();
                        DetailRekapanPenilaian::create([
                            'id' => Uuid::uuid4(),
                            'rekapan_penilaian_id' => $rekapan_penilaian->id,
                            'kriteria_id' => $item->id,
                            'total_nilai' => $detail_pengangkutan,
                        ]);
                    }
                }
                DB::commit();
            }
            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'PengangkutanPenilaianController.php',
                'action' => 'Menggunakan Fitur Multi Hapus Pengangkutan Penilaian Harian',
            ]);
            // End Log
            return response()->json(['success' => true, 'info' => 'Data yang dipilih berhasil dihapus']);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'info' => $e]);
        }
    }
    public function scan()
    {
        DB::beginTransaction();
        try {
            $periode = Carbon::now()->format('F Y');
            $get_all = Kriteria::where('periode', $periode)->where('publish', '1')->orderBy('urutan', 'asc')->get();
            $config = Config::where('key', 'unpublish-time')->first();
            if ($get_all->count() > 0) {
                if ($config->status == 'active') {
                    if (\Carbon\Carbon::parse($get_all->first()->updated_at)->addMinute($config->value)->format('Y-m-d H:i:s') >= \Carbon\Carbon::now()->format('Y-m-d H:i:s')) {
                        // End Log
                        DB::rollBack();
                        return redirect()->back()->with('error', 'Kriteria Penilaian Masih Belum Selesai Diatur, Mohon Menunggu Terlebih Dahulu');
                    }
                }
                // Log Activity
                LogActivity::create([
                    'ip_address' => request()->ip(),
                    'user_id' => Auth::user()->id,
                    'previous_url' => URL::previous(),
                    'current_url' => URL::current(),
                    'file' => 'PengangkutanPenilaianController.php',
                    'action' => 'Akses Halaman Scan Pengangkutan Penilaian Harian',
                ]);
                // End Log
                DB::commit();
                return view('admin.page.pengangkutan-penilaian.scan', compact('get_all'));
            }
            DB::rollBack();
            return redirect()->back()->with('error', "Kriteria Untuk Bulan $periode Belum Disetel, Silahkan Atur Terlebih Dahulu");
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi Kesalahan Di Server');
        }
    }

    public function scanProcess(Request $request)
    {
        DB::beginTransaction();
        try {
            if (!empty($request->id_user)) {
                $find_user = User::where('id', $request->id_user)->first();
                if (!empty($find_user)) {
                    if ($find_user->role == 4 || $find_user->role == 5) {
                        if ($find_user->status_iuran == 1) {
                            // Cek Apakah Kriteria Sudah Ada
                            $periode = Carbon::now()->format('F Y');
                            $get_all = Kriteria::where('periode', $periode)->where('publish', '1')->orderBy('urutan', 'asc')->get();
                            if ($get_all->count() <= 0) {
                                DB::rollback();
                                return response()->json(['success' => false, 'info' => "Kriteria Untuk Bulan $periode Belum Disetel, Silahkan Atur Terlebih Dahulu"]);
                            }

                            // Cek Apakah Kriteia Sudah Selesai Diubah
                            $configUnpublish = Config::where('key', 'unpublish-time')->first();
                            if ($configUnpublish->status == 'active') {
                                if (\Carbon\Carbon::parse($get_all->first()->updated_at)->addMinute($configUnpublish->value)->format('Y-m-d H:i:s') >= \Carbon\Carbon::now()->format('Y-m-d H:i:s')) {
                                    // End Log
                                    DB::rollBack();
                                    return redirect(route('pengangkutan-penilaian'))->with('error', 'Kriteria Penilaian Masih Belum Selesai Diatur, Mohon Menunggu Terlebih Dahulu');
                                }
                            }

                            // Cek apakah sudah ada data penilaian harian
                            $config = Config::where('key', 'hari-penilaian')->first();
                            $penilaian_harian = PengangkutanPenilaianHarian::where('user_id', $find_user->id)->whereDate('tanggal_angkut_penilaian', Carbon::now()->format('Y-m-d'))->count();
                            if ($penilaian_harian > 0) {
                                DB::rollback();
                                return response()->json(['success' => false, 'info' => 'Pengangkutan dan Penilaian Hari Ini Untuk Nasabah/Pelanggan Ini Sudah Dilakukan']);
                            }
                            // Cek apakah sudah lebih dari batas
                            $penilaian_bulanan = PengangkutanPenilaianHarian::where('user_id', $find_user->id)->whereMonth('tanggal_angkut_penilaian', Carbon::now()->format('m'))->whereYear('tanggal_angkut_penilaian', Carbon::now()->format('Y'))->get();
                            if ($config->status == 'active' && $penilaian_bulanan->count() + 1 > $config->value) {
                                DB::rollback();
                                return response()->json(['success' => false, 'info' => 'Penilaian Harian Untuk Nasabah/Pelanggan Yang Dipindai Sudah Melebihi Batas']);
                            }
                            DB::commit();
                            return response()->json(['success' => true, 'info' => $find_user->id]);
                        }
                        DB::rollback();
                        return response()->json(['success' => false, 'info' => 'Bukan Pelanggan Aktif Pembayaran Iuran']);
                    }
                    DB::rollback();
                    return response()->json(['success' => false, 'info' => 'Bukan Merupakan Nasabah atau Pelanggan']);
                }
                DB::rollback();
                return response()->json(['success' => false, 'info' => 'Data Pelanggan atau Nasabah Tidak Ditemukan']);
            }
            DB::rollback();
            return response()->json(['success' => false, 'info' => 'Parameter ID User Harus Diisi']);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'info' => 'Data Tidak Ditemukan']);
        }
    }

    public function result_scan($id_user)
    {
        DB::beginTransaction();
        try {
            $find = User::where('id', $id_user)->firstOrFail();
            if ($find->role == 4 || $find->role == 5) {
                if ($find->status_iuran == 1) {
                    // Cek Apakah Kriteria Sudah Ada
                    $periode = Carbon::now()->format('F Y');
                    $get_all = Kriteria::where('periode', $periode)->where('publish', '1')->orderBy('urutan', 'asc')->get();
                    if ($get_all->count() <= 0) {
                        DB::rollback();
                        return redirect(route('pengangkutan-penilaian.scan'))->with('error', "Kriteria Untuk Bulan $periode Belum Disetel, Silahkan Atur Terlebih Dahulu");
                    }

                    // Cek Apakah Kriteia Sudah Selesai Diubah
                    $configUnpublish = Config::where('key', 'unpublish-time')->first();
                    if ($configUnpublish->status == 'active') {
                        if (\Carbon\Carbon::parse($get_all->first()->updated_at)->addMinute($configUnpublish->value)->format('Y-m-d H:i:s') >= \Carbon\Carbon::now()->format('Y-m-d H:i:s')) {
                            // End Log
                            DB::rollBack();
                            return redirect(route('pengangkutan-penilaian'))->with('error', 'Kriteria Penilaian Masih Belum Selesai Diatur, Mohon Menunggu Terlebih Dahulu');
                        }
                    }

                    // Cek apakah sudah ada data penilaian harian
                    $config = Config::where('key', 'hari-penilaian')->first();
                    $penilaian_harian = PengangkutanPenilaianHarian::where('user_id', $find->id)->whereDate('tanggal_angkut_penilaian', Carbon::now()->format('Y-m-d'))->count();
                    if ($penilaian_harian > 0) {
                        DB::rollback();
                        return redirect(route('pengangkutan-penilaian.scan'))->with('error', 'Pengangkutan dan Penilaian Hari Ini Untuk Nasabah/Pelanggan Ini Sudah Dilakukan');
                    }

                    // Cek apakah sudah lebih dari batas
                    $penilaian_bulanan = PengangkutanPenilaianHarian::where('user_id', $find->id)->whereMonth('tanggal_angkut_penilaian', Carbon::now()->format('m'))->whereYear('tanggal_angkut_penilaian', Carbon::now()->format('Y'))->get();
                    if ($config->status == 'active' && $penilaian_bulanan->count() + 1 > $config->value) {
                        DB::rollback();
                        return redirect(route('pengangkutan-penilaian.scan'))->with('error', 'Penilaian Harian Untuk Nasabah/Pelanggan Yang Dipindai Sudah Melebihi Batas');
                    }

                    $get_all = Kriteria::where('periode', Carbon::now()->format('F Y'))->where('urutan', '!=', null)->orderBy('urutan', 'asc')->get();
                    // Log Activity
                    LogActivity::create([
                        'ip_address' => request()->ip(),
                        'user_id' => Auth::user()->id,
                        'target_user' => $find->name,
                        'previous_url' => URL::previous(),
                        'current_url' => URL::current(),
                        'file' => 'PengangkutanPenilaianController.php',
                        'action' => 'Mendapatkan Hasil Scan Pengangkutan Penilaian Harian',
                    ]);
                    // End Log
                    DB::commit();
                    return view('admin.page.pengangkutan-penilaian._result-scan', compact('find', 'get_all'));
                }
                DB::rollback();
                return redirect(route('pengangkutan-penilaian.scan'))->with('error', 'Bukan Pelanggan Aktif Pembayaran Iuran');
            }
            DB::rollback();
            return redirect(route('pengangkutan-penilaian.scan'))->with('error', 'Bukan Merupakan Nasabah atau Pelanggan');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Tidak Ditemukan');
        }
    }

    public function resultProcess(Request $request, $id_user)
    {
        $request->validate([
            'id_kriteria.*' => 'required',
            'nilai_kriteria' => 'required|array|min:' . count($request->id_kriteria) . '',
        ]);
        DB::beginTransaction();
        try {
            // Cek apakah usernya ada
            $find_user = User::where('id', $id_user)->where('status_iuran', '=', 1)->where(function ($query) {
                $query->where('role', 4);
                $query->orWhere('role', 5);
            })->first();
            if (!empty($find_user)) {
                // Cek Apakah Kriteria Sudah Ada
                $periode = Carbon::now()->format('F Y');
                $get_all = Kriteria::where('periode', $periode)->where('publish', '1')->orderBy('urutan', 'asc')->get();
                if ($get_all->count() <= 0) {
                    DB::rollback();
                    return redirect(route('pengangkutan-penilaian.scan'))->with('error', "Kriteria Untuk Bulan $periode Belum Disetel, Silahkan Atur Terlebih Dahulu");
                }

                // Cek Apakah Kriteia Sudah Selesai Diubah
                $configUnpublish = Config::where('key', 'unpublish-time')->first();
                if ($configUnpublish->status == 'active') {
                    if (\Carbon\Carbon::parse($get_all->first()->updated_at)->addMinute($configUnpublish->value)->format('Y-m-d H:i:s') >= \Carbon\Carbon::now()->format('Y-m-d H:i:s')) {
                        // End Log
                        DB::rollBack();
                        return redirect(route('pengangkutan-penilaian'))->with('error', 'Kriteria Penilaian Masih Belum Selesai Diatur, Mohon Menunggu Terlebih Dahulu');
                    }
                }

                // Cek apakah sudah ada data penilaian harian
                $config = Config::where(
                    'key',
                    'hari-penilaian'
                )->first();
                $penilaian_harian = PengangkutanPenilaianHarian::where('user_id', $find_user->id)->whereDate('tanggal_angkut_penilaian', Carbon::now()->format('Y-m-d'))->count();
                if ($penilaian_harian > 0) {
                    DB::rollback();
                    return redirect(route('pengangkutan-penilaian.scan'))->with('error', 'Pengangkutan dan Penilaian Hari Ini Untuk Nasabah/Pelanggan Ini Sudah Dilakukan');
                }

                // Cek apakah sudah lebih dari batas
                $penilaian_bulanan = PengangkutanPenilaianHarian::where('user_id', $find_user->id)->whereMonth('tanggal_angkut_penilaian', Carbon::now()->format('m'))->whereYear('tanggal_angkut_penilaian', Carbon::now()->format('Y'))->get();
                if ($config->status == 'active' && $penilaian_bulanan->count() + 1 > $config->value) {
                    DB::rollback();
                    return redirect(route('pengangkutan-penilaian.scan'))->with('error', 'Penilaian Harian Untuk Nasabah/Pelanggan Yang Dipindai Sudah Melebihi Batas');
                }

                // Cek Form
                if (count($request->id_kriteria) != count($request->nilai_kriteria)) {
                    return redirect()->back()->with('error', 'Formulir Penilaian Yang Dikirim Tidak Lengkap, Mohon Periksa Kembali');
                }

                // Tambahkan Pengangkutan Penilaian Harian
                $penilaian = PengangkutanPenilaianHarian::create([
                    'id' => Uuid::uuid4(),
                    'user_id' => $find_user->id,
                    'pegawai_id' => Auth::user()->id,
                    'tanggal_angkut_penilaian' => Carbon::now()->format('Y-m-d H:i:s'),
                ]);

                // Buat Detail Pengangkutan Penilaian Harian
                for ($i = 0; $i < count($request->id_kriteria); $i++) {
                    DetailPengangkutanPenilaianHarian::create([
                        'id' => Uuid::uuid4(),
                        'pengangkutan_penilaian_harian_id' => $penilaian->id,
                        'kriteria_id' => $request->id_kriteria[$i],
                        'nilai_kriteria' => $request->nilai_kriteria[$i][$request->id_kriteria[$i]],
                    ]);
                }

                // Update Total Penilaian
                $find_rekapan = RekapanPenilaian::where('user_id', $find_user->id)->where('periode', $periode)->first();
                $count_penilaian_bulanan = PengangkutanPenilaianHarian::where('user_id', $find_user->id)->whereMonth('tanggal_angkut_penilaian', Carbon::now()->format('m'))->whereYear('tanggal_angkut_penilaian', Carbon::now()->format('Y'))->count();
                if (!empty($find_rekapan)) {
                    DetailRekapanPenilaian::where('rekapan_penilaian_id', $find_rekapan->id)->delete();

                    // Rekapan
                    $find_rekapan->jumlah_penilaian = $count_penilaian_bulanan;
                    $find_rekapan->save();

                    // Detail Kriteria
                    if (empty($get_all) || $get_all->count() <= 0) {
                        DB::rollBack();
                        return redirect()->back()->with('error', "Kriteria Untuk Bulan $periode Belum Disetel, Silahkan Atur Terlebih Dahulu");
                    }
                    foreach ($get_all as $item) {
                        $detail_pengangkutan = DetailPengangkutanPenilaianHarian::join('pengangkutan_penilaian_harian', 'pengangkutan_penilaian_harian.id', '=', 'detail_pengangkutan_penilaian_harian.pengangkutan_penilaian_harian_id')->where('kriteria_id', $item->id)->where('nilai_kriteria', 'iya')->where('pengangkutan_penilaian_harian.user_id', $find_user->id)->whereMonth('pengangkutan_penilaian_harian.tanggal_angkut_penilaian', Carbon::now()->format('m'))->whereYear('tanggal_angkut_penilaian', Carbon::now()->format('Y'))->count();

                        DetailRekapanPenilaian::create([
                            'id' => Uuid::uuid4(),
                            'rekapan_penilaian_id' => $find_rekapan->id,
                            'kriteria_id' => $item->id,
                            'total_nilai' => $detail_pengangkutan,
                        ]);
                    }
                } else {
                    $rekapan_penilaian = RekapanPenilaian::create([
                        'id' => Uuid::uuid4(),
                        'user_id' => $find_user->id,
                        'periode' => $periode,
                        'jumlah_penilaian' => $count_penilaian_bulanan,
                    ]);

                    // Kriteria
                    if (empty($get_all) || $get_all->count() <= 0) {
                        DB::rollBack();
                        return redirect()->back()->with('error', "Kriteria Untuk Bulan $periode Belum Disetel, Silahkan Atur Terlebih Dahulu");
                    }

                    foreach ($get_all as $item) {
                        $detail_pengangkutan = DetailPengangkutanPenilaianHarian::join('pengangkutan_penilaian_harian', 'pengangkutan_penilaian_harian.id', '=', 'detail_pengangkutan_penilaian_harian.pengangkutan_penilaian_harian_id')->where('kriteria_id', $item->id)->where('nilai_kriteria', 'iya')->where('pengangkutan_penilaian_harian.user_id', $find_user->id)->whereMonth('pengangkutan_penilaian_harian.tanggal_angkut_penilaian', Carbon::now()->format('m'))->whereYear('tanggal_angkut_penilaian', Carbon::now()->format('Y'))->count();

                        DetailRekapanPenilaian::create([
                            'id' => Uuid::uuid4(),
                            'rekapan_penilaian_id' => $rekapan_penilaian->id,
                            'kriteria_id' => $item->id,
                            'total_nilai' => $detail_pengangkutan,
                        ]);
                    }
                }

                // Send Whatsapp To User When Finish Transaction
                $message = Controller::message_pengangkutan($find_user->name, $penilaian->created_at, Auth::user()->name);
                if ($find_user->no_telp != '') {
                    // send message to whatsapp number
                    Controller::sendMessage($find_user->no_telp, $message);
                    Controller::email_pengangkutan($penilaian->created_at, Auth::user()->name, $find_user->email, $find_user->name);
                } else {
                    Controller::email_pengangkutan($penilaian->created_at, Auth::user()->name, $find_user->email, $find_user->name);
                }

                // Send Notif For Website
                $pesan_notif = Controller::notif_pengangkutan($penilaian->created_at, Auth::user()->name);
                Controller::storeNotification($find_user->id, 'angkut', 'Pengangkutan Sampah', $pesan_notif);
                // End Send Notif When Finish Transaction

                // Log Activity
                LogActivity::create([
                    'ip_address' => request()->ip(),
                    'user_id' => Auth::user()->id,
                    'previous_url' => URL::previous(),
                    'current_url' => URL::current(),
                    'file' => 'PengangkutanPenilaianController.php',
                    'action' => 'Menambahkan Data Pengangkutan Penilaian Harian',
                ]);
                // End Log
                DB::commit();
                return redirect(route('pengangkutan-penilaian.scan'))->with('success', 'Data Berhasil Ditambahkan');
            }
            DB::rollback();
            return redirect(route('pengangkutan-penilaian.scan'))->with('error', 'Pelanggan/Nasabah Tidak Ditemukan atau Status Iuran Non-Aktif');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Ditambahkan');
        }
    }
}
