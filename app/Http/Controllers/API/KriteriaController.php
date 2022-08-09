<?php

namespace App\Http\Controllers\API;

use Exception;
use Carbon\Carbon;
use App\Models\Kriteria;
use App\Models\LogActivity;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class KriteriaController extends Controller
{
    public function allKriteria()
    {
        DB::beginTransaction();
        try {
            $kriteria = Kriteria::where('periode', Carbon::now()->format('F Y'))->where('publish', '1')->orderBy('urutan', 'ASC')->get();
            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'API\KriteriaController.php',
                'action' => 'Mengambil Data Kriteria',
            ]);
            // End Log
            DB::commit();
            return ResponseFormatter::success(
                $kriteria,
                'Data Kriteria Berhasil Didapatkan'
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
