<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Config;
use App\Models\LogActivity;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ConfigController extends Controller
{
    public function config()
    {
        DB::beginTransaction();
        try {
            $get_config_where_denda = Config::where('key', 'denda')->first();
            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'API\ConfigController.php',
                'action' => 'Mengambil Data Configurasi Denda',
            ]);
            DB::commit();
            return ResponseFormatter::success(
                $get_config_where_denda,
                'Data konfigurasi denda didapat'
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
