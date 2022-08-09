<?php

namespace App\Http\Controllers\Enduser;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\RekapanSampah;
use App\Models\DetailRekapanSampah;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PenyetoranController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role == 4) {
            $rekapan = RekapanSampah::where('user_id', $user->id)->whereDate('created_at', Carbon::now()->format('Y-m-d'))->get();
        } else {
            $rekapan = 0;
        }
        return view('enduser.page.penyetoran.index', ['user' => $user, 'rekapan' => $rekapan]);
    }

    public function detail(Request $request)
    {
        $user = Auth::user();
        $detail = DetailRekapanSampah::where('rekapan_sampah_id', $request->rekapan_sampah_id)->get();
        if ($user->role == 5) {
            return redirect(route('enduser.penyetoran.index'))->with('error', 'Anda belum terdaftar sebagai nasabah!!');
        }
        return view('enduser.page.penyetoran.detail', ['user' => $user, 'detail' => $detail]);
    }
    public function filter(Request $request)
    {
        $user = Auth::user();
        $validator = $this->_validator($request->all(), 'filter');
        if ($validator->fails()) {
            $validator->validate();
            return redirect(route('enduser.penyetoran.index'))->with('error', 'Gagal Memfilter Data, Periksa Inputan Anda');
        }

        if (empty($request->tanggal_awal) || empty($request->tanggal_akhir)) {
            return redirect(route('enduser.penyetoran.index'));
        }
        $start = Carbon::createFromFormat('Y-m-d', $request->tanggal_awal)->startOfDay()->toDateTimeString();
        $end = Carbon::createFromFormat('Y-m-d', $request->tanggal_akhir)->endOfDay()->toDateTimeString();

        $rekapan_iuran = RekapanSampah::where('user_id', $user->id)->where('created_at', '>=', $start)->where('created_at', '<=', $end)->get();

        return view('enduser.page.penyetoran.index', ['rekapan_iuran' => $rekapan_iuran, 'user' => $user]);
    }

    public function _validator(array $data, $status)
    {
        if ($status == 'filter') {
            return Validator::make($data, [
                'tanggal_awal' => ['nullable', 'date'],
                'tanggal_akhir' => ['nullable', 'after:tanggal_awal'],
            ]);
        }
    }
}
