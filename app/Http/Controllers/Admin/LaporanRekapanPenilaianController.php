<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\Models\Config;
use App\Models\Kriteria;
use App\Models\LogActivity;
use App\Models\Rekomendasi;
use App\Models\PemilahAktif;
use Illuminate\Http\Request;
use App\Models\RekapanPenilaian;
use App\Exports\RekomendasiExport;
use Illuminate\Support\Facades\DB;
use App\Exports\PemilahAktifExport;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\DetailRekapanPenilaian;
use App\Exports\RekapanPenilaianExport;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables as DataTablesDataTables;

class LaporanRekapanPenilaianController extends Controller
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
        if (!empty($request->bulan) && !empty($request->tahun)) {
            $request->validate([
                'bulan' => 'required|date_format:F',
                'tahun' => 'required|date_format:Y',
            ]);
        }

        if ($request->bulan && $request->tahun) {
            $periode = $request->bulan . ' ' . $request->tahun;
            $rekapan = RekapanPenilaian::where('periode', Carbon::parse($periode)->format('F Y'))->count();
        } else {
            $rekapan = RekapanPenilaian::where('periode', Carbon::now()->format('F Y'))->count();
        }
        $configKey = Config::where('key', 'minimal-penilaian')->first();
        // Log Activity
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'LaporanRekapanPenilaianController.php',
            'action' => 'Akses Halaman Laporan Rekapan Penilaian',
        ]);
        // End Log
        return view('admin.page.rekapan-penilaian.index', compact('rekapan', 'configKey'));
    }

    public function getAll(Request $request)
    {
        if ($request->bulan && $request->tahun) {
            $periode = $request->bulan . ' ' . $request->tahun;
            $penilaian = RekapanPenilaian::where('periode', Carbon::parse($periode)->format('F Y'))->get();
        } else {
            $penilaian = RekapanPenilaian::where('periode', Carbon::now()->format('F Y'))->get();
        }
        return DataTablesDataTables::of($penilaian)
            ->addIndexColumn()
            ->editColumn('no_member', function ($model) {
                return $model->user->no_member;
            })
            ->editColumn('user', function ($model) {
                return $model->user->name;
            })
            ->editColumn('status', function ($model) {
                return $model->user->status_iuran;
            })
            ->make(true);
    }

    public function detail(Request $request)
    {
        $find = RekapanPenilaian::where('id', $request->id)->first();
        if (!empty($find)) {
            $detail = DetailRekapanPenilaian::select('detail_rekapan_penilaian.*')->join('kriteria', 'kriteria.id', '=', 'detail_rekapan_penilaian.kriteria_id')->where('rekapan_penilaian_id', $find->id)->where('kriteria.publish', '1')->orderBy('kriteria.urutan', 'ASC')->get();
        } else {
            $detail = false;
            $find = false;
        }
        return view('admin.page.rekapan-penilaian._detail', compact('find', 'detail'));
    }

    public function export(Request $request, $status)
    {
        if ($status == 'filter') {
            $validate = Validator::make($request->all(), [
                'bulan' => 'required|date_format:F',
                'tahun' => 'required|date_format:Y',
            ]);
            if ($validate->fails()) {
                return redirect()->back()->with('error', 'Data Bulan dan Tahun Tidak Sesuai Format Yang Diminta, Silahkan Ulangi Melakukan Filter Data');
            }
        }

        DB::beginTransaction();
        try {
            if ($status == 'filter') {
                $periode = $request->bulan . ' ' . $request->tahun;
                $penilaian = RekapanPenilaian::query();
                $penilaian->where('periode', Carbon::parse($periode)->format('F Y'));
                $penilaian = $penilaian->select(['users.no_member', 'users.name', 'periode', 'jumlah_penilaian'])->join('users', 'users.id', '=', 'rekapan_penilaian.user_id');
                return Excel::download(new RekapanPenilaianExport($penilaian), 'export_data_rekapan_penilaian.xlsx');
            }
            $penilaian = RekapanPenilaian::query();
            $penilaian->select(['users.no_member', 'users.name', 'periode', 'jumlah_penilaian'])->join('users', 'users.id', '=', 'rekapan_penilaian.user_id');
            $penilaian = $penilaian->whereIn('rekapan_penilaian.id', json_decode($request->id_user));
            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'LaporanRekapanPenilaianController.php',
                'action' => 'Ekspor Laporan Rekapan Penilaian',
            ]);
            // End Log
            DB::commit();
            return Excel::download(new RekapanPenilaianExport($penilaian), 'export_data_rekapan_penilaian.xlsx');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Diekspor');
        }
    }

    public function exportSingle($id)
    {
        DB::beginTransaction();
        try {
            $penilaian = RekapanPenilaian::query();
            $penilaian->select(['users.no_member', 'users.name', 'periode', 'jumlah_penilaian'])->join('users', 'users.id', '=', 'rekapan_penilaian.user_id');
            $penilaian = $penilaian->where('rekapan_penilaian.id', $id);
            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'LaporanRekapanPenilaianController.php',
                'action' => 'Ekspor Laporan Rekapan Penilaian',
            ]);
            // End Log
            DB::commit();
            return Excel::download(new RekapanPenilaianExport($penilaian), 'export_data_rekapan_penilaian.xlsx');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Diekspor');
        }
    }
    public function electreQuery(Request $request)
    {
        if (!empty($request->bulan) && !empty($request->tahun)) {
            $request->validate([
                'bulan' => 'required|date_format:F',
                'tahun' => 'required|date_format:Y',
            ]);
        } else {
            return redirect()->back()->with('error', 'Tidak Ditemukan Rekomendasi Untuk Periode Tersebut');
        }

        DB::beginTransaction();
        try {
            // Start Electre Query
            $periode = Carbon::parse(UcWords(strtolower($request->bulan . ' ' . $request->tahun)))->format('F Y');
            Rekomendasi::where('periode', $periode)->delete();

            // Get Data Alternatif
            $alternatif = [];
            $configKey = Config::where('key', 'minimal-penilaian')->first();
            if ($configKey->status == 'active') {
                $queryalternatif = RekapanPenilaian::select('rekapan_penilaian.*')->join('users', 'users.id', '=', 'rekapan_penilaian.user_id')->where('users.status_iuran', 1)->where('rekapan_penilaian.periode', $periode)->where('rekapan_penilaian.jumlah_penilaian', '>=', $configKey->value)->get();
            } else {
                $queryalternatif = RekapanPenilaian::select('rekapan_penilaian.*')->join('users', 'users.id', '=', 'rekapan_penilaian.user_id')->where('users.status_iuran', 1)->where('rekapan_penilaian.periode', $periode)->get();
            }

            if ($queryalternatif->count() < 2) {
                DB::rollBack();
                return redirect(route('rekapan-penilaian', ['bulan' => $request->bulan, 'tahun' => $request->tahun]))->with('error', 'Tidak Dapat Mengolah Data Yang Jumlahnya Terlalu Sedikit ');
            }

            $i = 0;
            foreach ($queryalternatif as $item) {
                $alternatif[$i] = $item->user->id;
                $i++;
            }

            // Get Data Kriteria dan Bobot
            $kriteria = [];
            $bobot = [];

            $querykriteria = Kriteria::where('periode', $periode)->where('publish', '1')->orderBy('urutan', 'ASC')->get();
            $i = 0;
            $bobot_total = 0;
            foreach ($querykriteria as $item) {
                $find_rekapan = DetailRekapanPenilaian::select('detail_rekapan_penilaian.*')->join('rekapan_penilaian', 'rekapan_penilaian.id', '=', 'detail_rekapan_penilaian.rekapan_penilaian_id')->where('rekapan_penilaian.periode', $periode)->where('kriteria_id', $item->id)->count();
                if ($find_rekapan > 0) {
                    $kriteria[$i] = $item->nama_kriteria;
                    $bobot[$i] = $item->bobot / 100;
                    $bobot_total += $item->bobot;
                    $i++;
                }
            }

            // Cek Nilai Bobot Total
            if ($bobot_total != 100) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Total Kriteria Yang Digunakan Sebanyak ' . count($kriteria) . ' Kriteria,Dengan Nilai Bobot Total Tidak Sama Dengan 100%. Silahkan sesuaikan terlebih dahulu');
            }

            // Get Data Alternatif Kriteria
            $alternatifkriteria = [];
            $i = 0;
            foreach ($queryalternatif as $item) {
                $querykriteria = Kriteria::where('periode', $periode)->where('publish', '1')->orderBy('urutan', 'ASC')->get();
                $j = 0;
                foreach ($querykriteria as $item_kriteria) {
                    $find_rekapan = DetailRekapanPenilaian::select('detail_rekapan_penilaian.*')->join('rekapan_penilaian', 'rekapan_penilaian.id', '=', 'detail_rekapan_penilaian.rekapan_penilaian_id')->where('rekapan_penilaian.periode', $periode)->where('kriteria_id', $item_kriteria->id)->count();
                    if ($find_rekapan > 0) {
                        $alternatifkriteria[$i][$j] = DetailRekapanPenilaian::where('rekapan_penilaian_id', $item->id)->where('kriteria_id', $item_kriteria->id)->first()->total_nilai;
                        $j++;
                    }
                }
                $i++;
            }
            // Get Data Pembagi
            $pembagi = [];

            for ($i = 0; $i < $querykriteria->count(); $i++) {
                $pembagi[$i] = 0;
                for ($j = 0; $j < $queryalternatif->count(); $j++) {
                    $pembagi[$i] = $pembagi[$i] + ($alternatifkriteria[$j][$i] * $alternatifkriteria[$j][$i]);
                }
                $pembagi[$i] = sqrt($pembagi[$i]);
            }

            // Normalisasi Matriks
            $normalisasi = [];

            for ($i = 0; $i < $queryalternatif->count(); $i++) {
                for ($j = 0; $j < $querykriteria->count(); $j++) {
                    // Mengatasi Division By Zero
                    $normalisasi[$i][$j] = $pembagi[$j] == 0 ? 0 : $alternatifkriteria[$i][$j] / $pembagi[$j];
                }
            }
            // Matriks V
            $V = [];

            for ($i = 0; $i < $queryalternatif->count(); $i++) {
                for ($j = 0; $j < $querykriteria->count(); $j++) {
                    $V[$i][$j] = $normalisasi[$i][$j] * $bobot[$j];
                }
            }

            $concordance = [];
            $discordance = [];

            $matriks_concordance = [];
            $matriks_discordance = [];
            $maks_discordance = [];
            $maks_pembagi_discordance = [];

            $treshold_matriks_concordance = 0;
            $treshold_matriks_discordance = 0;

            for ($i = 0; $i < $queryalternatif->count(); $i++) {
                for ($j = 0; $j < $queryalternatif->count(); $j++) {
                    $concordance[$i][$j] = '';
                    $discordance[$i][$j] = '';

                    $matriks_concordance[$i][$j] = '';
                    $matriks_discordance[$i][$j] = '';
                    $maks_discordance[$i][$j] = 0;
                    $maks_pembagi_discordance[$i][$j] = 0;

                    if ($i != $j) {
                        $matriks_concordance[$i][$j] = 0;
                        $matriks_discordance[$i][$j] = 0;

                        for ($k = 0; $k < $querykriteria->count(); $k++) {
                            if ($V[$i][$k] >= $V[$j][$k]) {
                                if ($concordance[$i][$j] == '') {
                                    $concordance[$i][$j] = $concordance[$i][$j] . ($k + 1);
                                } else {
                                    $concordance[$i][$j] = $concordance[$i][$j] . ',' . ($k + 1);
                                }
                                $matriks_concordance[$i][$j] = $matriks_concordance[$i][$j] + $bobot[$k];
                            }
                            if ($V[$i][$k] < $V[$j][$k]) {
                                if ($discordance[$i][$j] == '') {
                                    $discordance[$i][$j] = $discordance[$i][$j] . ($k + 1);
                                } else {
                                    $discordance[$i][$j] = $discordance[$i][$j] . ',' . ($k + 1);
                                }
                                $maks_discordance[$i][$j] = max($maks_discordance[$i][$j], abs($V[$i][$k] - $V[$j][$k]));
                            }
                            $maks_pembagi_discordance[$i][$j] = max($maks_pembagi_discordance[$i][$j], abs($V[$i][$k] - $V[$j][$k]));
                        }
                        $matriks_discordance[$i][$j] =  $maks_discordance[$i][$j] / $maks_pembagi_discordance[$i][$j];
                        $treshold_matriks_concordance = $treshold_matriks_concordance + $matriks_concordance[$i][$j];
                        $treshold_matriks_discordance = $treshold_matriks_discordance + $matriks_discordance[$i][$j];
                    }
                }
            }

            $treshold_matriks_concordance = $treshold_matriks_concordance / ($queryalternatif->count() * ($queryalternatif->count() - 1));
            $treshold_matriks_discordance = $treshold_matriks_discordance / ($queryalternatif->count() * ($queryalternatif->count() - 1));

            $matriks_dominan_concordance = [];
            $matriks_dominan_discordance = [];
            $agregate_dominance_matrix = [];

            $jml_nilai_1 = [];

            for ($i = 0; $i < $queryalternatif->count(); $i++) {
                $jml_nilai_1[$i] = 0;

                for ($j = 0; $j < $queryalternatif->count(); $j++) {
                    $matriks_dominan_concordance[$i][$j] = '';
                    $matriks_dominan_discordance[$i][$j] = '';
                    $agregate_dominance_matrix[$i][$j] = '';
                    if ($i != $j) {
                        $matriks_dominan_concordance[$i][$j] = 0;
                        $matriks_dominan_discordance[$i][$j] = 0;
                        $agregate_dominance_matrix[$i][$j] = 0;

                        if ($matriks_concordance[$i][$j] >= $treshold_matriks_concordance) {
                            $matriks_dominan_concordance[$i][$j] = 1;
                        }
                        if ($matriks_discordance[$i][$j] >= $treshold_matriks_discordance) {
                            $matriks_dominan_discordance[$i][$j] = 1;
                        }

                        $agregate_dominance_matrix[$i][$j] = $matriks_dominan_concordance[$i][$j] * $matriks_dominan_discordance[$i][$j];

                        if ($agregate_dominance_matrix[$i][$j] >= 1) {
                            $jml_nilai_1[$i] = $jml_nilai_1[$i] + 1;
                        }
                    }
                }
            }

            // Sorting Keputusan

            for ($i = 0; $i < $queryalternatif->count(); $i++) {
                $jml_nilai_1_rangking[$i] = $jml_nilai_1[$i];
                $alternatif_rangking[$i] = $alternatif[$i];
            }

            for ($i = 0; $i < $queryalternatif->count(); $i++) {
                for ($j = $i; $j < $queryalternatif->count(); $j++) {
                    if ($jml_nilai_1_rangking[$i] < $jml_nilai_1_rangking[$j]) {
                        $tmp_jml_nilai_1_rangking = $jml_nilai_1_rangking[$i];
                        $tmp_alternatif = $alternatif_rangking[$i];
                        $jml_nilai_1_rangking[$i] = $jml_nilai_1_rangking[$j];
                        $alternatif_rangking[$i] = $alternatif_rangking[$j];
                        $jml_nilai_1_rangking[$j] = $tmp_jml_nilai_1_rangking;
                        $alternatif_rangking[$j] = $tmp_alternatif;
                    }
                }
            }

            // Submit to DB
            for ($i = 0; $i < count($alternatif_rangking); $i++) {
                Rekomendasi::where('periode', $periode)->where('user_id', '!=', $alternatif_rangking[$i])->create([
                    'id' => Uuid::uuid4(),
                    'ranking' => $i + 1,
                    'periode' => $periode,
                    'user_id' => $alternatif_rangking[$i],
                    'hasil_electre' => $jml_nilai_1_rangking[$i],
                ]);
            }
            $rekomendasi = Rekomendasi::where('periode', $periode)->count();
            $pemilah_aktif = PemilahAktif::where('periode', $periode)->get();
            $configUnpublish = Config::where('key', 'unpublish-time')->first();

            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'LaporanRekapanPenilaianController.php',
                'action' => 'Akses Halaman Metode ELECTRE',
            ]);
            // End Log
            DB::commit();
            return view('admin.page.rekapan-penilaian.rekomendasi', compact('querykriteria', 'configUnpublish', 'alternatifkriteria', 'pembagi', 'normalisasi', 'V', 'concordance', 'discordance', 'matriks_concordance', 'matriks_discordance', 'treshold_matriks_concordance', 'treshold_matriks_discordance', 'matriks_dominan_concordance', 'matriks_dominan_discordance', 'agregate_dominance_matrix', 'jml_nilai_1', 'jml_nilai_1_rangking', 'alternatif_rangking', 'rekomendasi', 'pemilah_aktif'));
        } catch (Exception $e) {
            DB::rollback();
            dd($e);
            return redirect()->back()->with('error', 'Data Rekomendasi Pemilah Aktif Gagal Didapatkan');
        }
    }

    public function getAllRekomendasi(Request $request)
    {
        if (!empty($request->bulan) && !empty($request->tahun)) {
            $periode = Carbon::parse(UcWords(strtolower($request->bulan . ' ' . $request->tahun)))->format('F Y');
        } else {
            $periode = Carbon::now()->format('F Y');
        }
        $rekomendasi = Rekomendasi::where('periode', $periode)->orderBy('ranking', 'ASC')->get();
        return DataTablesDataTables::of($rekomendasi)
            ->addIndexColumn()
            ->setRowAttr([
                'class' => function ($model) {
                    if ($model->where('periode', $model->periode)->max('hasil_electre') == $model->hasil_electre) {
                        return 'bg-warning text-white';
                    }
                },
            ])
            ->editColumn('no_member', function ($model) {
                return $model->user->no_member;
            })
            ->editColumn('user', function ($model) {
                return $model->user->name;
            })
            ->editColumn('hasil_electre', function ($model) {
                if ($model->where('periode', $model->periode)->max('hasil_electre') == $model->hasil_electre) {
                    return $model->hasil_electre . ' -- (Paling Direkomendasikan)';
                }
                return $model->hasil_electre;
            })
            ->make(true);
    }

    public function exportRekomendasi(Request $request)
    {
        $request->validate([
            'bulan' => 'required|date_format:F',
            'tahun' => 'required|date_format:Y',
        ]);
        DB::beginTransaction();
        try {
            $rekomendasi = Rekomendasi::query();
            $rekomendasi->select(['ranking', 'periode', 'users.no_member', 'users.name', 'hasil_electre'])->join('users', 'users.id', '=', 'rekomendasi.user_id');
            $rekomendasi->where('periode', Carbon::parse($request->bulan . ' ' . $request->tahun)->format('F Y'))->orderBy('ranking', 'ASC');
            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'LaporanRekapanPenilaianController.php',
                'action' => 'Ekspor Rekomendasi',
            ]);
            // End Log
            DB::commit();
            return Excel::download(new RekomendasiExport($rekomendasi), 'Export_Data_Rekomendasi_Periode_' . Carbon::parse($request->bulan . ' ' . $request->tahun)->format('F Y') . '.xlsx');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Diekspor');
        }
    }

    public function getAllPemenang(Request $request)
    {
        if ($request->bulan && $request->tahun) {
            $periode = $request->bulan . ' ' . $request->tahun;
            $penilaian = PemilahAktif::where('periode', Carbon::parse($periode)->format('F Y'))->orderBy('ranking', 'asc')->get();
        } else {
            $penilaian = PemilahAktif::where('periode', Carbon::now()->format('F Y'))->orderBy('ranking', 'asc')->get();
        }
        return DataTablesDataTables::of($penilaian)
            ->addIndexColumn()
            ->editColumn('no_member', function ($model) {
                return $model->user->no_member;
            })
            ->editColumn('user', function ($model) {
                return $model->user->name;
            })
            ->make(true);
    }

    public function listRekomendasiPemenang(Request $request)
    {
        $periode = Carbon::parse(UcWords(strtolower($request->bulan . ' ' . $request->tahun)))->format('F Y');
        $rekomendasi = Rekomendasi::where('periode', $periode)->orderBy('ranking', 'ASC')->get();
        return view('admin.page.rekapan-penilaian._list-rekomendasi', compact('rekomendasi', 'periode'));
    }

    public function prosesPemenang(Request $request)
    {
        $request->validate([
            'periode' => 'required|date_format:F Y',
            'checkbox_pemilah_aktif.*' => 'required',
            'checkbox_pemilah_aktif' => 'required|min:1',
        ]);
        DB::beginTransaction();
        try {
            $get_all = PemilahAktif::where('periode', $request->periode)->get();
            foreach ($get_all as $item) {
                if ($item->publish == '0') {
                    $item->delete();
                } else {
                    DB::rollback();
                    return redirect()->back()->with('error', 'Data Pemilah Aktif Untuk Periode Ini Sudah Dipublish');
                }
            }

            for ($i = 0; $i < count($request->checkbox_pemilah_aktif); $i++) {
                $find = Rekomendasi::where('id', $request->checkbox_pemilah_aktif[$i])->first();
                if (!empty($find)) {
                    PemilahAktif::create([
                        'id' => Uuid::uuid4(),
                        'user_id' => $find->user_id,
                        'ranking' => $find->ranking,
                        'hasil_electre' => $find->hasil_electre,
                        'periode' => $request->periode,
                        'alasan' => $request->alasan[$find->id] == null ? 'Sesuai Rekomendasi Sistem' : ucWords(strtolower($request->alasan[$find->id])),
                    ]);
                }
            }

            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'LaporanRekapanPenilaianController.php',
                'action' => 'Import Pemilah Aktif',
            ]);
            // End Log
            DB::commit();
            return redirect()->back()->with('success', 'Data Berhasil Disimpan');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Disimpan');
        }
    }

    public function destroyPemenang(Request $request)
    {
        $request->validate([
            'bulan' => 'required|date_format:F',
            'tahun' => 'required|date_format:Y',
        ]);
        DB::beginTransaction();
        try {
            $periode = $request->bulan . ' ' . $request->tahun;
            $get_all = PemilahAktif::where('periode', Carbon::parse($periode)->format('F Y'))->get();
            foreach ($get_all as $item) {
                if ($item->publish == '0') {
                    $item->delete();
                } else {
                    DB::rollback();
                    return redirect()->back()->with('error', 'Data Pemilah Aktif Untuk Periode Ini Sudah Dipublish');
                }
            }
            DB::commit();
            return redirect()->back()->with('success', 'Data Berhasil Dikosongkan');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Dikosongkan');
        }
    }

    public function publishPemenang(Request $request)
    {
        $request->validate([
            'bulan' => 'required|date_format:F',
            'tahun' => 'required|date_format:Y',
        ]);
        DB::beginTransaction();
        try {
            $periode = $request->bulan . ' ' . $request->tahun;
            $get_all = PemilahAktif::where('periode', Carbon::parse($periode)->format('F Y'))->get();
            foreach ($get_all as $item) {
                if ($item->publish == '0') {
                    $item->publish = '1';
                    $item->save();
                } else {
                    DB::rollback();
                    return redirect()->back()->with('error', 'Data Pemilah Aktif Untuk Periode Ini Sudah Dipublish');
                }
            }
            DB::commit();
            return redirect()->back()->with('success', 'Data Berhasil Dipublish');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Dipublish');
        }
    }

    public function unpublishPemenang(Request $request)
    {
        $request->validate([
            'bulan' => 'required|date_format:F',
            'tahun' => 'required|date_format:Y',
        ]);
        DB::beginTransaction();
        try {
            $config = Config::where('key', 'unpublish-time')->first();
            $periode = $request->bulan . ' ' . $request->tahun;
            $get_all = PemilahAktif::where('periode', Carbon::parse($periode)->format('F Y'))->get();
            foreach ($get_all as $item) {
                if ($config->status == 'active') {
                    if ($item->publish == '1') {
                        if (\Carbon\Carbon::parse($item->updated_at)->addMinute($config->value)->format('Y-m-d H:i:s') >= \Carbon\Carbon::now()->format('Y-m-d H:i:s')) {
                            $item->publish = '0';
                            $item->save();
                        } else {
                            DB::rollback();
                            return redirect()->back()->with('error', 'Tidak Dapat Melakukan Unpublish');
                        }
                    }
                } else {
                    DB::rollback();
                    abort(404);
                }
            }
            DB::commit();
            return redirect()->back()->with('success', 'Data Berhasil Di Unpublish');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Di Unpublish');
        }
    }

    public function exportPemenang(Request $request)
    {
        $request->validate([
            'bulan' => 'required|date_format:F',
            'tahun' => 'required|date_format:Y',
        ]);
        DB::beginTransaction();
        try {
            $pemilah_aktif = PemilahAktif::query();
            $pemilah_aktif->select(['ranking', 'periode', 'users.no_member', 'users.name', 'hasil_electre', 'alasan'])->join('users', 'users.id', '=', 'pemilah_aktif.user_id');
            $pemilah_aktif->where('periode', Carbon::parse($request->bulan . ' ' . $request->tahun)->format('F Y'))->orderBy('ranking', 'ASC');
            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'LaporanRekapanPenilaianController.php',
                'action' => 'Ekspor Pemilah Aktif',
            ]);
            // End Log
            DB::commit();
            return Excel::download(new PemilahAktifExport($pemilah_aktif), 'Export_Data_Pemilah_Aktif_Periode_' . Carbon::parse($request->bulan . ' ' . $request->tahun)->format('F Y') . '.xlsx');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Diekspor');
        }
    }
}
