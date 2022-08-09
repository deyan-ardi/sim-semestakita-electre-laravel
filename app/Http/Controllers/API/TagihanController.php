<?php

namespace App\Http\Controllers\API;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use App\Models\LogActivity;
use Illuminate\Support\Str;
use App\Models\RekapanIuran;
use App\Models\TagihanIuran;
use Illuminate\Http\Request;
use App\Models\TmpTagihanIuran;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\TmpDetailTagihanIuran;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TagihanController extends Controller
{
    public function scan($id)
    {
        //hanya kembalikan data nasabah yang aktif membayar iuran
        DB::beginTransaction();
        try {
            $user = User::where('id', $id)->where(function ($query) {
                $query->where('role', 4);
                $query->orWhere('role', 5);
            })->first();
            if (! $user) {
                return ResponseFormatter::error([
                    'success' => false,
                    'message' => 'Data tidak ditemukan',
                ], 'Not Found', 404);
            }
            if ($user->status_iuran != 1) {
                return ResponseFormatter::error([
                    'success' => false,
                    'message' => 'Status iuran tidak aktif',
                ], 'Not Found', 403);
            }
            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'target_user' => $user->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'API\TagihanController.php',
                'action' => 'Mengambil Data Nasabah Berdasarkan Hasil Scan QR',
            ]);
            // End Log
            DB::commit();
            return ResponseFormatter::success(
                $user,
                'Data nasabah didapat'
            );
        } catch (Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'success' => false,
                'message' => $e,
            ], 'Internal Server Error', 500);
        }
    }

    public function allTagihan($id)
    {
        DB::beginTransaction();
        try {
            //yang ditampilkan adalah unpaid dan overdue
            $tagihan = TagihanIuran::where('user_id', $id)->orderBy('created_at', 'DESC')->where('status', '!=', 'PAID');
            if ($tagihan->count() == 0) {
                return ResponseFormatter::error([
                    'success' => false,
                    'message' => 'Data tidak ditemukan',
                ], 'Not Found', 404);
            }
            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'target_user' => $id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'API\TagihanController.php',
                'action' => 'Mengambil Data Tagihan Dengan Status Bukan Paid',
            ]);
            // End Log
            DB::commit();
            return ResponseFormatter::success(
                $tagihan->get(),
                'Data tagihan didapat'
            );
        } catch (Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'success' => false,
                'message' => $e,
            ], 'Internal Server Error', 500);
        }
    }

    public function storeTmpTagihanIuran(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => ['required'],
        ]);
        if ($validator->fails()) {
            $validator->validate();
            return ResponseFormatter::error([
                'success' => false,
                'message' => 'Data tidak valid',
            ], 'Bad Request', 400);
        }
        DB::beginTransaction();
        try {
            //untuk menghapus data tmp tagihan iuran yang lama sesuai id pegawai
            $rekap = TmpTagihanIuran::where('petugas_id', Auth::user()->id)->get();
            foreach ($rekap as $rekap) {
                $rekap->delete();
            }
            $tmp =  TmpTagihanIuran::create([
                'id' => Uuid::uuid4(),
                'user_id' => $request->user_id,
                'petugas_id' => Auth::user()->id,
            ]);

            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'API\TagihanController.php',
                'action' => 'Menambahkan Tmp Tagihan Iuran Melalui API',
            ]);
            // End Log
            DB::commit();
            return ResponseFormatter::success(
                $tmp,
                'Data Tagihan Iuran Disimpan'
            );
        } catch (Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'success' => false,
                'message' => $e,
            ], 'Internal Server Error', 500);
        }
    }

    public function storeTmpDetailTagihanIuran(Request $request)
    {
        DB::beginTransaction();
        try {
            $all = TmpDetailTagihanIuran::where('tmp_tagihan_iuran_id', $request->tmp_tagihan_iuran_id)->get();
            $status = true;
            foreach ($all as $all) {
                //tagihan iuran id didapat dari tabel tagihan iuran
                //jika data sudah ada di keranjang maka return false
                if ($all->tagihan_iuran_id == $request->tagihan_iuran_id) {
                    $status = false;
                    break;
                }
            }
            if ($status) {
                $query = TmpDetailTagihanIuran::create([
                    'id' => Uuid::uuid4(),
                    'tagihan_iuran_id' => $request->tagihan_iuran_id,
                    'tmp_tagihan_iuran_id' => $request->tmp_tagihan_iuran_id,
                    'no_pembayaran' => 'TI-' . date('Ymd') . '-' . strtoupper(Str::random(6)),
                ]);
                // Log Activity
                LogActivity::create([
                    'ip_address' => request()->ip(),
                    'user_id' => Auth::user()->id,
                    'previous_url' => URL::previous(),
                    'current_url' => URL::current(),
                    'file' => 'API\TagihanController.php',
                    'action' => 'Menambahkan Tmp Detail Tagihan Iuran Melalui API',
                ]);
            // End Log
            } else {
                $query = false;
            }

            if ($query) {
                $getInputData = TmpDetailTagihanIuran::with('tagihan_iuran')->where('id', $query->id)->first();
                DB::commit();
                return ResponseFormatter::success(
                    $getInputData,
                    'Data Detail Tagihan Iuran Disimpan'
                );
            }
            DB::rollback();
            return ResponseFormatter::error([
                'success' => false,
                'message' => 'Tagihan Iuran Sudah Ada Di Keranjang Checkout',
            ], 'Bad Request', 400);
        } catch (Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'success' => false,
                'message' => $e,
            ], 'Internal Server Error', 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $find = TmpDetailTagihanIuran::find($id);
            if ($find) {
                // Log Activity
                $find->delete();
                LogActivity::create([
                    'ip_address' => request()->ip(),
                    'user_id' => Auth::user()->id,
                    'target_user' => $id,
                    'previous_url' => URL::previous(),
                    'current_url' => URL::current(),
                    'file' => 'API\TagihanController.php',
                    'action' => 'Menghapus Tmp Detail Tagihan Iuran Melalui API',
                ]);
                // End Log
                return ResponseFormatter::success(
                    'Data Detail Tagihan Iuran Dihapus'
                );
            }
            DB::commit();
            return ResponseFormatter::error([
                'success' => false,
                'message' => 'Data Detail Tagihan Iuran Gagal Dihapus',
            ], 'Bad Request', 400);
        } catch (Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'success' => false,
                'message' => $e,
            ], 'Internal Server Error', 500);
        }
    }

    public function checkout(Request $request)
    {
        DB::beginTransaction();
        try {
            $iuran = TmpTagihanIuran::with(['tmp_detail_tagihan_iuran', 'user'])->where('id', $request->tmp_tagihan_iuran_id)->first();
            // dd($iuran->tmp_detail_tagihan_iuran->count());
            if ($iuran->tmp_detail_tagihan_iuran->count() > 0) {
                // Add to tabel rekap iuran
                $total = 0;
                foreach ($iuran->tmp_detail_tagihan_iuran as $data) {
                    $total = $total + $data->tagihan_iuran->total_tagihan;
                }
                if ($request->bayar - $total >= 0) {
                    foreach ($iuran->tmp_detail_tagihan_iuran as $data) {
                        if ($data->tagihan_iuran->sub_total_denda > 0) {
                            $status_denda = 'DENDA';
                        } else {
                            $status_denda = 'TIDAK DENDA';
                        }
                        $rekapan = RekapanIuran::create([
                            'id' => $data->id,
                            'tanggal' => $data->tagihan_iuran->tanggal,
                            'user_id' => $iuran->user_id,
                            'no_tagihan' => $data->tagihan_iuran->no_tagihan,
                            'no_pembayaran' => $data->no_pembayaran,
                            'deskripsi' => $data->tagihan_iuran->deskripsi . ' Bulan ' . \Carbon\Carbon::parse($data->tagihan_iuran->tanggal)->format('F Y'),
                            'sub_total' => $data->tagihan_iuran->sub_total,
                            'sub_total_denda' => $data->tagihan_iuran->sub_total_denda,
                            'status_denda' => $status_denda,
                            'total_tagihan' => $data->tagihan_iuran->total_tagihan,
                        ]);

                        // Update status tagihan iuran
                        $find = TagihanIuran::find($data->tagihan_iuran_id);
                        $find->status = 'Paid';
                        $find->save();

                        // Notification
                        $send_user_notif = User::where('id', $iuran->user_id)->first();
                        $message = Controller::message_tagihan($send_user_notif->name, $data->tagihan_iuran->total_tagihan, $find->tanggal, $rekapan->created_at);

                        // Send Email or Whatsapp
                        if ($send_user_notif->no_telp != '') {
                            Controller::sendMessage($send_user_notif->no_telp, $message);
                            Controller::email_tagihan($data->tagihan_iuran->total_tagihan, $find->tanggal, $rekapan->created_at, $send_user_notif->email, $send_user_notif->name);
                        } else {
                            Controller::email_tagihan($data->tagihan_iuran->total_tagihan, $find->tanggal, $rekapan->created_at, $send_user_notif->email, $send_user_notif->name);
                        }

                        // Send Notif For Website
                        $notif = Controller::notif_tagihan($send_user_notif->name, $data->tagihan_iuran->total_tagihan, $find->tanggal, $rekapan->created_at);
                        Controller::storeNotification($iuran->user_id, 'iuran', 'Pembayaran Tagihan Iuran', $notif);
                        // End Notif For Website
                    }

                    // Log Activity
                    LogActivity::create([
                        'ip_address' => request()->ip(),
                        'user_id' => Auth::user()->id,
                        'target_user' => $iuran->user_id,
                        'previous_url' => URL::previous(),
                        'current_url' => URL::current(),
                        'file' => 'API\TagihanController.php',
                        'action' => 'Submit Pembayaran Iuran Nasabah Melalui API',
                    ]);
                    // End Log

                    // Hapus tmp
                    $iuran->delete();

                    DB::commit();
                    return ResponseFormatter::success(
                        'Setoran Sampah Berhasil Ditambahkan'
                    );
                }
                DB::rollback();
                return ResponseFormatter::error([
                    'success' => false,
                    'message' => 'Nominal Bayar Kurang Dari Total Wajib Bayar',
                ], 'Bad Request', 400);
            }
            DB::rollback();
            return ResponseFormatter::error([
                'success' => false,
                'message' => 'Data Sampah Masih Kosong',
            ], 'Bad Request', 400);
        } catch (Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'success' => false,
                'message' => $e,
            ], 'Internal Server Error', 500);
        }
    }

    public function kirimInvoice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'foto' => 'required|image|max:2048',
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(['error' => $validator->errors()], 'Validasi gagal', 400);
        }

        if ($request->file('foto')) {
            $file = $request->file('foto');
            $filename = 'invoice_' . Carbon::now()->format('dMY') . '.' . $file->getClientOriginalExtension();
            // dd($filename);

            $file->storeAs('public/invoice', $filename);

            // Send To User When Finish Transaction
            $send_user_notif = User::where('id', $request->id)->first();
            $message = Controller::message_invoice($send_user_notif->name);
            if ($send_user_notif->no_telp != '') {
                // send message to whatsapp number
                $url = config('app.url') . Storage::url('public/invoice/' . $filename);
                // dd($url);
                // $url = 'https://7d1d-103-253-24-84.ngrok.io/storage/invoice/invoice_24Feb2022.jpeg';
                Controller::sendMessageWithFile($send_user_notif->no_telp, $url, $message);
            }

            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'API\TagihanController.php',
                'action' => 'Mengirim foto ke whatsapp',
            ]);

            // End Log
            return ResponseFormatter::success(
                '',
                'Kirim invoice ke whatsapp berhasil'
            );
        }
        return ResponseFormatter::error([
            'success' => false,
            'message' => 'Upload Foto Terlebih Dahulu',
        ], 'Bad Request', 400);
    }
}
