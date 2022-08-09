<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Ramsey\Uuid\Uuid;
use App\Models\Config;
use App\Models\Pengajuan;
use App\Models\LogActivity;
use Illuminate\Http\Request;
use App\Exports\PengajuanExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class PenjemputanController extends Controller
{
    public function __construct()
    {
        // Delete All Tmp When Go To This Controller
        $this->middleware(function ($request, $next) {
            $this->removeSessionTmpAll();
            $this->removeSessionChangeProfil();
            return $next($request);
        });
    }

    public function index()
    {
        $pengajuan = Pengajuan::all();
        // Log Activity
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'PenjemputanController.php',
            'action' => 'Halaman Awal Penjemputan',
        ]);
        // End Log
        return view('admin.page.pengajuan.index', ['pengajuan' => $pengajuan]);
    }

    public function create()
    {
        // Log Activity
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'PenjemputanController.php',
            'action' => 'Halaman Buat Penjemputan',
        ]);
        $config = Config::where('key', 'penjemputan')->first();
        // End Log
        if (Auth::user()->role != 6) {
            return view('admin.page.pengajuan.tambah', ['config' => $config]);
        }
        abort(404);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => ['required', 'date'],
            'name' => ['required', 'string'],
            'no_telp' => ['required', 'min:9', 'max:15'],
            'alamat' => ['required', 'string'],
            'ambil' => ['required', 'string'],
            'jarak' => ['required'],
            'biaya' => ['nullable'],
        ]);
        DB::beginTransaction();
        try {
            $config = Config::where('key', 'penjemputan')->first();
            if ($config->status == 'deactive') {
                $biaya = $request->biaya;
            } else {
                $biaya = $request->jarak * $config->value;
            }

            Pengajuan::create([
                'id' => Uuid::uuid4(),
                'tanggal' => $request->date,
                'nama_pelanggan' => ucwords($request->name),
                'kontak_pelanggan' => $request->no_telp,
                'alamat_pelanggan' => ucwords($request->alamat),
                'lokasi_ambil' => ucwords($request->ambil),
                'jarak' => $request->jarak,
                'biaya' => $biaya,
                'status' => 'pending',
                'user_id' => Auth::user()->id,
            ]);

            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'target_user' => $request->name,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'PenjemputanController.php',
                'action' => 'Store Penjemputan',
            ]);
            // End Log
            DB::commit();
            return redirect()->back()->with('success', 'Data Berhasil Ditambahkan');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Ditambahkan');
        }
    }

    public function destroy(Pengajuan $jemput)
    {
        DB::beginTransaction();
        try {
            if ($jemput->status == 'lunas') {
                return redirect(route('penjemputan'))->with('error', 'Data Penjemputan Sukses Tidak Dapat Dihapus');
            }
            $jemput->delete();

            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'target_user' => $jemput->nama_pelanggan,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'PenjemputanController.php',
                'action' => 'Hapus Penjemputan',
            ]);
            // End Log
            DB::commit();
            return redirect()->back()->with('success', 'Data Berhasil Dihapus');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Dihapus');
        }
    }

    public function set_status(Request $request, Pengajuan $jemput)
    {
        $request->validate([
            'status' => ['required', 'string'],
        ]);
        DB::beginTransaction();
        try {
            if ($jemput->status == 'lunas') {
                return redirect(route('penjemputan'))->with('error', 'Status Data Penjemputan Sukses Tidak Dapat Diganti');
            }
            $jemput->status = $request->status;
            $jemput->save();
            $pesan_notif = 'Permintaan penjemputan sampah telah di konfirmasi sukses, biaya layanan telah dimasukkan ke Kas TPST';
            $this->storeNotification('null', 'jemput', 'Permintaan Penjemputan Sampah', $pesan_notif);
            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'PenjemputanController.php',
                'action' => 'Set Status Penjemputan',
            ]);
            // End Log
            DB::commit();
            return redirect()->back()->with('success', 'Data Berhasil Diubah');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Diubah');
        }
    }

    public function edit(Pengajuan $jemput)
    {
        // Log Activity
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'PenjemputanController.php',
            'action' => 'Form Edit Penjemputan',
        ]);
        $config = Config::where('key', 'penjemputan')->first();

        // End Log
        if (Auth::user()->role != 6) {
            if ($jemput->status == 'lunas') {
                return redirect(route('penjemputan'))->with('error', 'Data Penjemputan Sukses Tidak Dapat Diganti');
            }
            return view('admin.page.pengajuan.edit', ['edit' => $jemput, 'config' => $config]);
        }
        abort(404);
    }

    public function filter(Request $request)
    {
        $request->validate([
            'tanggal_awal' => ['required', 'date', 'date_format:Y-m-d'],
            'tanggal_akhir' => ['required', 'after:tanggal_awal', 'date', 'date_format:Y-m-d'],
            'status' => ['required'],
        ]);
        if ($request->status == 'semua') {
            $pengajuan = Pengajuan::where('tanggal', '>=', $request->tanggal_awal)->where('tanggal', '<=', $request->tanggal_akhir)->get();
        } else {
            $pengajuan = Pengajuan::where('tanggal', '>=', $request->tanggal_awal)->where('tanggal', '<=', $request->tanggal_akhir)->where('status', $request->status)->get();
        }
        // Log Activity
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'PenjemputanController.php',
            'action' => 'Filter Penjemputan',
        ]);
        // End Log
        return view('admin.page.pengajuan.index', ['pengajuan' => $pengajuan]);
    }

    public function export(Request $request)
    {
        DB::beginTransaction();
        try {
            if ($request->tanggal_awal == null || $request->tanggal_akhir == null) {
                $data = Pengajuan::select('tanggal', 'nama_pelanggan', 'kontak_pelanggan', 'alamat_pelanggan', 'lokasi_ambil', 'jarak', 'biaya', 'status', 'users.name')->join('users', 'users.id', '=', 'pengajuan.user_id');
            } else {
                if ($request->status == 'semua') {
                    $data = Pengajuan::select('tanggal', 'nama_pelanggan', 'kontak_pelanggan', 'alamat_pelanggan', 'lokasi_ambil', 'jarak', 'biaya', 'status', 'users.name')->join('users', 'users.id', '=', 'pengajuan.user_id')->where('tanggal', '>=', $request->tanggal_awal)->where('tanggal', '<=', $request->tanggal_akhir);
                } else {
                    $data = Pengajuan::select('tanggal', 'nama_pelanggan', 'kontak_pelanggan', 'alamat_pelanggan', 'lokasi_ambil', 'jarak', 'biaya', 'status', 'users.name')->join('users', 'users.id', '=', 'pengajuan.user_id')->where('tanggal', '>=', $request->tanggal_awal)->where('tanggal', '<=', $request->tanggal_akhir)->where('status', $request->status);
                }
            }
            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'PenjemputanController.php',
                'action' => 'Export Excel Penjemputan',
            ]);
            // End Log
            DB::commit();
            return Excel::download(new PengajuanExport($data), 'export_data_pengajuan_penjemputan_sampah.xlsx');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Diekspor');
        }
    }

    public function update(Request $request, Pengajuan $jemput)
    {
        $request->validate([
            'date' => ['required', 'date'],
            'name' => ['required', 'string'],
            'no_telp' => ['required', 'min:9', 'max:15'],
            'alamat' => ['required', 'string'],
            'ambil' => ['required', 'string'],
            'jarak' => ['required'],
            'biaya' => ['nullable'],
        ]);
        DB::beginTransaction();
        try {
            $config = Config::where('key', 'penjemputan')->first();
            if ($config->status == 'deactive') {
                $biaya = $request->biaya;
            } else {
                $biaya = $request->jarak * $config->value;
            }

            $jemput->tanggal = $request->date;
            $jemput->nama_pelanggan = ucwords($request->name);
            $jemput->kontak_pelanggan = $request->no_telp;
            $jemput->alamat_pelanggan = ucwords($request->alamat);
            $jemput->lokasi_ambil = ucwords($request->ambil);
            $jemput->jarak = $request->jarak;
            $jemput->biaya = $biaya;
            $jemput->save();

            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'target_user' => $request->name,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'PenjemputanController.php',
                'action' => 'Update Penjemputan',
            ]);
            // End Log
            DB::commit();
            return redirect()->back()->with('success', 'Data Berhasil Diperbaharui');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Diperbaharui');
        }
    }
}
