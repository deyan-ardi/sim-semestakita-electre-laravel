<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use App\Models\Config;
use App\Models\LogActivity;
use App\Models\RekapanIuran;
use App\Models\TagihanIuran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class KasirIuranController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            DB::transaction(function () {
                Session::forget('tmp_rekapan_sampah');
                Session::forget('tmp_tabungan');
                Session::forget('tmp_rekapan_harian');
            });
            $this->removeSessionChangeProfil();
            return $next($request);
        });
    }
    public function index()
    {
        Session::forget('tmp_tagihan_iuran');
        Session::forget('tmp_detail_tagihan_iuran');
        $user = User::where('status_iuran', 1)->get();
        // Log Activity

        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'KasirIuranController.php',
            'action' => 'Halaman Index Kasir Iuran Nasabah',
        ]);
        // End Log
        return view('admin.page.iuran.kasir', ['page' => 2, 'fitur' => 3, 'user' => $user]);
    }

    public function tambah(Request $request)
    {
        $request->validate([
            'user' => ['required', 'string'],
        ]);
        DB::beginTransaction();
        try {
            Session::forget('tmp_tagihan_iuran');
            Session::forget('tmp_detail_tagihan_iuran');
            Session::put('tmp_tagihan_iuran', [
                'id' => Uuid::uuid4(),
                'user_id' => $request->user,
            ]);
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'KasirIuranController.php',
                'action' => 'Memulai Session Kasir Iuran',
            ]);
            DB::commit();
            return redirect(route('iuran.kasir.pembayaran'));
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Ditambahkan');
        }
    }

    public function pembayaran()
    {
        DB::beginTransaction();
        try {
            $find_first = Session::get('tmp_tagihan_iuran');
            $denda = Config::where('key', 'denda')->firstOrfail();
            if (! empty($find_first) || $find_first != null) {
                $find_user = User::where('id', $find_first['user_id'])->first();
                $daftar_tagihan = TagihanIuran::where('user_id', $find_user->id)->where('status', '!=', 'PAID')->get();
                return view('admin.page.iuran.pembayaran', ['denda' => $denda, 'page' => 2, 'fitur' => 3, 'tagihan' => $daftar_tagihan, 'pembayaran' => $find_first, 'user' => $find_user]);
            }
            DB::commit();
            return redirect(route('iuran.kasir'));
        } catch (Exception $e) {
            DB::rollback();
            dd($e);
            return redirect()->back()->with('error', 'Data Gagal Ditambahkan');
        }
    }

    public function getAllData()
    {
        DB::beginTransaction();
        try {
            $query = Session::get('tmp_detail_tagihan_iuran');
            if (empty($query) || $query == null) {
                $query = [];
            } else {
                $query = $query;
            }
            DB::commit();
            return response()->json(['success' => true, 'data' => $query]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => true, 'error' => $e]);
        }
    }

    public function tambah_keranjang(Request $request)
    {
        DB::beginTransaction();
        try {
            $all = Session::get('tmp_detail_tagihan_iuran');
            $status = true;
            $tagihan = TagihanIuran::where('id', $request->tagihan)->first();
            if (! empty($all) || $all != null) {
                foreach ($all as $all) {
                    if ($all['tagihan_iuran_id'] == $tagihan->id) {
                        $status = false;
                        break;
                    }
                }
            } else {
                Session::put('tmp_detail_tagihan_iuran', []);
                $status = true;
            }

            // Get Tagihan
            if ($status) {
                Session::push('tmp_detail_tagihan_iuran', [
                    'id' => Uuid::uuid4(),
                    'tagihan_iuran_id' => $tagihan->id,
                    'no_tagihan' => $tagihan->no_tagihan,
                    'tanggal' => $tagihan->tanggal,
                    'total_tagihan' => $tagihan->total_tagihan,
                    'status' => $tagihan->status,
                    'sub_total' => $tagihan->sub_total,
                    'sub_total_denda' => $tagihan->sub_total_denda,
                    'deskripsi' => $tagihan->deskripsi,
                    'no_pembayaran' => 'TI-' . date('Ymd') . '-' . strtoupper(\Str::random(6)),
                ]);
                DB::commit();
                return response()->json(['success' => true]);
            }
            DB::rollback();
            return response()->json(['error' => true, 'message' => 'Tagihan Iuran Sudah Ada Di Keranjang Checkout']);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => true, 'message' => 'Data Gagal Ditambahkan']);
        }
    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {
            $find = Session::pull('tmp_detail_tagihan_iuran');
            if (! empty($find) || $find != null) {
                foreach ($find as $key => $value) {
                    if ($value['id'] == $request->id) {
                        unset($find[$key]);
                    }
                }
                Session::put('tmp_detail_tagihan_iuran', $find);
            }
            DB::commit();
            return response()->json(['success' => true]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => true, 'message' => 'Data Gagal Dihapus']);
        }
    }

    public function show_cetak($array)
    {
        $data = json_decode(base64_decode($array));
        $find = RekapanIuran::whereIn('id', $data)->get();
        return view('admin.page.iuran.show-cetak', ['pembayaran' => $find]);
    }
    public function tambah_pembayaran(Request $request)
    {
        DB::beginTransaction();
        try {
            $tmp_detail_iuran = Session::pull('tmp_detail_tagihan_iuran');
            $tmp_iuran = Session::pull('tmp_tagihan_iuran');
            $count_detail = count($tmp_detail_iuran);
            $count_iuran = count($tmp_iuran);
            if ($count_detail > 0 && $count_iuran > 0) {
                // Add to tabel rekap iuran
                $total = 0;
                foreach ($tmp_detail_iuran as $data) {
                    $total = $total + $data['total_tagihan'];
                }
                if ($request->bayar - $total >= 0) {
                    $id_bayar = [];
                    foreach ($tmp_detail_iuran as $data) {
                        if ($data['sub_total_denda'] > 0) {
                            $status_denda = 'DENDA';
                        } else {
                            $status_denda = 'TIDAK DENDA';
                        }
                        $check = RekapanIuran::where('no_tagihan', $data['no_tagihan'])->where('user_id', $tmp_iuran['user_id'])->get();
                        if ($check->count() > 0) {
                            DB::rollback();
                            return redirect(route('iuran.kasir'))->with('error', 'Terdapat Tagihan Yang Sudah Terbayar, Transaksi Gagal');
                        }

                        $rekapan = RekapanIuran::create([
                            'id' => $data['id'],
                            'tanggal' => $data['tanggal'],
                            'user_id' => $tmp_iuran['user_id'],
                            'no_tagihan' => $data['no_tagihan'],
                            'no_pembayaran' => $data['no_pembayaran'],
                            'deskripsi' => $data['deskripsi'] . ' Bulan ' . \Carbon\Carbon::parse($data['tanggal'])->format('F Y'),
                            'sub_total' => $data['sub_total'],
                            'sub_total_denda' => $data['sub_total_denda'],
                            'status_denda' => $status_denda,
                            'total_tagihan' => $data['total_tagihan'],
                        ]);

                        // Push to array
                        array_push($id_bayar, $rekapan->id);

                        // Update status tagihan iuran
                        $find = TagihanIuran::find($data['tagihan_iuran_id']);
                        $find->status = 'Paid';
                        $find->save();

                        // Send To User When Finish Transaction
                        $send_user_notif = User::where('id', $tmp_iuran['user_id'])->first();
                        $message = Controller::message_tagihan($send_user_notif->name, $data['total_tagihan'], $find->tanggal, $rekapan->created_at);
                        // Send Email or Whatsapp
                        if ($send_user_notif->no_telp != '') {
                            Controller::sendMessage($send_user_notif->no_telp, $message);
                            Controller::email_tagihan($data['total_tagihan'], $find->tanggal, $rekapan->created_at, $send_user_notif->email, $send_user_notif->name);
                        } else {
                            Controller::email_tagihan($data['total_tagihan'], $find->tanggal, $rekapan->created_at, $send_user_notif->email, $send_user_notif->name);
                        }

                        // Send Notif For Website
                        $notif = Controller::notif_tagihan($send_user_notif->name, $data['total_tagihan'], $find->tanggal, $rekapan->created_at);
                        $this->storeNotification($tmp_iuran['user_id'], 'iuran', 'Pembayaran Tagihan Iuran', $notif);
                    }
                    $arr_id = base64_encode(json_encode($id_bayar));
                    LogActivity::create([
                        'ip_address' => request()->ip(),
                        'user_id' => Auth::user()->id,
                        'target_user' => $send_user_notif->name,
                        'previous_url' => URL::previous(),
                        'current_url' => URL::current(),
                        'file' => 'KasirIuranController.php',
                        'action' => 'Submit Pembayaran Iuran Nasabah',
                    ]);
                    // End Send Notif When Finish Transaction
                    Session::forget('tmp_detail_tagihan_iuran');
                    Session::forget('tmp_tagihan_iuran');
                    DB::commit();
                    return redirect(route('iuran.kasir.cetak', $arr_id))->with('success', 'Pembayaran Tagihan Berhasil Ditambahkan');
                }
                DB::rollback();
                return redirect()->back()->with('error', 'Nominal Bayar Kurang Dari Total Wajib Bayar');
            }
            DB::rollback();
            return redirect()->back()->with('error', 'Data Sampah Masih Kosong');
        } catch (Exception $e) {
            DB::rollback();
            dd($e);
            return redirect()->back()->with('error', 'Terjadi Kesalahan Pada Saat Melakukan Transaksi');
        }
    }

    public function cetak_pdf($array)
    {
        DB::beginTransaction();
        try {
            $data = json_decode(base64_decode($array));
            $find = RekapanIuran::whereIn('id', $data)->get();
            $pdf = \PDF::loadView('pdf.cetak-pembayaran', ['title' => 'Pembayaran Tagihan Iuran', 'rekapan' => $find])->setPaper('a4', 'landscape');
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'KasirIuranController.php',
                'action' => 'Cetak Bukti Pembayaran Iuran',
            ]);
            DB::commit();
            return $pdf->download('Bukti Pembayaran Iuran.pdf');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Dicetak');
        }
    }
}
