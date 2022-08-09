<?php

namespace App\Http\Controllers\Enduser;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\RekapanSampah;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\RekapanPenarikanTabungan;
use Illuminate\Support\Facades\Validator;

class TabunganController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role == 5) {
            return redirect(route('enduser.dashboard'))->with('error', 'Anda belum terdaftar sebagai nasabah!');
        }
        $rekapan_penarikan = RekapanPenarikanTabungan::where('user_id', $user->id)->whereDate('created_at', Carbon::now()->format('Y-m-d'))->get();
        $rekapan_pemasukan = RekapanSampah::where('user_id', $user->id)->whereDate('created_at', Carbon::now()->format('Y-m-d'))->get();
        return view('enduser.page.tabungan.index', ['user' => $user, 'rekapan_penarikan' => $rekapan_penarikan, 'rekapan_pemasukan' => $rekapan_pemasukan]);
    }

    public function filter(Request $request)
    {
        $user = Auth::user();
        $validator = $this->_validator($request->all(), 'filter');
        if ($validator->fails()) {
            $validator->validate();
            return redirect(route('enduser.tabungan.index'))->with('error', 'Gagal Memfilter Data, Periksa Inputan Anda');
        }

        if (empty($request->tanggal_awal) || empty($request->tanggal_akhir)) {
            return redirect(route('enduser.tabungan.index'));
        }
        $start = Carbon::createFromFormat('Y-m-d', $request->tanggal_awal)->startOfDay()->toDateTimeString();
        $end = Carbon::createFromFormat('Y-m-d', $request->tanggal_akhir)->endOfDay()->toDateTimeString();

        $rekapan_penarikan = RekapanPenarikanTabungan::where('user_id', $user->id)->where('created_at', '>=', $start)->Where('created_at', '<=', $end)->get();
        $rekapan_pemasukan = RekapanSampah::where('user_id', $user->id)->where('created_at', '>=', $start)->Where('created_at', '<=', $end)->get();

        return view('enduser.page.tabungan.index', ['user' => $user, 'rekapan_penarikan' => $rekapan_penarikan, 'rekapan_pemasukan' => $rekapan_pemasukan]);
    }

    public function _validator(array $data, $status)
    {
        if ($status == 'filter') {
            return Validator::make($data, [
                'tanggal_awal' => ['nullable', 'date'],
                'tanggal_akhir' => ['nullable', 'after:tanggal_awal'],
                'status' => ['nullable', 'string'],
            ]);
        }
    }
}
