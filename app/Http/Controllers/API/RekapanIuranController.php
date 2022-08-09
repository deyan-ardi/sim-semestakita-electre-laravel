<?php

namespace App\Http\Controllers\API;

use Exception;
use Carbon\Carbon;
use App\Models\LogActivity;
use App\Models\RekapanIuran;
use InvalidArgumentException;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RekapanIuranController extends Controller
{
    public function allRekapan()
    {
        DB::beginTransaction();
        try {
            $all_rekapan_iuran_now = RekapanIuran::with('user')->whereDate('created_at', Carbon::now()->format('Y-m-d'))->orderBy('created_at', 'DESC')->get();
            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'API\RekapanIuranController.php',
                'action' => 'Mengambil Data Rekapan Iuran Hari Ini',
            ]);
            // End Log
            DB::commit();
            return ResponseFormatter::success(
                $all_rekapan_iuran_now,
                'Data Rekapan Iuran hari ini didapat'
            );
        } catch (Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'success' => false,
                'message' => $e,
            ], 'Internal Server Error', 500);
        }
    }

    public function filter($date)
    {
        DB::beginTransaction();
        try {
            $date = Carbon::parse($date)->format('Y-m-d');
            $all_rekapan_iuran_filter = RekapanIuran::with('user')->where('created_at', '>=', $date . ' 00:00:00')->where('created_at', '<=', $date . ' 23:59:59')->orderBy('created_at', 'DESC')->get();
            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'API\RekapanIuranController.php',
                'action' => 'Mengambil Data Rekapan Hasil Filter Hari Ini',
            ]);
            // End Log
            DB::commit();
            return ResponseFormatter::success(
                $all_rekapan_iuran_filter,
                'Data Rekapan Iuran Hasil Filter didapat'
            );
        } catch (InvalidArgumentException $e) {
            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'API\RekapanIuranController.php',
                'action' => 'Gagal Mengambil Data Rekapan Iuran, Filter Data Bukan Tanggal',
            ]);
            // End Log
            DB::rollback();
            return ResponseFormatter::error(['error' => $e], 'Bukan Tanggal', 400);
        } catch (Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'success' => false,
                'message' => $e,
            ], 'Internal Server Error', 500);
        }
    }
}
