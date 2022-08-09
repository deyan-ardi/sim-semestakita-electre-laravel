<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\LogActivity;
use App\Models\TagihanIuran;
use Illuminate\Http\Request;
use App\Models\PembayaranRutin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DaftarTagihanController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->removeSessionTmpAll();
            $this->removeSessionChangeProfil();
            return $next($request);
        });
    }

    public function index()
    {
        $tagihan = TagihanIuran::all();
        $daftar = PembayaranRutin::all();
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'DaftarTagihanController.php',
            'action' => 'Halaman Index Daftar Tagihan',
        ]);
        return view('admin.page.tagihan.index', ['tagihan' => $tagihan, 'pembayaran' => $daftar]);
    }

    public function filter(Request $request)
    {
        $request->validate([
            'tanggal_awal' => ['nullable', 'date'],
            'tanggal_akhir' => ['nullable', 'after:tanggal_awal'],
            'status' => ['nullable', 'string'],
        ]);
        $status = strtoupper($request->status);
        if (empty($request->tanggal_awal) || empty($request->tanggal_akhir)) {
            if ($status == 'SEMUA') {
                return redirect(route('tagihan'));
            }
            $tagihan = TagihanIuran::where('status', $status)->get();
        } else {
            if ($status == 'SEMUA') {
                $tagihan = TagihanIuran::where('tanggal', '>=', $request->tanggal_awal)->where('tanggal', '<=', $request->tanggal_akhir)->get();
            } else {
                $tagihan = TagihanIuran::where('tanggal', '>=', $request->tanggal_awal)->where('tanggal', '<=', $request->tanggal_akhir)->where('status', $status)->get();
            }
        }
        $daftar = PembayaranRutin::all();
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'DaftarTagihanController.php',
            'action' => 'Filter Data',
        ]);
        return view('admin.page.tagihan.index', ['tagihan' => $tagihan, 'pembayaran' => $daftar]);
    }

    public function regenerate(Request $request)
    {
        $request->validate([
            'bulan' => ['required', 'date_format:m'],
            'tagihan' => ['required'],
        ]);
        DB::beginTransaction();
        try {
            $pembayaranRutin = PembayaranRutin::findOrFail($request->tagihan);
            $daftarTagihanDigenerateUlang = TagihanIuran::selectRaw('tagihan_iuran.*')
                ->whereMonth('tanggal', $request->bulan)
                ->join('users', 'users.id', 'tagihan_iuran.user_id')
                ->where('tagihan_iuran.status', '!=', 'PAID')
                ->where('users.pembayaran_rutin_id', $request->tagihan)
                ->where('users.status_iuran', 1)
                ->where('users.pembayaran_rutin_id', '!=', null)
                ->get();
            if ($daftarTagihanDigenerateUlang->count() > 0) {
                $arr_tagihan = [];
                foreach ($daftarTagihanDigenerateUlang as $tagihan) {
                    $date = strtotime("+$pembayaranRutin->durasi_pembayaran day");
                    $dateDue = date('Y-m-d', $date);
                    $tagihan->tanggal = date('Y-m-d');
                    $tagihan->user_id = $tagihan->user_id;
                    $tagihan->deskripsi = $pembayaranRutin->nama_pembayaran;
                    $tagihan->due_date = $dateDue;
                    $tagihan->status = 'Unpaid';
                    $tagihan->total_tagihan = $pembayaranRutin->total_biaya;
                    $tagihan->save();
                    array_push($arr_tagihan, $tagihan->user->no_member . '-' . $tagihan->user->name);
                    // sending email
                    Controller::email_tagihan_baru($tagihan->user->email, $tagihan->deskripsi, $tagihan->tanggal, $tagihan->total_tagihan, $tagihan->user->name);
                }
                LogActivity::create([
                    'ip_address' => request()->ip(),
                    'target_user' => json_encode($arr_tagihan),
                    'user_id' => Auth::user()->id,
                    'previous_url' => URL::previous(),
                    'current_url' => URL::current(),
                    'file' => 'DaftarTagihanController.php',
                    'action' => 'Regenerate Tagihan Overdue dan Unpaid',
                ]);
                DB::commit();
                return redirect()->back()->with('success', 'Tagihan Iuran Berhasil Di Regenerate');
            }
            DB::rollback();
            return redirect(route('tagihan'))->with('error', 'Gagal Di Regeneratem Tidak Ada Tagihan Pada Bulan Tersebut');
        } catch (Exception $e) {
            dd($e);
            DB::rollback();
            return redirect()->back()->with('error', 'Tagihan Iuran Gagal Di Regenerate');
        }
    }
}
