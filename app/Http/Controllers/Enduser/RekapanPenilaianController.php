<?php

namespace App\Http\Controllers\Enduser;

use Carbon\Carbon;
use App\Models\PemilahAktif;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\PengangkutanPenilaianHarian;
use Yajra\DataTables\DataTables as DataTablesDataTables;

class RekapanPenilaianController extends Controller
{
    public function index()
    {
        $total = PemilahAktif::where('user_id', Auth::user()->id)->where('publish', '1')->count();
        return view('enduser.page.rekapan-penilaian.index', compact('total'));
    }

    public function getAllRiwayat(Request $request)
    {
        if ($request->bulan && $request->tahun) {
            $periode = $request->bulan . ' ' . $request->tahun;
            $penilaian = PengangkutanPenilaianHarian::where('user_id', Auth::user()->id)->whereMonth('tanggal_angkut_penilaian', Carbon::parse($periode)->format('m'))->whereYear('tanggal_angkut_penilaian', Carbon::parse($periode)->format('Y'))->orderBy('tanggal_angkut_penilaian', 'DESC')->get();
        } else {
            $penilaian = PengangkutanPenilaianHarian::where('user_id', Auth::user()->id)->whereMonth('tanggal_angkut_penilaian', Carbon::now()->format('m'))->whereYear('tanggal_angkut_penilaian', Carbon::now()->format('Y'))->orderBy('tanggal_angkut_penilaian', 'DESC')->get();
        }
        return DataTablesDataTables::of($penilaian)
            ->addIndexColumn()
            ->editColumn('tanggal_angkut_penilaian', function ($model) {
                return Carbon::parse($model->tanggal_angkut_penilaian)->format('d F Y H:i') . ' WITA';
            })
            ->editColumn('pegawai', function ($model) {
                return $model->pegawai->name;
            })
            ->make(true);
    }

    public function getAllRekomendasi(Request $request)
    {
        if ($request->bulan && $request->tahun) {
            $periode = $request->bulan . ' ' . $request->tahun;
            $penilaian = PemilahAktif::where('publish', '1')->where('periode', Carbon::parse($periode)->format('F Y'))->orderBy('ranking', 'ASC')->get();
        } else {
            $penilaian = PemilahAktif::where('publish', '1')->where('periode', Carbon::now()->format('F Y'))->orderBy('ranking', 'ASC')->get();
        }
        return DataTablesDataTables::of($penilaian)
            ->editColumn('no_member', function ($model) {
                return $model->user->no_member;
            })
            ->editColumn('user', function ($model) {
                return $model->user->name;
            })
            ->make(true);
    }
}
