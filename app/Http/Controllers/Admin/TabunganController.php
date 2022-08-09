<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use App\Models\Config;
use App\Models\Tabungan;
use App\Models\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\RekapanPenarikanTabungan;

class TabunganController extends Controller
{
    public function __construct()
    {
        // Delete All Tmp When Go To This Controller
        $this->middleware(function ($request, $next) {
            DB::transaction(function () {
                Session::forget('tmp_rekapan_sampah');
                Session::forget('tmp_tagihan_iuran');
                Session::forget('tmp_rekapan_harian');
            });
            $this->removeSessionChangeProfil();
            return $next($request);
        });
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        Session::forget('tmp_tabungan');
        $all_user = User::where('role', 4)->orderBy('role', 'ASC')->get();
        // Log Activity
        LogActivity::create([
            'ip_address' => request()->ip(),

            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'TabunganController.php',
            'action' => 'Halaman Awal Tabungan',
        ]);
        // End Log
        return view('admin.page.tabungan.kasir', ['user' => $all_user]);
    }

    public function tambah(Request $request)
    {
        $request->validate([
            'user' => ['required'],
        ]);
        DB::beginTransaction();
        try {
            Session::forget('tmp_tabungan');
            Session::put('tmp_tabungan', [
                'id' => Uuid::uuid4(),
                'user_id' => $request->user,
                'no_penarikan' => 'TT-' . date('Ymd') . '-' . strtoupper(\Str::random(6)),
            ]);
            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'TabunganController.php',
                'action' => 'Start Session Kasir Tabungan',
            ]);
            // End Log
            DB::commit();
            return redirect(route('tabungan.kasir'));
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Ditambahkan');
        }
    }

    public function kasir()
    {
        DB::beginTransaction();
        try {
            $min_penarikan = Config::where('key', 'sisa-tabungan')->firstOrFail();
            $find_first = Session::get('tmp_tabungan');
            if (! empty($find_first) && $find_first != null) {
                $user = User::find($find_first['user_id']);
                $find_tabungan = Tabungan::where('user_id', $find_first['user_id'])->first();
                if (empty($find_tabungan) && $find_tabungan == null) {
                    $find_tabungan = Tabungan::create([
                        'id' => Uuid::uuid4(),
                        'user_id' => $find_first->user->id,
                        'debet' => 0,
                        'kredit' => 0,
                        'saldo' => 0,
                    ]);
                }
                DB::commit();
                return view('admin.page.tabungan.penarikan-tabungan', ['min_penarikan' => $min_penarikan, 'penarikan' => $find_first, 'tabungan' => $find_tabungan, 'user' => $user]);
            }
            DB::rollback();
            return redirect(route('tabungan'))->with('error', 'Data Gagal Ditambahkan');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Ditambahkan');
        }
    }

    public function aksi_kasir(Request $request)
    {
        DB::beginTransaction();
        try {
            $tmp_penarikan = Session::get('tmp_tabungan');
            $count_penarikan = count($tmp_penarikan);
            if ($count_penarikan > 0) {
                $find = Tabungan::where('user_id', $tmp_penarikan['user_id'])->first();
                if ($find->saldo - $request->tarik >= 0) {
                    // Query Untuk Mencegah Transaksi Yang Sama di waktu yang sama
                    $find_rekapan = RekapanPenarikanTabungan::where('user_id', $tmp_penarikan['user_id'])->orderBy('created_at', 'DESC')->first();
                    if (! empty($find_rekapan) && \Carbon\Carbon::parse($find_rekapan->created_at)->addMinute(2)->format('Y-m-d H:i:s') >= \Carbon\Carbon::now()->format('Y-m-d H:i:s')) {
                        DB::rollback();
                        return redirect(route('tabungan'))->with('error', 'Silahkan Tunggu 2 Menit Untuk Melakukan Penarikan Tabungan Untuk Nasabah Ini Lagi');
                    }

                    $rekapan = RekapanPenarikanTabungan::create([
                        'id' => $tmp_penarikan['id'],
                        'user_id' => $tmp_penarikan['user_id'],
                        'no_penarikan' => $tmp_penarikan['no_penarikan'],
                        'total_penarikan' => $request->tarik,
                        'created_by' => Auth::user()->name,
                        'updated_by' => Auth::user()->name,
                    ]);

                    // Update Tabungan User
                    $find->kredit = $find->kredit + $request->tarik;
                    $find->saldo = $find->saldo - $request->tarik;
                    $find->save();

                    // Send To User When Finish Transaction
                    $send_user_notif = User::where('id', $tmp_penarikan['user_id'])->first();
                    $message = Controller::message_penarikan($send_user_notif->name, $rekapan->created_at, $request->tarik, $find->saldo);
                    // Email
                    if ($send_user_notif->no_telp != '') {
                        Controller::sendMessage($send_user_notif->no_telp, $message);
                        Controller::email_penarikan($rekapan->created_at, $request->tarik, $find->saldo, $send_user_notif->email, $send_user_notif->name);
                    } else {
                        Controller::email_penarikan($rekapan->created_at, $request->tarik, $find->saldo, $send_user_notif->email, $send_user_notif->name);
                    }

                    // Send Notif For Website
                    $pesan_notif = Controller::notif_penarikan($send_user_notif->name, $rekapan->created_at, $request->tarik, $find->saldo);
                    $this->storeNotification($send_user_notif->id, 'tarik', 'Penarikan Tabungan Nasabah', $pesan_notif);

                    // End Send Notif When Finish Transaction
                    Session::forget('tmp_tabungan');

                    // Log Activity
                    LogActivity::create([
                        'ip_address' => request()->ip(),
                        'user_id' => Auth::user()->id,
                        'previous_url' => URL::previous(),
                        'current_url' => URL::current(),
                        'file' => 'TabunganController.php',
                        'action' => 'Simpan Transaksi Tabungan',
                    ]);
                    // End Log
                    DB::commit();
                    return redirect(route('tabungan.cetak', [$rekapan->id]))->with('success', 'Penarikan Tabungan Berhasil Dilakukan');
                }
                DB::rollback();
                return redirect()->back()->with('error', 'Saldo Tabungan Tidak Mencukupi');
            }
            DB::rollback();
            return redirect(route('tabungan'));
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Disimpan');
        }
    }

    public function show_cetak(RekapanPenarikanTabungan $penarikan)
    {
        DB::beginTransaction();
        try {
            if ($penarikan->count() > 0) {
                return view('admin.page.tabungan.show-cetak', ['rekapan' => $penarikan]);
            }
            DB::commit();
            return redirect(route('penyetoran'));
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Disimpan');
        }
    }

    public function cetak_pdf(RekapanPenarikanTabungan $penarikan)
    {
        DB::beginTransaction();
        try {
            $tabungan = Tabungan::where('user_id', $penarikan->user_id)->first();
            $pdf = \PDF::loadView('pdf.cetak-penarikan', ['title' => 'Penarikan Tabungan', 'rekapan' => $penarikan, 'tabungan' => $tabungan])->setPaper('a4', 'landscape');
            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'TabunganController.php',
                'action' => 'Cetak PDF Tabungan',
            ]);
            // End Log
            DB::commit();
            return  $pdf->download('Penarikan -' . ($penarikan->no_penarikan) . '.pdf');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Dicetak');
        }
    }
}
