<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ConfigController;
use App\Http\Controllers\API\TagihanController;
use App\Http\Controllers\API\KriteriaController;
use App\Http\Controllers\API\RekomendasiController;
use App\Http\Controllers\API\SystemNotifController;
use App\Http\Controllers\API\RekapanIuranController;
use App\Http\Controllers\API\StatistikSampahController;
use App\Http\Controllers\API\PengangkutanPenilaianController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', [AuthController::class, 'login']);
Route::post('logout-other-device', [AuthController::class, 'logoutOtherDevice']);

// With Auth
Route::group(['middleware' => 'auth:api',], function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('check-token-user', [AuthController::class, 'checkTokenUser']);
    Route::prefix('user')->group(function () {
        Route::get('/', [AuthController::class, 'fetch']);
        Route::post('/updateProfile', [AuthController::class, 'updateProfile']);
        Route::post('/updatePhoto', [AuthController::class, 'updatePhoto']);
    });

    Route::prefix('nasabah')->group(function () {
        //after user scan nasabah id then create
        Route::get('/scan/{id?}', [TagihanController::class, 'scan']);
        Route::prefix('tagihan')->group(function () {
            Route::get('/{id?}', [TagihanController::class, 'allTagihan']);
            Route::post('/tmp', [TagihanController::class, 'storeTmpTagihanIuran']);
            Route::post('/tmp/detail', [TagihanController::class, 'storeTmpDetailTagihanIuran']);
            Route::delete('tmp/{id?}', [TagihanController::class, 'destroy']);
            Route::post('/tmp/checkout', [TagihanController::class, 'checkout']);
            Route::post('/invoice', [TagihanController::class, 'kirimInvoice']);
        });
    });

    Route::prefix('config')->group(function () {
        Route::get('/', [ConfigController::class, 'config']);
    });

    Route::prefix('system-notif')->group(function () {
        Route::get('/', [SystemNotifController::class, 'allNotif']);
        Route::get('/{id?}', [SystemNotifController::class, 'singleNotif']);
    });

    Route::prefix('rekapan-iuran')->group(function () {
        Route::get('/', [RekapanIuranController::class, 'allRekapan']);
        Route::get('/{date?}', [RekapanIuranController::class, 'filter']);
    });

    Route::prefix('pengangkutan-penilaian')->group(function () {
        Route::get('/', [PengangkutanPenilaianController::class, 'getAllPengangkutan']);
        Route::get('/{date?}', [PengangkutanPenilaianController::class, 'filter']);
        Route::post('tambah', [PengangkutanPenilaianController::class, 'storeData']);
    });
    Route::prefix('data-statistik')->group(function () {
        Route::get('/', [StatistikSampahController::class, 'sampahKeseluruhan']);
        Route::get('/kategori/{kategori?}', [StatistikSampahController::class, 'filterKategori']);
        Route::get('/harian', [StatistikSampahController::class, 'sampahHarian']);
    });

    Route::prefix('kriteria')->group(function () {
        Route::get('/', [KriteriaController::class, 'allKriteria']);
    });

    Route::prefix('rekomendasi-electre')->group(function () {
        Route::get('/{bulan?}/{tahun?}', [RekomendasiController::class, 'allRekomendasi']);
    });
});
