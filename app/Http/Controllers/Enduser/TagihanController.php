<?php

namespace App\Http\Controllers\Enduser;

use App\Models\TagihanIuran;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TagihanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->status_iuran == 0 || $user->status_iuran == null) {
            return redirect(route('enduser.dashboard'))->with('error', 'Anda Tidak Aktif Membayar Iuran Sampah');
        }

        $tagihan = TagihanIuran::where('user_id', $user->id)->where('status', 'UNPAID')->orWhere('status', 'OVERDUE')->orderBy('created_at', 'DESC')->get();
        return view('enduser.page.tagihan.index', ['user' => $user, 'tagihan' => $tagihan]);
    }

    public function detail(Request $request)
    {
        $user = Auth::user();
        if ($user->status_iuran == 0 || $user->status_iuran == null) {
            return redirect(route('enduser.dashboard'))->with('error', 'Anda Tidak Aktif Membayar Iuran');
        }
        $tagihan = TagihanIuran::find($request->id);
        return view('enduser.page.tagihan.detail', ['user' => $user, 'tagihan' => $tagihan]);
    }

    public function filter(Request $request)
    {
        $user = Auth::user();
        $validator = $this->_validator($request->all(), 'filter');
        if ($validator->fails()) {
            $validator->validate();
            return redirect(route('enduser.tabungan.index'))->with('error', 'Gagal Memfilter Data, Periksa Inputan Anda');
        }

        $status = strtoupper($request->status);

        if (empty($request->tanggal_awal) || empty($request->tanggal_akhir)) {
            if ($status == 'SEMUA') {
                return redirect(route('enduser.tagihan.index'));
            }
            $tagihan = TagihanIuran::where('user_id', $user->id)->where('status', $status)->get();
        } else {
            if ($status == 'SEMUA') {
                $tagihan = TagihanIuran::where('user_id', $user->id)->where('tanggal', '>=', $request->tanggal_awal)->where('tanggal', '<=', $request->tanggal_akhir)->get();
            } else {
                $tagihan = TagihanIuran::where('user_id', $user->id)->where('tanggal', '>=', $request->tanggal_awal)->where('tanggal', '<=', $request->tanggal_akhir)->where('status', $status)->get();
            }
        }

        return view('enduser.page.tagihan.index', ['user' => $user, 'tagihan' => $tagihan]);
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
