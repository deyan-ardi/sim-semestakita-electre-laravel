<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Ramsey\Uuid\Uuid;
use App\Models\Kategori;
use App\Models\LogActivity;
use Illuminate\Http\Request;
use App\Models\RekapanHarian;
use Illuminate\Support\Facades\DB;
use App\Models\DetailRekapanHarian;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class RekapanHarianController extends Controller
{
    public function __construct()
    {
        // Delete All Tmp When Go To This Controller
        $this->middleware(function ($request, $next) {
            DB::transaction(function () {
                Session::forget('tmp_rekapan_sampah');
                Session::forget('tmp_tabungan');
                Session::forget('tmp_tagihan_iuran');
            });
            $this->removeSessionChangeProfil();
            return $next($request);
        });
    }
    public function index()
    {
        Session::forget('tmp_rekapan_harian');
        Session::forget('tmp_detail_rekapan_harian');
        // Log Activity
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'RekapanHarianController.php',
            'action' => 'Halaman Index Rekapan Harian',
        ]);
        // End Log
        return view('admin.page.harian.index');
    }

    public function tambah(Request $request)
    {
        $request->validate([
            'user' => ['required'],
        ]);
        DB::beginTransaction();
        try {
            Session::forget('tmp_rekapan_harian');
            Session::forget('tmp_detail_rekapan_harian');
            Session::put('tmp_rekapan_harian', [
                'id' => Uuid::uuid4(),
                'tanggal' => $request->date,
                'user_id' => Auth::user()->id,
                'kode_transaksi' => 'RH-' . date('Ymd') . '-' . strtoupper(\Str::random(6)),
                'status' => $request->user,
            ]);
            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'RekapanHarianController.php',
                'action' => 'Memulai Session Rekapan Harian',
            ]);
            // End Log
            DB::commit();
            return redirect(route('harian.kasir'));
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Ditambahkan');
        }
    }

    public function kasir()
    {
        DB::beginTransaction();
        try {
            // $find_first = TmpRekapanHarian::where('user_id', Auth::user()->id)->first();
            $find_first = Session::get('tmp_rekapan_harian');
            if (! empty($find_first) || $find_first != null) {
                $kategori = Kategori::all();
                return view('admin.page.harian.tambah-rekapan', ['page' => 2, 'fitur' => 2, 'rekapan' => $find_first, 'kategori' => $kategori]);
            }
            DB::commit();
            return redirect(route('harian'));
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Ditambahkan');
        }
    }

    public function hapus_sampah(Request $request)
    {
        DB::beginTransaction();
        try {
            $find = Session::pull('tmp_detail_rekapan_harian');
            if (! empty($find) || $find != null) {
                foreach ($find as $key => $value) {
                    if ($value['id'] == $request->id) {
                        unset($find[$key]);
                    }
                }
                Session::put('tmp_detail_rekapan_harian', $find);
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
            $query = Session::get('tmp_detail_rekapan_harian');
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
    public function tambah_sampah(Request $request)
    {
        DB::beginTransaction();
        try {
            $all = Session::get('tmp_detail_rekapan_harian');
            $status = true;
            $kategori = Kategori::find($request->kategori);
            if (! empty($all) || $all != null) {
                foreach ($all as $all) {
                    if ($all['kategori_id'] == $kategori->id) {
                        $status = false;
                        break;
                    }
                }
            } else {
                Session::put('tmp_detail_rekapan_harian', []);
                $status = true;
            }

            $find = Session::get('tmp_rekapan_harian');
            if ($request->total == 0) {
                return response()->json(['error' => true, 'message' => 'Jumlah Sampah Masuk/Keluar Tidak Dapat Sama Dengan 0']);
            }
            if ($find['status'] == 'Keluar') {
                if ($kategori->total_sampah - $request->total < 0) {
                    return response()->json(['error' => true, 'message' => 'Stok Sampah Tidak Mencukupi']);
                }
            }
            if ($status) {
                Session::push('tmp_detail_rekapan_harian', [
                    'id' => Uuid::uuid4(),
                    'kategori_id' => $kategori->id,
                    'nama_kategori' => $kategori->nama_kategori,
                    'harga_beli' => $kategori->harga_beli,
                    'jenis_sampah' => $kategori->jenis_sampah,
                    'tmp_rekapan_harian_id' => $find['id'],
                    'jumlah_sampah' => $request->total,
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

    public function aksi_kasir(Request $request)
    {
        DB::beginTransaction();
        try {
            $tmp_detail_harian = Session::pull('tmp_detail_rekapan_harian');
            $tmp_harian = Session::pull('tmp_rekapan_harian');
            $count_detail = count($tmp_detail_harian);
            $count_harian = count($tmp_harian);
            if ($count_detail > 0 && $count_harian > 0) {
                $total_sampah = 0;
                // Add to table rekapan sharian
                if ($tmp_harian['status'] == 'Keluar') {
                    // Query Untuk Mencegah Transaksi Yang Sama di waktu yang sama
                    $find_rekapan = RekapanHarian::where('status', 'Keluar')->orderBy('created_at', 'DESC')->first();
                    if (! empty($find_rekapan) && \Carbon\Carbon::parse($find_rekapan->created_at)->addMinute(2)->format('Y-m-d H:i:s') >= \Carbon\Carbon::now()->format('Y-m-d H:i:s')) {
                        DB::rollback();
                        return redirect(route('tabungan'))->with('error', 'Silahkan Tunggu 2 Menit Sebelum Menambahkan Data Sampah Keluar Lagi');
                    }
                    $total_pemasukan = $request->pemasukan;
                } else {
                    // Query Untuk Mencegah Transaksi Yang Sama di waktu yang sama
                    $find_rekapan = RekapanHarian::where('status', 'Masuk')->orderBy('created_at', 'DESC')->first();
                    if (! empty($find_rekapan) && \Carbon\Carbon::parse($find_rekapan->created_at)->addMinute(2)->format('Y-m-d H:i:s') >= \Carbon\Carbon::now()->format('Y-m-d H:i:s')) {
                        DB::rollback();
                        return redirect(route('tabungan'))->with('error', 'Silahkan Tunggu 2 Menit Sebelum Menambahkan Data Sampah Masuk Lagi');
                    }
                    $total_pemasukan = 0;
                }

                $rekapan = RekapanHarian::create([
                    'id' => $tmp_harian['id'],
                    'tanggal' => $tmp_harian['tanggal'],
                    'kode_transaksi' => $tmp_harian['kode_transaksi'],
                    'status' => $tmp_harian['status'],
                    'total_sampah' => $total_sampah,
                    'total_pemasukan' => $total_pemasukan,
                    'created_by' => Auth::user()->name,
                    'updated_by' => Auth::user()->name,
                ]);

                // Add Detail
                foreach ($tmp_detail_harian as $sampah) {
                    $total_sampah = $total_sampah + $sampah['jumlah_sampah'];
                    DetailRekapanHarian::create([
                        'nama_kategori' => $sampah['nama_kategori'],
                        'harga_kategori' => $sampah['harga_beli'],
                        'jenis_sampah' => $sampah['jenis_sampah'],
                        'rekapan_harian_id' => $tmp_harian['id'],
                        'jumlah_sampah' => $sampah['jumlah_sampah'],
                    ]);

                    $find_kategori = Kategori::find($sampah['kategori_id']);
                    if ($tmp_harian['status'] == 'Masuk') {
                        // Update tambah Total Sampah di TPS berdasarkan kategori jika ternyata rekapan data sampah masuk
                        $find_kategori->total_sampah = $find_kategori->total_sampah + $sampah['jumlah_sampah'];
                        $find_kategori->save();
                    } else {
                        $find_kategori->total_sampah = $find_kategori->total_sampah - $sampah['jumlah_sampah'];
                        $find_kategori->save();
                    }
                }

                // Update Rekapan Untuk Total Sampah dan Total Beli
                $rekapan->total_sampah = $total_sampah;
                $rekapan->save();

                // Remove Tmp
                Session::forget('tmp_rekapan_harian');
                Session::forget('tmp_detail_rekapan_harian');

                // Log Activity
                LogActivity::create([
                    'ip_address' => request()->ip(),
                    'user_id' => Auth::user()->id,
                    'previous_url' => URL::previous(),
                    'current_url' => URL::current(),
                    'file' => 'RekapanHarianController.php',
                    'action' => 'Simpan Rekapan Harian',
                ]);
                // End Log
                DB::commit();
                return redirect(route('harian.cetak', [$rekapan->id]))->with('success', 'Setoran Sampah Harian Berhasil Ditambahkan');
            }
            DB::rollback();
            return redirect()->back()->with('error', 'Data Sampah Masih Kosong');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Ditambahkan');
        }
    }

    public function show_cetak(RekapanHarian $harian)
    {
        DB::beginTransaction();
        try {
            if ($harian->count() > 0) {
                return view('admin.page.harian.show-cetak', ['rekapan' => $harian]);
            }
            DB::commit();
            return redirect(route('harian'));
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Disimpan');
        }
    }

    public function cetak_pdf(RekapanHarian $harian)
    {
        DB::beginTransaction();
        try {
            $pdf = \PDF::loadView('pdf.cetak-harian', ['title' => 'Rekapan Harian ' . $harian->status, 'rekapan' => $harian])->setPaper('a4', 'landscape');
            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'RekapanHarianController.php',
                'action' => 'Cetak PDF Rekapan Harian',
            ]);
            // End Log
            DB::commit();
            return  $pdf->download('Rekapan Harian Sampah ' . $harian->status . '-' . ($harian->kode_transaksi) . '.pdf');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Dicetak');
        }
    }
}
