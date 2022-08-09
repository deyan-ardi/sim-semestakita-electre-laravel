<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Helpers\ResponseFormatter;
use App\Models\SystemNotification;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SystemNotifController extends Controller
{
    public function allNotif()
    {
        DB::beginTransaction();
        try {
            $all_notifikasi = SystemNotification::where('user_id', Auth()->user()->id)->where('key', 'angkut')->orWhere('key', 'iuran')->orderBy('created_at', 'DESC')->get();
            DB::commit();
            return ResponseFormatter::success(
                $all_notifikasi,
                'Data notifikasi untuk pegawai yang login didapat'
            );
        } catch (Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'success' => false,
                'message' => $e,
            ], 'Internal Server Error', 500);
        }
    }

    public function singleNotif($id)
    {
        DB::beginTransaction();
        try {
            $single_notifikasi = SystemNotification::where('id', $id)->first();
            if ($single_notifikasi == null) {
                return ResponseFormatter::error([
                    'success' => false,
                    'message' => 'Data tidak ditemukan',
                ], 'Not Found', 404);
            }
            $single_notifikasi->status = 'sudah_dibaca';
            $single_notifikasi->save();
            DB::commit();
            return ResponseFormatter::success(
                $single_notifikasi,
                'Data notifikasi untuk pegawai yang login didapat'
            );
        } catch (Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'success' => false,
                'message' => $e,
            ], 'Internal Server Error', 500);
        }
    }
}
