<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use App\Models\Kategori;
use App\Models\Tabungan;
use App\Models\LogActivity;
use Illuminate\Http\Request;
use App\Models\RekapanSampah;
use Illuminate\Support\Facades\DB;
use App\Models\DetailRekapanSampah;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PenyetoranController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            DB::transaction(function () {
                Session::forget('tmp_tagihan_iuran');
                Session::forget('tmp_tabungan');
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
        Session::forget('tmp_rekapan_sampah');
        Session::forget('tmp_detail_rekapan_sampah');
        $all_user = User::where('role', 4)->orderBy('role', 'ASC')->get();
        // Log Activity
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'PenyetoranController.php',
            'action' => 'Halaman Index Penyetoran',
        ]);
        // End Log
        return view('admin.page.penyetoran.penyetoran', ['user' => $all_user]);
    }

    public function tambah(Request $request)
    {
        $request->validate([
            'user' => ['required'],
        ]);
        DB::beginTransaction();
        try {
            Session::forget('tmp_rekapan_sampah');
            Session::forget('tmp_detail_rekapan_sampah');
            Session::put('tmp_rekapan_sampah', [
                'id' => Uuid::uuid4(),
                'user_id' => $request->user,
                'kode_transaksi' => 'TS-' . date('Ymd') . '-' . strtoupper(\Str::random(6)),
            ]);
            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'PenyetoranController.php',
                'action' => 'Memulai Session Kasir Penyetoran',
            ]);
            // End Log
            DB::commit();
            return redirect(route('penyetoran.kasir'));
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Ditambahkan');
        }
    }

    public function kasir()
    {
        DB::beginTransaction();
        try {
            $find_first = Session::get('tmp_rekapan_sampah');
            if (! empty($find_first) || $find_first != null) {
                $kategori = Kategori::all();
                $user = User::where('id', $find_first['user_id'])->first();
                return view('admin.page.penyetoran.tambah-penyetoran', ['penyetoran' => $find_first, 'user' => $user, 'kategori' => $kategori]);
            }
            DB::commit();
            return redirect(route('penyetoran'));
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Ditambahkan');
        }
    }

    public function tambah_sampah(Request $request)
    {
        DB::beginTransaction();
        try {
            $all = Session::get('tmp_detail_rekapan_sampah');
            $status = true;
            $cari_kategori = Kategori::find($request->kategori);
            if (! empty($all) || $all != null) {
                foreach ($all as $all) {
                    if ($all['kategori_id'] == $cari_kategori->id) {
                        $status = false;
                        break;
                    }
                }
            } else {
                Session::put('tmp_detail_rekapan_sampah', []);
                $status = true;
            }

            if ($request->total == 0) {
                return response()->json(['error' => true, 'message' => 'Jumlah Sampah Tidak Dapat 0']);
            }

            if ($status) {
                Session::push('tmp_detail_rekapan_sampah', [
                    'id' => Uuid::uuid4(),
                    'kategori_id' => $cari_kategori->id,
                    'nama_kategori' => $cari_kategori->nama_kategori,
                    'jenis_sampah' => $cari_kategori->jenis_sampah,
                    'harga_beli' => $cari_kategori->harga_beli,
                    'jumlah_sampah' => $request->total,
                    'sub_total' => $cari_kategori->harga_beli * $request->total,
                ]);
                DB::commit();
                return response()->json(['success' => true]);
            }

            DB::rollback();
            return response()->json(['error' => true, 'message' => 'Kategori Sampah Sudah Ada Di Keranjang Checkout']);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => true, 'message' => 'Data Gagal Ditambahkan']);
        }
    }

    public function hapus_sampah(Request $request)
    {
        DB::beginTransaction();
        try {
            $find = Session::pull('tmp_detail_rekapan_sampah');
            if (! empty($find) || $find != null) {
                foreach ($find as $key => $value) {
                    if ($value['id'] == $request->id) {
                        unset($find[$key]);
                    }
                }
                Session::put('tmp_detail_rekapan_sampah', $find);
            }
            DB::commit();
            return response()->json(['success' => true]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => true]);
        }
    }
    public function getAllData()
    {
        DB::beginTransaction();
        try {
            $query = Session::get('tmp_detail_rekapan_sampah');
            if (empty($query) || $query == null) {
                $query = [];
            } else {
                $query = $query;
            }
            DB::commit();
            return response()->json(['success' => true, 'data' => $query]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => true]);
        }
    }

    public function aksi_kasir()
    {
        DB::beginTransaction();
        try {
            $tmp_detail_rekapan = Session::pull('tmp_detail_rekapan_sampah');
            $tmp_rekapan = Session::pull('tmp_rekapan_sampah');
            $count_detail = count($tmp_detail_rekapan);
            $count_rekapan = count($tmp_rekapan);
            if ($count_detail > 0 && $count_rekapan > 0) {
                $total_sampah = 0;
                $total_beli = 0;
                $find_rekapan = RekapanSampah::where('user_id', $tmp_rekapan['user_id'])->orderBy('created_at', 'DESC')->first();
                if (! empty($find_rekapan) && \Carbon\Carbon::parse($find_rekapan->created_at)->addMinute(2)->format('Y-m-d H:i:s') >= \Carbon\Carbon::now()->format('Y-m-d H:i:s')) {
                    DB::rollback();
                    return redirect(route('penyetoran'))->with('error', 'Silahkan Tunggu 2 Menit Untuk Menambahkan Penyetoran Untuk Nasabah Ini Lagi');
                }
                // Add to table rekapan sampah
                $rekapan = RekapanSampah::create([
                    'id' => $tmp_rekapan['id'],
                    'user_id' => $tmp_rekapan['user_id'],
                    'kode_transaksi' => $tmp_rekapan['kode_transaksi'],
                    // 'is_nasabah' => $penyetoran->user->role == 4 ? 'Y' : 'N',
                    'total_sampah' => $total_sampah,
                    'total_beli' => $total_beli,
                    'created_by' => Auth::user()->name,
                    'updated_by' => Auth::user()->name,
                ]);
                // Add Detail
                foreach ($tmp_detail_rekapan as $sampah) {
                    $total_sampah = $total_sampah + $sampah['jumlah_sampah'];
                    $total_beli = $total_beli + $sampah['sub_total'];
                    DetailRekapanSampah::create([
                        'nama_kategori' => $sampah['nama_kategori'],
                        'jenis_sampah' => $sampah['jenis_sampah'],
                        'harga_kategori' => $sampah['harga_beli'],
                        'rekapan_sampah_id' => $tmp_rekapan['id'],
                        'jumlah_sampah' => $sampah['jumlah_sampah'],
                        'sub_total' => $sampah['sub_total'],
                    ]);

                    // Update Total Sampah di TPS berdasarkan kategori
                    $find_kategori = Kategori::find($sampah['kategori_id']);
                    $find_kategori->total_sampah = $find_kategori->total_sampah + $sampah['jumlah_sampah'];
                    $find_kategori->save();
                }

                // Update Rekapan Untuk Total Sampah dan Total Beli
                $rekapan->total_sampah = $total_sampah;
                $rekapan->total_beli = $total_beli;
                $rekapan->save();

                // Updated tabungan user
                $tabungan = Tabungan::where('user_id', $tmp_rekapan['user_id'])->first();
                if (! empty($tabungan)) {
                    $tabungan->debet = $tabungan->debet + $total_beli;
                    $tabungan->saldo = $tabungan->saldo + $total_beli;
                    $tabungan->save();
                } else {
                    $tabungan = Tabungan::create([
                        'id' => Uuid::uuid4(),
                        'user_id' => $tmp_rekapan['user_id'],
                        'debet' => $total_beli,
                        'kredit' => 0,
                        'saldo' => $total_beli,
                    ]);
                }

                // Send Whatsapp To User When Finish Transaction
                $send_user_notif = User::where('id', $tmp_rekapan['user_id'])->first();
                $message = Controller::message_penyetoran($send_user_notif->name, $rekapan->created_at, $total_beli, $tabungan->saldo);
                if ($send_user_notif->no_telp != '') {
                    Controller::sendMessage($send_user_notif->no_telp, $message);
                    Controller::email_penyetoran($rekapan->created_at, $total_beli, $tabungan->saldo, $send_user_notif->email, $send_user_notif->name);
                } else {
                    // Sending Email
                    Controller::email_penyetoran($rekapan->created_at, $total_beli, $tabungan->saldo, $send_user_notif->email, $send_user_notif->name);
                }

                // Send Notif For Website
                $pesan_notif = Controller::notif_penyetoran($rekapan->created_at, $send_user_notif->name, $total_beli, $tabungan->saldo);
                $this->storeNotification($send_user_notif->id, 'nabung', 'Nabung Sampah Nasabah', $pesan_notif);
                // End Send Notif When Finish Transaction

                // Remove Tmp
                Session::forget('tmp_rekapan_sampah');
                Session::forget('tmp_detail_rekapan_sampah');
                // Log Activity
                LogActivity::create([
                    'ip_address' => request()->ip(),
                    'user_id' => Auth::user()->id,
                    'previous_url' => URL::previous(),
                    'current_url' => URL::current(),
                    'file' => 'PenyetoranController.php',
                    'action' => 'Simpan Penyetoran',
                ]);
                // End Log

                DB::commit();
                return redirect(route('penyetoran.cetak', [$rekapan->id]))->with('success', 'Setoran Sampah Berhasil Ditambahkan');
            }
            DB::rollBack();
            return redirect()->back()->with('error', 'Data Sampah Masih Kosong');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Di Simpan');
        }
    }

    public function show_cetak(RekapanSampah $rekapan)
    {
        DB::beginTransaction();
        try {
            if ($rekapan->count() > 0) {
                DB::commit();
                return view('admin.page.penyetoran.show-cetak', ['rekapan' => $rekapan]);
            }
            DB::rollback();
            return redirect(route('penyetoran'));
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Di Simpan');
        }
    }
    public function cetak_pdf(RekapanSampah $rekapan)
    {
        DB::beginTransaction();
        try {
            $pdf = \PDF::loadView('pdf.cetak-setoran', ['title' => 'Penyetoran Sampah', 'rekapan' => $rekapan])->setPaper('a4', 'landscape');
            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'PenyetoranController.php',
                'action' => 'Cetak Penyetoran',
            ]);
            // End Log
            DB::commit();
            return  $pdf->download('Penyetoran -' . ($rekapan->kode_transaksi) . '.pdf');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Dicetak');
        }
    }
}
