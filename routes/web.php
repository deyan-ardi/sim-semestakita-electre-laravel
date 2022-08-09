<?php

use Illuminate\Support\Facades\Auth;
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

// Setting in Storage Server
// Route::prefix('storage')->group(function () {
//     $target  = '/home/semesta3/semestakita/storage/app/public';
//     $link    = '/home/semesta3/taksu-tridatu/storage';
//     symlink($target, $link);
// });

// Setting in Storage Demo Server
// Route::prefix('storage')->group(function () {
//     $target  = '/home/semesta3/semestakita/storage/app/public';
//     $link    = '/home/semesta3/demo.semestakita.id/storage';
//     symlink($target, $link);
// });

Route::get('/', function () {
    return redirect(route('login'));
});
Route::get('sitemap.xml', 'SitemapController@index');
// Whatsapp Login
Route::prefix('login-whatsapp')->group(function () {
    Route::get('/', 'Auth\LoginController@formLoginWhatsapp')->name('whatsapp.login');
    Route::get('kode-otp', 'Auth\LoginController@formInputOtpWhatsapp')->name('whatsapp.login.token');
    Route::post('resend', 'Auth\LoginController@login')->name('whatsapp.login.resend');
    Route::post('validation', 'Auth\LoginController@validOtpWhatsapp')->name('whatsapp.login.validation');
});

Auth::routes(['register' => false]);
// Reset Kata Sandi
Route::post('reset-password/email/aksi', 'Auth\ForgotPasswordController@forgetPasswordStore')->name('reset.password.aksi');
Route::get('reset-password/token/{token}', 'Auth\ForgotPasswordController@resetPassword')->name('reset.password.getEmail');
Route::post('reset-password/aksi', 'Auth\ForgotPasswordController@resetPasswordStore')->name('reset.password.update');

// Route for check user login is admin? redirect to admin page, else redirect to user page
Route::get('redirect', 'RedirectController@index');
Route::get('ganti-email/{user}', 'Admin\GetEmailCodeController@ganti_email')->name('ganti.email');
Route::get('redirect/logout-info', 'RedirectController@logout_info')->name('redirect.logout');

Route::prefix('panel')->middleware(['admin', 'auth'])->group(function () {
    // Group admin
    Route::namespace('Admin')->group(function () {
        Route::get('/', 'HomeController@index')->name('admin');

        // Penyetoran Sampah
        Route::prefix('kasir-penyetoran')->middleware(['admin', 'auth', 'lainnya'])->group(function () {
            // Kasir Penyetoran
            Route::get('/', 'PenyetoranController@index')->name('penyetoran');
            Route::post('penyetoran-sampah/proses', 'PenyetoranController@aksi_kasir')->name('penyetoran.aksi');
            Route::post('penyetoran-sampah', 'PenyetoranController@tambah')->name('penyetoran.tambah');
            Route::get('kasir-penyetoran-sampah', 'PenyetoranController@kasir')->name('penyetoran.kasir');
            Route::post('penyetoran-sampah/tambah', 'PenyetoranController@tambah_sampah')->name('penyetoran.tambah.sampah');
            Route::delete('penyetoran-sampah/hapus', 'PenyetoranController@hapus_sampah')->name('penyetoran.hapus.sampah');
            Route::post('list-data', 'PenyetoranController@getAllData')->name('penyetoran.list');
            Route::get('cetak-bukti-penyetoran/{rekapan}', 'PenyetoranController@show_cetak')->name('penyetoran.cetak');
            Route::post('cetak-bukti-penyetoran/aksi/{rekapan}', 'PenyetoranController@cetak_pdf')->name('penyetoran.cetak.aksi');
        });

        Route::prefix('master-data')->middleware(['admin', 'auth', 'pegawai', 'lainnya'])->group(function () {
            // Iuran
            Route::prefix('iuran')->middleware(['admin', 'auth'])->group(function () {
                Route::get('/', 'IuranController@index')->name('iuran.master');
                Route::get('ubah/{id}', 'IuranController@edit')->name('iuran.master.edit');
                Route::get('tambah', 'IuranController@create')->name('iuran.master.tambah');
                Route::post('tambah/proses', 'IuranController@store')->name('iuran.master.store');
                Route::put('ubah/proses/{id}', 'IuranController@update')->name('iuran.master.update');
                Route::delete('hapus/{id}', 'IuranController@destroy')->name('iuran.master.delete');
            });

            // Kriteria Penilaian
            Route::prefix('kriteria-penilaian')->middleware(['admin', 'auth'])->group(function () {
                Route::get('/', 'KriteriaPenilaianController@index')->name('kriteria-penilaian');
                Route::post('tambah/proses', 'KriteriaPenilaianController@store')->name('kriteria-penilaian.store');
                Route::patch('ubah/proses', 'KriteriaPenilaianController@update')->name('kriteria-penilaian.update');
                Route::post('hapus-semua', 'KriteriaPenilaianController@destroyAll')->name('kriteria-penilaian.destroy.all');
                Route::post('publish-semua', 'KriteriaPenilaianController@publishAll')->name('kriteria-penilaian.publish.all');
                Route::get('tampilkan', 'KriteriaPenilaianController@getAll')->name('kriteria-penilaian.getAll');
                Route::post('unpublish-semua', 'KriteriaPenilaianController@unpublishAll')->name('kriteria-penilaian.unpublish.all');
            });

            // Kategori
            Route::prefix('kategori-sampah')->middleware(['admin', 'auth'])->group(function () {
                Route::get('/', 'KategoriController@index')->name('kategori');
                Route::get('tambah', 'KategoriController@create')->name('kategori.tambah');
                Route::post('tambah/proses', 'KategoriController@store')->name('kategori.store');
                Route::get('ubah/{id}', 'KategoriController@edit')->name('kategori.edit');
                Route::put('ubah/proses/{id}', 'KategoriController@update')->name('kategori.update');
                Route::delete('hapus/{id}', 'KategoriController@destroy')->name('kategori.delete');
            });

            // User Management
            Route::prefix('pengguna')->middleware(['admin', 'auth', 'pengelola'])->group(function () {
                Route::get('/', 'UserController@index')->name('user');
                Route::get('tambah', 'UserController@create')->name('user.tambah');
                Route::post('tambah/proses', 'UserController@store')->name('user.store');
                Route::get('ubah/{id}', 'UserController@edit')->name('user.edit');
                Route::put('ubah/proses/{id}', 'UserController@update')->name('user.update');
                Route::delete('hapus/{id}', 'UserController@destroy')->name('user.delete');
            });

            // Pelanggan
            Route::prefix('pelanggan')->middleware(['admin', 'auth'])->group(function () {
                Route::get('/', 'PelangganController@index')->name('pelanggan');
                Route::get('tambah', 'PelangganController@create')->name('pelanggan.tambah');
                Route::post('tambah/proses', 'PelangganController@store')->name('pelanggan.store');
                Route::get('ubah/{id}', 'PelangganController@edit')->name('pelanggan.edit');
                Route::put('ubah/proses/{id}', 'PelangganController@update')->name('pelanggan.update');
                Route::get('tampilkan', 'PelangganController@getAll')->name('pelanggan.getAll');
                Route::delete('hapus/{id}', 'PelangganController@destroy')->name('pelanggan.delete');
                Route::post('hapus-semua', 'PelangganController@destroyAll')->name('pelanggan.delete.all');
                // Toggle Status
                Route::post('ubah-status', 'PelangganController@ubahStatus')->name('pelanggan.ubahStatus');
                Route::post('ubah-status-multi', 'PelangganController@ubahStatusAll')->name('pelanggan.ubahStatus.all');

                Route::post('cetak-kode-qr-multi', 'PelangganController@cetakQRAll')->name('pelanggan.cetak.qr.multiple');
                Route::get('cetak-kode-qr/{id}', 'PelangganController@cetakQR')->name('pelanggan.cetak.qr');

                // Import Eksport
                Route::post('ekspor-multi-data/{status}', 'PelangganController@export')->name('pelanggan.export');
                Route::get('ekspor-data/{id}', 'PelangganController@exportone')->name('pelanggan.exportone');
                Route::post('impor-data', 'PelangganController@import')->name('pelanggan.import');
            });

            // Konfigurasi Sistem
            Route::prefix('konfigurasi-dasar')->middleware(['admin', 'auth'])->group(function () {
                Route::get('/', 'KonfigurasiController@index')->name('konfigurasi');
                Route::patch('ubah/proses/{id}', 'KonfigurasiController@update')->name('konfigurasi.update');
                Route::group(['prefix' => 'kriteria'], function () {
                    Route::get('tampilkan', 'KonfigurasiController@getAll')->name('konfigurasi.kriteria.getAll');
                    Route::get('tambah', 'KonfigurasiController@createKriteria')->name('konfigurasi.kriteria.tambah');
                    Route::post('tambah/proses', 'KonfigurasiController@storeKriteria')->name('konfigurasi.kriteria.store');
                    Route::get('ubah/{id}', 'KonfigurasiController@editKriteria')->name('konfigurasi.kriteria.ubah');
                    Route::patch('ubah/proses/{id}', 'KonfigurasiController@updateKriteria')->name('konfigurasi.kriteria.update');
                    Route::delete('hapus/{id}', 'KonfigurasiController@destroyKriteria')->name('konfigurasi.kriteria.destroy');
                });
            });

            // Informasi Sistem
            Route::prefix('informasi-sistem')->middleware(['admin', 'auth', 'pengelola'])->group(function () {
                Route::get('/', 'SistemInfoController@index')->name('sistem.info');
                Route::get('tambah', 'SistemInfoController@create')->name('sistem.info.tambah');
                Route::get('ubah/{id}', 'SistemInfoController@edit')->name('sistem.info.edit');
                Route::post('tambah/proses', 'SistemInfoController@store')->name('sistem.info.store');
                Route::delete('hapus/{id}', 'SistemInfoController@destroy')->name('sistem.info.destroy');
                Route::patch('ubah/proses/{id}', 'SistemInfoController@update')->name('sistem.info.update');
            });
            // Nasabah
            Route::prefix('nasabah')->middleware(['admin', 'auth'])->group(function () {
                Route::get('/', 'NasabahController@index')->name('nasabah');
                Route::post('tambah/proses', 'NasabahController@store')->name('nasabah.store');
                Route::get('tambah', 'NasabahController@create')->name('nasabah.tambah');
                Route::get('tampilkan', 'NasabahController@getAll')->name('nasabah.getAll');
                Route::get('ubah/{id}', 'NasabahController@edit')->name('nasabah.edit');
                Route::put('ubah/proses/{id}', 'NasabahController@update')->name('nasabah.update');
                Route::delete('hapus/{id}', 'NasabahController@destroy')->name('nasabah.delete');
                Route::post('hapus-semua', 'NasabahController@destroyAll')->name('nasabah.delete.all');
                // Toggle Status
                Route::post('ubah-status', 'NasabahController@ubahStatus')->name('nasabah.ubahStatus');
                Route::post('ubah-status-multi', 'NasabahController@ubahStatusAll')->name('nasabah.ubahStatus.all');

                Route::get('cetak-kode-qr/{id}', 'NasabahController@cetakQR')->name('nasabah.cetak.qr');
                Route::post('cetak-kode-qr-multi', 'NasabahController@cetakQRAll')->name('nasabah.cetak.qr.multiple');
                // Import Eksport
                Route::post('ekspor-multi-data/{status}', 'NasabahController@export')->name('nasabah.export');
                Route::get('ekspor-data/{id}', 'NasabahController@exportone')->name('nasabah.exportone');
                Route::post('impor-data', 'NasabahController@import')->name('nasabah.import');
            });
        });

        // Kasir Iuran
        Route::prefix('kasir-iuran')->middleware(['admin', 'auth', 'lainnya'])->group(function () {
            Route::get('/', 'KasirIuranController@index')->name('iuran.kasir');
            Route::post('pembayaran-iuran/proses', 'KasirIuranController@tambah')->name('iuran.kasir.tambah');
            Route::delete('pembayaran-iuran/hapus', 'KasirIuranController@destroy')->name('iuran.kasir.hapus.sampah');
            Route::get('pembayaran-iuran', 'KasirIuranController@pembayaran')->name('iuran.kasir.pembayaran');
            Route::get('cetak-bukti-pembayaran/{array}', 'KasirIuranController@show_cetak')->name('iuran.kasir.cetak');
            Route::post('cetak-bukti-pembayaran/aksi/{array}', 'KasirIuranController@cetak_pdf')->name('iuran.kasir.cetak.aksi');
            Route::post('list-data', 'KasirIuranController@getAllData')->name('iuran.kasir.table.list');
            Route::post('pembayaran-iuran/bayar', 'KasirIuranController@tambah_pembayaran')->name('iuran.kasir.bayar.aksi');
            Route::post('pembayaran-iuran/tambah', 'KasirIuranController@tambah_keranjang')->name('iuran.kasir.tambah.keranjang');
        });

        // Informasi Sistem
        Route::prefix('informasi-sistem')->middleware(['admin', 'auth'])->group(function () {
            Route::get('/', 'SistemInfoController@detail_info')->name('bantuan.info.sistem');
        });

        // Kasir
        Route::prefix('kasir-rekapan-harian')->middleware(['admin', 'auth', 'lainnya'])->group(function () {
            Route::get('/', 'RekapanHarianController@index')->name('harian');
            Route::post('rekapan-harian/proses', 'RekapanHarianController@tambah')->name('harian.tambah');
            Route::post('rekapan-harian/proses-kasir', 'RekapanHarianController@aksi_kasir')->name('harian.aksi');
            Route::post('list-data', 'RekapanHarianController@getAllData')->name('harian.list');
            Route::post('rekapan-harian/tambah', 'RekapanHarianController@tambah_sampah')->name('harian.tambah.sampah');
            Route::delete('rekapan-harian/hapus', 'RekapanHarianController@hapus_sampah')->name('harian.hapus.sampah');
            Route::get('rekapan-harian', 'RekapanHarianController@kasir')->name('harian.kasir');
            Route::get('cetak-bukti-rekapan/{harian}', 'RekapanHarianController@show_cetak')->name('harian.cetak');
            Route::post('cetak-bukti-rekapan/proses/{harian}', 'RekapanHarianController@cetak_pdf')->name('harian.cetak.aksi');
        });
        // Tabungan
        Route::prefix('kasir-tabungan')->middleware(['admin', 'auth', 'lainnya'])->group(function () {
            // Kasir Penarikan Tabungan
            Route::get('/', 'TabunganController@index')->name('tabungan');
            Route::get('kasir-penarikan-tabungan', 'TabunganController@kasir')->name('tabungan.kasir');
            Route::post('penarikan-tabungan', 'TabunganController@tambah')->name('tabungan.tambah');
            Route::post('penarikan-tabungan/proses', 'TabunganController@aksi_kasir')->name('tabungan.aksi');
            Route::get('cetak-bukti-penarikan/{penarikan}', 'TabunganController@show_cetak')->name('tabungan.cetak');
            Route::post('cetak-bukti-penarikan/proses/{penarikan}', 'TabunganController@cetak_pdf')->name('tabungan.cetak.aksi');
        });

        // Tagihan
        Route::prefix('daftar-tagihan')->middleware(['admin', 'auth'])->group(function () {
            Route::get('/', 'DaftarTagihanController@index')->name('tagihan');
            Route::get('filter', 'DaftarTagihanController@filter')->name('tagihan.filter');
            Route::post('regenerate', 'DaftarTagihanController@regenerate')->name('tagihan.regenerate');
        });

        // List Tabungan
        Route::prefix('list-tabungan')->middleware(['admin', 'auth'])->group(function () {
            Route::get('/', 'ListTabunganController@index')->name('list-tabungan');
            Route::get('filter', 'ListTabunganController@filter')->name('list-tabungan.filter');
            Route::post('ekspor-multi-data', 'ListTabunganController@export')->name('list-tabungan.export');
            Route::get('ekspor-data/{id}', 'ListTabunganController@export_single')->name('list-tabungan.export.single');
        });

        // Profil
        Route::prefix('profil')->middleware(['admin', 'auth'])->group(function () {
            Route::get('/', 'ProfilController@index')->name('ganti.profil');
            Route::get('validasi/pergantian-nomor-whatsapp', 'ProfilController@inputToken')->name('ganti.profil.validasi');
            Route::post('validasi/pergantian-nomor-whatsapp', 'ProfilController@validasiToken')->name('ganti.profil.validasi.aksi');
            Route::patch('ubah/proses/{user}', 'ProfilController@update')->name('ganti.profil.aksi');
        });

        // Security
        Route::prefix('keamanan')->middleware(['admin', 'auth'])->group(function () {
            Route::get('/', 'SecurityController@index')->name('ganti.keamanan');
            Route::patch('ubah/proses/{user}', 'SecurityController@update')->name('ganti.keamanan.aksi');
        });

        // Pengangkutan dan Penilaian Harian
        Route::prefix('pengangkutan-penilaian')->middleware(['admin', 'auth'])->group(function () {
            Route::get('/', 'PengangkutanPenilaianController@index')->name('pengangkutan-penilaian');
            Route::get('tampilkan', 'PengangkutanPenilaianController@getAll')->name('pengangkutan-penilaian.getAll');
            Route::post('detail', 'PengangkutanPenilaianController@detail')->name('pengangkutan-penilaian.detail');
            Route::post('hapus-semua', 'PengangkutanPenilaianController@destroyAll')->name('pengangkutan-penilaian.destroy.all');
            Route::delete('hapus/{id}', 'PengangkutanPenilaianController@destroy')->name('pengangkutan-penilaian.destroy');

            Route::prefix('pindai-kode-qr')->group(function () {
                Route::get('/', 'PengangkutanPenilaianController@scan')->name('pengangkutan-penilaian.scan');
                Route::post('proses', 'PengangkutanPenilaianController@scanProcess')->name('pengangkutan-penilaian.scan.process');
                Route::get('hasil/{id_user}', 'PengangkutanPenilaianController@result_scan')->name('pengangkutan-penilaian.scan.result');
                Route::post('proses-data/{id_user}', 'PengangkutanPenilaianController@resultProcess')->name('pengangkutan-penilaian.scan.result.process');
            });
        });

        // Penjemputan
        Route::prefix('penjemputan-sampah')->middleware(['admin', 'auth'])->group(function () {
            Route::get('/', 'PenjemputanController@index')->name('penjemputan');
            Route::get('filter', 'PenjemputanController@filter')->name('penjemputan.filter');
            Route::get('ubah/{jemput}', 'PenjemputanController@edit')->name('penjemputan.edit');
            Route::patch('ubah/proses/{jemput}', 'PenjemputanController@update')->name('penjemputan.update');
            Route::get('tambah', 'PenjemputanController@create')->name('penjemputan.create');
            Route::post('tambah/proses', 'PenjemputanController@store')->name('penjemputan.store');
            Route::post('ekspor-data', 'PenjemputanController@export')->name('penjemputan.export');
            Route::post('ubah-status/{jemput}', 'PenjemputanController@set_status')->name('penjemputan.set');
            Route::delete('hapus/{jemput}', 'PenjemputanController@destroy')->name('penjemputan.delete');
        });

        // Artikel
        Route::prefix('artikel-dan-produk')->middleware(['admin', 'auth', 'lainnya'])->group(function () {
            Route::get('/', 'ArtikelController@index')->name('artikel');
            Route::post('tambah/proses', 'ArtikelController@store')->name('artikel.store');
            Route::get('tambah', 'ArtikelController@create')->name('artikel.tambah');
            Route::get('ubah/{id}', 'ArtikelController@edit')->name('artikel.edit');
            Route::put('ubah/proses/{id}', 'ArtikelController@update')->name('artikel.update');
            Route::delete('hapus/{id}', 'ArtikelController@destroy')->name('artikel.delete');
        });

        // Laporan Rekapan Sampah
        Route::prefix('rekapan-sampah')->middleware(['admin', 'auth'])->group(function () {
            Route::get('/', 'LaporanSampahController@index')->name('rekapan-sampah');
            Route::get('detail/{rekapan}', 'LaporanSampahController@detail')->name('rekapan-sampah.detail');
            Route::get('filter', 'LaporanSampahController@filter')->name('rekapan-sampah.filter');
            Route::get('cari-data/{id}', 'LaporanSampahController@search')->name('rekapan-sampah.search');
            Route::post('ekspor-data', 'LaporanSampahController@export')->name('rekapan-sampah.export');
            Route::get('cetak-bukti-penyetoran/{rekapan}', 'LaporanSampahController@cetak_by_id')->name('rekapan-sampah.cetak.id');
            Route::post('cetak-bukti-penyetoran/aksi/{rekapan}', 'LaporanSampahController@cetak_pdf')->name('rekapan-sampah.cetak.aksi');
        });

        // Laporan Rekapan Tabungan
        Route::prefix('rekapan-tabungan')->middleware(['admin', 'auth'])->group(function () {
            Route::get('/', 'LaporanTabunganController@index')->name('rekapan-tabungan');
            Route::get('filter', 'LaporanTabunganController@filter')->name('rekapan-tabungan.filter');
            Route::get('cari-data/{id}', 'LaporanTabunganController@search')->name('rekapan-tabungan.search');
            Route::post('ekspor-data', 'LaporanTabunganController@export')->name('rekapan-tabungan.export');
            Route::get('cetak-bukti-penarikan/{penarikan}', 'LaporanTabunganController@cetak_by_id')->name('rekapan-tabungan.cetak.id');
            // Route::post('cetak-bukti-penarikan/aksi/{penarikan}', 'LaporanTabunganController@cetak_pdf')->name('rekapan-tabungan.cetak.aksi');
        });

        // Laporan Rekapan Iuran
        Route::prefix('rekapan-iuran')->middleware(['admin', 'auth'])->group(function () {
            Route::get('/', 'LaporanIuranController@index')->name('rekapan-iuran');
            Route::get('filter', 'LaporanIuranController@filter')->name('rekapan-iuran.filter');
            Route::post('ekspor-data', 'LaporanIuranController@export')->name('rekapan-iuran.export');
            Route::get('cetak-bukti-penyetoran/{rekapan}', 'LaporanIuranController@cetak_by_id')->name('rekapan-iuran.cetak.id');
        });

        // Laporan Rekapan Penilaian
        Route::prefix('rekapan-penilaian')->middleware(['admin', 'auth'])->group(function () {
            Route::get('/', 'LaporanRekapanPenilaianController@index')->name('rekapan-penilaian');
            Route::get('tampilkan', 'LaporanRekapanPenilaianController@getAll')->name('rekapan-penilaian.getAll');
            Route::post('detail', 'LaporanRekapanPenilaianController@detail')->name('rekapan-penilaian.detail');
            Route::post('ekspor-data/{status}', 'LaporanRekapanPenilaianController@export')->name('rekapan-penilaian.export');
            Route::post('ekspor-data-single/{id}', 'LaporanRekapanPenilaianController@exportSingle')->name('rekapan-penilaian.export.single');

            // Rekomendasi
            Route::prefix('pemilah-aktif')->group(function () {
                Route::get('/', 'LaporanRekapanPenilaianController@electreQuery')->name('rekapan-penilaian.rekomendasi');
                Route::get('tampilkan', 'LaporanRekapanPenilaianController@getAllRekomendasi')->name('rekapan-penilaian.rekomendasi.getAll');
                Route::post('ekspor-data', 'LaporanRekapanPenilaianController@exportRekomendasi')->name('rekapan-penilaian.rekomendasi.export');
                Route::group(['prefix' => 'pemenang'], function () {
                    Route::get('/', 'LaporanRekapanPenilaianController@getAllPemenang')->name('rekapan-penilaian.rekomendasi.pemenang.getAll');
                    Route::post('list-rekomendasi', 'LaporanRekapanPenilaianController@listRekomendasiPemenang')->name('rekapan-penilaian.rekomendasi.pemenang.list.rekomendasi');
                    Route::post('proses', 'LaporanRekapanPenilaianController@prosesPemenang')->name('rekapan-penilaian.rekomendasi.pemenang.proses');
                    Route::post('publish', 'LaporanRekapanPenilaianController@publishPemenang')->name('rekapan-penilaian.rekomendasi.pemenang.publish');
                    Route::post('unpublish', 'LaporanRekapanPenilaianController@unpublishPemenang')->name('rekapan-penilaian.rekomendasi.pemenang.unpublish');
                    Route::delete('hapus', 'LaporanRekapanPenilaianController@destroyPemenang')->name('rekapan-penilaian.rekomendasi.pemenang.destroy');
                    Route::post('/', 'LaporanRekapanPenilaianController@exportPemenang')->name('rekapan-penilaian.rekomendasi.pemenang.export');
                });
            });
        });

        // Laporan Rekapan Sampah Harian
        Route::prefix('rekapan-harian')->middleware(['admin', 'auth'])->group(function () {
            Route::get('/', 'LaporanSampahHarianController@index')->name('rekapan-harian');
            Route::get('detail/{rekapan}', 'LaporanSampahHarianController@detail')->name('rekapan-harian.detail');
            Route::get('filter', 'LaporanSampahHarianController@filter')->name('rekapan-harian.filter');
            Route::post('ekspor-data', 'LaporanSampahHarianController@export')->name('rekapan-harian.export');
            Route::get('cetak-bukti-penyetoran/{rekapan}', 'LaporanSampahHarianController@cetak_by_id')->name('rekapan-harian.cetak.id');
            Route::post('cetak-bukti-penyetoran/aksi/{rekapan}', 'LaporanSampahHarianController@cetak_pdf')->name('rekapan-harian.cetak.aksi');
        });

        // Detail Tabungan dan History
        Route::prefix('detail-tabungan')->middleware(['admin', 'auth'])->group(function () {
            Route::get('detail/{user}/{status}', 'DetailTabunganController@index')->name('rekapan-tabungan.detail');
            Route::post('cetak-histori/{user}', 'DetailTabunganController@print_pdf')->name('detail-tabungan.cetak-history');
            Route::get('histori/{user}', 'DetailTabunganController@history')->name('rekapan-tabungan.history');
        });

        // Notifikasi
        Route::prefix('kirim-notifikasi')->middleware(['admin', 'auth', 'lainnya'])->group(function () {
            Route::get('/', 'NotifikasiController@index')->name('notifikasi');
            Route::get('filter', 'NotifikasiController@filter')->name('notifikasi.filter');
            Route::get('ubah/{notifikasi}', 'NotifikasiController@edit')->name('notifikasi.edit');
            Route::get('tambah', 'NotifikasiController@create')->name('notifikasi.create');
            Route::post('tambah/proses', 'NotifikasiController@store')->name('notifikasi.store');
            Route::patch('ubah/proses/{notifikasi}', 'NotifikasiController@update')->name('notifikasi.update');
            Route::delete('hapus/{notifikasi}', 'NotifikasiController@destroy')->name('notifikasi.delete');
        });

        Route::prefix('notif-sistem')->group(function () {
            Route::get('/{id}', 'SistemNotifController@index')->name('sistem.notif');
        });
        // Pengaduan User
        Route::prefix('pengaduan-kritik-saran')->middleware(['admin', 'auth', 'lainnya'])->group(function () {
            Route::get('/', 'PengaduanUserController@index')->name('pengaduan');
            Route::get('filter', 'PengaduanUserController@filter')->name('pengaduan.filter');
            Route::get('detail/{id}', 'PengaduanUserController@detail')->name('pengaduan.detail');
        });

        // Statistik Sampah
        Route::prefix('statistik-sampah')->middleware(['admin', 'auth'])->group(function () {
            Route::get('/', 'StatistikSampahController@index')->name('statistik.keseluruhan');
            Route::get('harian/', 'StatistikSampahController@harian')->name('statistik.harian');
        });
    });
});
