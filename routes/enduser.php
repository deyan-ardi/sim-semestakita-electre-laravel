<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('ganti_email/{user}', 'Enduser\GetEmailController@ganti_email')->name('enduser.profil.ganti.email');
Route::prefix('user')->middleware(['user', 'auth'])->group(function () {
    Route::get('/redirect/logout-info', 'RedirectController@logout_info')->name('redirect.logout.user');
    // Dashboard
    Route::namespace('Enduser')->group(function () {
        Route::get('/', 'HomeController@index')->name('enduser.dashboard');

        Route::prefix('statistik')->middleware(['user', 'auth'])->group(function () {
            Route::get('/anorganik', 'StatistikController@anorganik')->name('enduser.statistik.anorganik');
            Route::get('/b3', 'StatistikController@b3')->name('enduser.statistik.b3');
            Route::get('/residu', 'StatistikController@residu')->name('enduser.statistik.residu');
        });

        // Profile Setting
        Route::prefix('profil')->middleware(['user', 'auth'])->group(function () {
            Route::get('/', 'ProfilController@index')->name('enduser.profil.index');
            Route::post('aksi/{user}', 'ProfilController@aksi')->name('enduser.profil.aksi');
            Route::get('keamanan', 'ProfilController@view_security')->name('enduser.profil.security');
            Route::post('keamanan/aksi/{user}', 'ProfilController@aksi_security')->name('enduser.profil.security.aksi');
            Route::get('rekening', 'ProfilController@view_rekening')->name('enduser.profil.rekening');
            Route::post('rekening/aksi/{user}', 'ProfilController@aksi_rekening')->name('enduser.profil.rekening.aksi');
            Route::get('validasi/pergantian-rekening', 'ProfilController@validasiTokenRekening')->name('enduser.validasi.token.rekening');
            Route::get('validasi/pergantian-nomor-whatsapp', 'ProfilController@validasiTokenPhone')->name('enduser.validasi.token.phone');
            Route::post('validasi/pergantian-rekening', 'ProfilController@validasiTokenAksi')->name('enduser.validasi.token.aksi');
        });

        // Penyetoran Sampah
        Route::prefix('penyetoran')->middleware(['user', 'auth'])->group(function () {
            Route::get('/', 'PenyetoranController@index')->name('enduser.penyetoran.index');
            Route::get('/detail/{rekapan_sampah_id}', 'PenyetoranController@detail')->name('enduser.penyetoran.detail');
            Route::get('/filter', 'PenyetoranController@filter')->name('enduser.penyetoran.filter');
        });

        // Rekapan Penilaian
        Route::prefix('rekapan-penilaian')->middleware(['user', 'auth'])->group(function () {
            Route::get('/', 'RekapanPenilaianController@index')->name('enduser.rekapan-penilaian.index');
            Route::get('semua-riwayat', 'RekapanPenilaianController@getAllRiwayat')->name('enduser.rekapan-penilaian.getAll.riwayat');
            Route::get('gsemua-rekomendasi', 'RekapanPenilaianController@getAllRekomendasi')->name('enduser.rekapan-penilaian.getAll.rekomendasi');
        });
        // Produksi
        Route::prefix('produksi')->middleware(['user', 'auth'])->group(function () {
            Route::get('/', 'ProduksiController@index')->name('enduser.produksi.index');
            Route::get('/detail/{artikel:slug}', 'ProduksiController@detail')->name('enduser.produksi.detail');
        });

        // Artikel
        Route::prefix('artikel')->middleware(['user', 'auth'])->group(function () {
            Route::get('detail/{artikel:slug}', 'ArtikelController@detail_artikel')->name('enduser.artikel.detail');
        });

        // Riwayat
        Route::prefix('riwayat')->middleware(['user', 'auth'])->group(function () {
            Route::get('/', 'RiwayatController@index')->name('enduser.riwayat.index');
            Route::get('/filter', 'RiwayatController@filter')->name('enduser.riwayat.filter');
            Route::get('/detail/{id}', 'RiwayatController@detail')->name('enduser.riwayat.detail');
        });

        // Tabungan
        Route::prefix('tabungan')->middleware(['user', 'auth'])->group(function () {
            Route::get('/', 'TabunganController@index')->name('enduser.tabungan.index');
            Route::get('/filter', 'TabunganController@filter')->name('enduser.tabungan.filter');
        });

        // Tagihan
        Route::prefix('tagihan')->middleware(['user', 'auth'])->group(function () {
            Route::get('/', 'TagihanController@index')->name('enduser.tagihan.index');
            Route::get('/filter', 'TagihanController@filter')->name('enduser.tagihan.filter');
            Route::get('/detail/{id}', 'TagihanController@detail')->name('enduser.tagihan.detail');
        });

        // Bantuan
        Route::prefix('bantuan')->middleware(['user', 'auth'])->group(function () {
            Route::get('/', 'BantuanController@index')->name('enduser.bantuan.index');
        });

        // About
        Route::prefix('tentang')->middleware(['user', 'auth'])->group(function () {
            Route::get('/', 'TentangController@index')->name('enduser.tentang.index');
        });

        // Hubungi
        Route::prefix('hubungi')->middleware(['user', 'auth'])->group(function () {
            Route::get('/', 'HubungiController@index')->name('enduser.hubungi.index');
            Route::get('/tambah-feedback', 'HubungiController@tambah_feedback')->name('enduser.hubungi.tambah');
            Route::get('/filter', 'HubungiController@filter')->name('enduser.hubungi.filter');
            Route::get('/ubah-feedback/{id}', 'HubungiController@ubah_feedback')->name('enduser.hubungi.ubah');
            Route::patch('/ubah-feedback/aksi/{id}', 'HubungiController@update')->name('enduser.hubungi.ubah.aksi');
            Route::post('/tambah-feedback/aksi', 'HubungiController@store')->name('enduser.hubungi.tambah.aksi');
            Route::get('/hapus-feedback/{id}', 'HubungiController@destroy')->name('enduser.hubungi.delete');
        });

        // Notifikasi
        Route::prefix('notifikasi-pengelola')->middleware(['user', 'auth'])->group(function () {
            Route::get('/', 'NotifikasiController@index')->name('enduser.notifikasi.index');
            Route::get('detail/{id}', 'NotifikasiController@sistem')->name('enduser.notifikasi.sistem');
        });
    });
});
