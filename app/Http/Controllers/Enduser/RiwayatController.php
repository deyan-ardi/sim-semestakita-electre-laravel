<?php

namespace App\Http\Controllers\Enduser;

use Carbon\Carbon;
use App\Models\RekapanIuran;
use App\Models\TagihanIuran;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RiwayatController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $rekapan_iuran = RekapanIuran::where('user_id', $user->id)->whereDate('created_at', Carbon::now()->format('Y-m-d'))->get();
        return view('enduser.page.riwayat.index', ['rekapan_iuran' => $rekapan_iuran, 'user' => $user]);
    }

    public function filter(Request $request)
    {
        $user = Auth::user();
        $validator = $this->_validator($request->all(), 'filter');
        if ($validator->fails()) {
            $validator->validate();
            return redirect(route('enduser.riwayat.index'))->with('error', 'Gagal Memfilter Data, Periksa Inputan Anda');
        }

        if (empty($request->tanggal_awal) || empty($request->tanggal_akhir)) {
            return redirect(route('enduser.riwayat.index'));
        }
        $start = Carbon::createFromFormat('Y-m-d', $request->tanggal_awal)->startOfDay()->toDateTimeString();
        $end = Carbon::createFromFormat('Y-m-d', $request->tanggal_akhir)->endOfDay()->toDateTimeString();

        $rekapan_iuran = RekapanIuran::where('user_id', $user->id)->where('created_at', '>=', $start)->where('created_at', '<=', $end)->get();

        return view('enduser.page.riwayat.index', ['rekapan_iuran' => $rekapan_iuran, 'user' => $user]);
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

    public function detail($id)
    {
        $user = Auth::user();
        if ($user->status_iuran == 0 || $user->status_iuran == null) {
            return redirect(route('enduser.dashboard'))->with('error', 'Anda Tidak Aktif Membayar Iuran');
        }
        $pembayaran = RekapanIuran::where('id', $id)->firstOrFail();
        $tagihan = TagihanIuran::where('no_tagihan', $pembayaran->no_tagihan)->firstOrFail();
        return view('enduser.page.riwayat.detail', ['tagihan' => $tagihan, 'user' => $user, 'pembayaran' => $pembayaran]);
    }
}
