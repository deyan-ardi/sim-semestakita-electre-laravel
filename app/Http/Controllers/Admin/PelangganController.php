<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\User;
use App\Models\Config;
use App\Models\Tabungan;
use App\Models\LogActivity;
use Illuminate\Http\Request;
use App\Models\PembayaranRutin;
use App\Exports\PelangganExport;
use App\Imports\PelangganImport;
use Ramsey\Uuid\Nonstandard\Uuid;
use Illuminate\Support\Facades\DB;
use App\Exports\PelangganOneExport;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables as DataTablesDataTables;

class PelangganController extends Controller
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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (! empty($request->status) && ! empty($request->pembayaran_rutin)) {
            $pembayaran_rutin = $request->pembayaran_rutin;
            $status = $request->status;
            if (strcasecmp($status, 'aktif') == 0) {
                $status_iuran = 1;
            } elseif (strcasecmp($status, 'non-aktif') == 0) {
                $status_iuran = 0;
            } else {
                $status_iuran = 'semua';
            }

            if (strcasecmp($pembayaran_rutin, 'semua') == 0) {
                if (strcasecmp($status_iuran, 'semua') == 0) {
                    $jumlahPelanggan = User::where('role', 5)->get();
                } else {
                    $jumlahPelanggan = User::where('role', 5)->where('status_iuran', $status_iuran)->get();
                }
            } else {
                $find = PembayaranRutin::where('id', $pembayaran_rutin)->first();
                if (! empty($find)) {
                    if (strcasecmp($status_iuran, 'semua') == 0) {
                        $jumlahPelanggan = User::where('role', 5)->where('pembayaran_rutin_id', $pembayaran_rutin)->get();
                    } else {
                        $jumlahPelanggan = User::where('role', 5)->where('pembayaran_rutin_id', $pembayaran_rutin)->where('status_iuran', $status_iuran)->get();
                    }
                } else {
                    $jumlahPelanggan = User::where('role', 5)->get();
                }
            }
        } else {
            $jumlahPelanggan = User::where('role', 5)->get();
        }
        $dataPembayaranRutin = PembayaranRutin::all();
        // Log Activity
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'PelangganController.php',
            'action' => 'Halaman Awal Pelanggan',
        ]);
        // End Log
        return view('admin.page.pelanggan.index', compact(['jumlahPelanggan',  'dataPembayaranRutin']));
    }

    public function getAll(Request $request)
    {
        if (! empty($request->status) && ! empty($request->pembayaran_rutin)) {
            $pembayaran_rutin = $request->pembayaran_rutin;
            $status = $request->status;
            if (strcasecmp($status, 'aktif') == 0) {
                $status_iuran = 1;
            } elseif (strcasecmp($status, 'non-aktif') == 0) {
                $status_iuran = 0;
            } else {
                $status_iuran = 'semua';
            }

            if (strcasecmp($pembayaran_rutin, 'semua') == 0) {
                if (strcasecmp($status_iuran, 'semua') == 0) {
                    $dataNasabah = User::where('role', 5)->get();
                } else {
                    $dataNasabah = User::where('role', 5)->where('status_iuran', $status_iuran)->get();
                }
            } else {
                $find = PembayaranRutin::where('id', $pembayaran_rutin)->first();
                if (! empty($find)) {
                    if (strcasecmp($status_iuran, 'semua') == 0) {
                        $dataNasabah = User::where('role', 5)->where('pembayaran_rutin_id', $pembayaran_rutin)->get();
                    } else {
                        $dataNasabah = User::where('role', 5)->where('pembayaran_rutin_id', $pembayaran_rutin)->where('status_iuran', $status_iuran)->get();
                    }
                } else {
                    $dataNasabah = User::where('role', 5)->get();
                }
            }
        } else {
            $dataNasabah = User::where('role', 5)->get();
        }
        return DataTablesDataTables::of($dataNasabah)
            ->addIndexColumn()
            ->editColumn('no_member', function ($model) {
                if ($model->no_member == null) {
                    $return = 'Belum Disetel';
                } else {
                    $return = $model->no_member;
                }
                return $return;
            })

            ->editColumn('pembayaran_harian', function ($model) {
                if ($model->pembayaran_rutin == null) {
                    $return = 'Belum Disetel';
                } else {
                    $return = $model->pembayaran_rutin->nama_pembayaran . '--' . number_format($model->pembayaran_rutin->total_biaya, 2, ',', '.');
                }
                return $return;
            })
            ->editColumn('role', function ($model) {
                if ($model->role == 5) {
                    return 'Pelanggan';
                }
            })
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dataPembayaranRutin = PembayaranRutin::all();
        if ($dataPembayaranRutin->count() <= 0) {
            return redirect()->back()->with('error', 'Mohon mengisi data iuran rutin terlebih dahulu!');
        }
        // Log Activity
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'PelangganController.php',
            'action' => 'Halaman Buat Pelanggan',
        ]);
        // End Log
        return view('admin.page.pelanggan.tambah', compact(['dataPembayaranRutin']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->generate == 'generate') {
            $member = ['nullable', 'string', 'unique:users'];
        } else {
            $member = ['required', 'string', 'unique:users'];
        }
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'required_with:re-password', 'same:re-password'],
            're-password' => ['required', 'string', 'min:8', 'required_with:password', 'same:password'],
            'no_member' => $member,
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'unique:users'],
            'alamat' => ['required'],
            'no_telp' => ['required', 'numeric', 'digits_between:8,15', 'unique:users'],
            'pembayaran_rutin' => ['required', 'string'],
        ]);
        DB::beginTransaction();
        try {
            $find = PembayaranRutin::where('id', $request->pembayaran_rutin)->first();
            if (! empty($find)) {
                $pembayaran_rutin = $find->id;
            } else {
                DB::rollBack();
                return redirect()->back()->with('error', 'Pembayaran Rutin tidak ditemukan!');
            }

            if ($request->generate == 'generate') {
                $no_member = 'MB-' . date('Ymd') . '-' . rand(1000, 9999);
            } else {
                $no_member = $request->no_member;
            }

            $user = User::create([
                'id' => Uuid::uuid4(),
                'no_telp' => $request->no_telp,
                'no_member' => strtoupper($no_member),
                'name' => ucWords($request->name),
                'email' => strtolower($request->email),
                'alamat' => ucWords($request->alamat),
                'pembayaran_rutin_id' => $pembayaran_rutin,
                'role' => 5,
                'status_iuran' => 1,
                'password' => Hash::make($request->password),
            ]);

            // Create tabungan
            Tabungan::create([
                'id' => Uuid::uuid4(),
                'user_id' => $user->id,
                'debet' => 0,
                'kredit' => 0,
                'saldo' => 0,
            ]);

            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'target_user' => $user->name,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'PelangganController.php',
                'action' => 'Store Pelanggan',
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Data Berhasil Ditambahkan');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Ditambahkan');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pelanggan = User::findOrFail($id);
        $dataPembayaranRutin = PembayaranRutin::all();
        if ($dataPembayaranRutin->count() <= 0) {
            return redirect()->back()->with('error', 'Mohon mengisi data iuran rutin terlebih dahulu!');
        }
        // Log Activity
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'PelangganController.php',
            'action' => 'Halaman Ubah Pelanggan',
        ]);
        // End Log
        return view('admin.page.pelanggan.edit', compact(['pelanggan', 'dataPembayaranRutin']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $pelanggan = User::findOrFail($id);
        // Check User if email in db == email in input form
        if ($pelanggan->email == $request->email) {
            $valid_email =  ['required', 'email'];
        } else {
            $valid_email =  ['required', 'email', 'unique:users'];
        }

        // Check User if no_member in db == no_member in input form
        if ($pelanggan->no_member == $request->no_member) {
            $valid_no_member =  ['required', 'string'];
        } else {
            $valid_no_member =  ['required', 'string', 'unique:users'];
        }

        // Check User if no_telp in db == no_telp in input form
        if ($pelanggan->no_telp == $request->no_telp) {
            $valid_no_telp =  ['required', 'numeric', 'digits_between:8,15',];
        } else {
            $valid_no_telp =  ['required', 'unique:users', 'numeric', 'digits_between:8,15',];
        }
        $request->validate([
            'password' => ['nullable', 'string', 'min:8', 'required_with:re-password', 'same:re-password'],
            're-password' => ['nullable', 'string', 'min:8', 'required_with:password', 'same:password'],
            'no_member' => $valid_no_member,
            'name' => ['required', 'string', 'max:100'],
            'email' => $valid_email,
            'no_telp' => $valid_no_telp,
            'alamat' => ['required', 'string'],
            'role' => ['required', 'in:4,5'],
            'pembayaran_rutin' => ['required', 'string'],
        ]);
        DB::beginTransaction();
        try {
            $find = PembayaranRutin::where('id', $request->pembayaran_rutin)->first();
            if (! empty($find)) {
                $pembayaran_rutin = $find->id;
            } else {
                DB::rollBack();
                return redirect()->back()->with('error', 'Pembayaran Rutin tidak ditemukan!');
            }

            if ($request->password != null) {
                $pelanggan->password = Hash::make($request->password);
            }

            $pelanggan->no_telp = $request->no_telp;
            $pelanggan->no_member = strtoupper($request->no_member);
            $pelanggan->name = ucWords($request->name);
            $pelanggan->email = strtolower($request->email);
            $pelanggan->alamat = ucWords($request->alamat);
            $pelanggan->pembayaran_rutin_id = $pembayaran_rutin;
            $pelanggan->role = $request->role;
            $pelanggan->save();

            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'target_user' => $pelanggan->name,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'PelangganController.php',
                'action' => 'Update Data Pelanggan',
            ]);
            // End Log
            DB::commit();
            return redirect()->back()->with('success', 'Data Berhasil Diubah');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Diubah');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $pelanggan = User::where('id', $id)->firstOrFail();
            $find_tabungan = Tabungan::where('user_id', $id)->firstOrFail();
            $minimum_saldo = Config::where('key', 'sisa-tabungan')->where('status', 'active')->first();
            if (! is_null($minimum_saldo)) {
                if ($find_tabungan->saldo > $minimum_saldo->minimum_sisa) {
                    return redirect()->back()->with('error', 'Gagal Menghapus, Pelanggan Masih Memiliki Saldo Tabungan Yang Belum Ditarik Ketika Menjadi Nasabah');
                }
                $pelanggan->delete();
            } else {
                $pelanggan->delete();
            }
            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'target_user' => $pelanggan->name,
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'PelangganController.php',
                'action' => 'Destroy Pelanggan',
            ]);
            // End Log
            DB::commit();
            return redirect()->back()->with('success', 'Data Berhasil Dihapus');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Dihapus');
        }
    }

    public function destroyAll(Request $request)
    {
        DB::beginTransaction();
        try {
            $nasabah = User::whereIn('id', $request->id_user)->get();
            foreach ($nasabah as $item) {
                $minimum_saldo = Config::where('key', 'sisa-tabungan')->where('status', 'active')->first();
                $find_tabungan = Tabungan::where('user_id', $item->id)->first();
                if (! is_null($minimum_saldo)) {
                    if ($find_tabungan->saldo > $minimum_saldo->minimum_sisa) {
                        DB::rollback();
                        return response()->json(['success' => false, 'info' => 'Gagal Menghapus, Nasabah Masih Memiliki Saldo Tabungan Yang Belum Ditarik']);
                    }
                    DB::commit();
                    $item->delete();
                } else {
                    DB::commit();
                    $item->delete();
                }
                // Log Activity
                LogActivity::create([
                    'ip_address' => request()->ip(),
                    'user_id' => Auth::user()->id,
                    'target_user' => $item->name,
                    'previous_url' => URL::previous(),
                    'current_url' => URL::current(),
                    'file' => 'NasabahController.php',
                    'action' => 'Destroy Data Nasabah',
                ]);
                // End Log
            }
            return response()->json(['success' => true, 'info' => 'Data yang dipilih berhasil dihapus']);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'info' => $e]);
        }
    }

    public function export(Request $request, $status)
    {
        DB::beginTransaction();
        try {
            if ($status == 'filter') {
                $dataPembayaranRutin = PembayaranRutin::all();
                if ($dataPembayaranRutin->count() <= 0) {
                    return redirect()->back()->with('error', 'Mohon mengisi data iuran rutin terlebih dahulu!');
                }
                $pelanggan = User::query();

                if ($request->pembayaran_rutin || $request->status) {
                    $pembayaran_rutin = $request->pembayaran_rutin;
                    if ($request->pembayaran_rutin == 'semua') {
                        $pembayaran_rutin = strtoupper($request->pembayaran_rutin);
                    }

                    $status = strtoupper($request->status);
                    $status_iuran = null;
                    if ($status == 'AKTIF') {
                        $status_iuran = 1;
                    } elseif ($status == 'NON-AKTIF') {
                        $status_iuran = 0;
                    }

                    if ($pembayaran_rutin == 'SEMUA') {
                        if ($status != 'SEMUA') {
                            $pelanggan->where('status_iuran', $status_iuran);
                        }
                    } else {
                        if ($status == 'SEMUA') {
                            $pelanggan->where('pembayaran_rutin_id', $pembayaran_rutin);
                        } else {
                            $pelanggan->where('pembayaran_rutin_id', $pembayaran_rutin)->where('status_iuran', $status_iuran);
                        }
                    }
                }

                $pelanggan = $pelanggan->select('no_member', 'name', 'email', 'no_telp', 'pembayaran_rutin.nama_pembayaran', 'alamat')->join('pembayaran_rutin', 'pembayaran_rutin.id', '=', 'users.pembayaran_rutin_id')->where('role', 5)->orderBy('users.created_at', 'DESC');
                // Log Activity
                LogActivity::create([
                    'ip_address' => request()->ip(),
                    'user_id' => Auth::user()->id,
                    'previous_url' => URL::previous(),
                    'current_url' => URL::current(),
                    'file' => 'PelangganController.php',
                    'action' => 'Export Excel Pelanggan',
                ]);
                // End Log
                return Excel::download(new PelangganExport($pelanggan), 'export_data_pelanggan.xlsx');
            }
            $dataPembayaranRutin = PembayaranRutin::all();
            if ($dataPembayaranRutin->count() <= 0) {
                return redirect()->back()->with('error', 'Mohon mengisi data iuran rutin terlebih dahulu!');
            }
            $pelanggan = User::query();
            $pelanggan = $pelanggan->select('no_member', 'name', 'email', 'no_telp', 'pembayaran_rutin.nama_pembayaran', 'alamat')->join('pembayaran_rutin', 'pembayaran_rutin.id', '=', 'users.pembayaran_rutin_id')->where('role', 5)->orderBy('users.created_at', 'DESC');
            $pelanggan = $pelanggan->whereIn('users.id', json_decode($request->id_user));

            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'PelangganController.php',
                'action' => 'Export Excel Pelanggan',
            ]);
            // End Log
            return Excel::download(new PelangganExport($pelanggan), 'export_data_pelanggan.xlsx');

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Diekspor');
        }
    }

    public function exportone($id)
    {
        DB::beginTransaction();
        try {
            $dataPembayaranRutin = PembayaranRutin::all();
            if ($dataPembayaranRutin->count() <= 0) {
                return redirect()->back()->with('error', 'Mohon mengisi data iuran rutin terlebih dahulu!');
            }
            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'PelangganController.php',
                'action' => 'Export One Excel Pelanggan',
            ]);
            // End Log
            DB::commit();
            return Excel::download(new PelangganOneExport($id), 'export_satu_data_pelanggan.xlsx');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Diekspor');
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'mimes:xlsx', 'max:2048'],
        ]);
        $dataPembayaranRutin = PembayaranRutin::all();
        if ($dataPembayaranRutin->count() <= 0) {
            return redirect()->back()->with('error', 'Mohon mengisi data iuran rutin terlebih dahulu!');
        }
        Excel::import(new PelangganImport(), request()->file('file'));
        // Log Activity
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'PelangganController.php',
            'action' => 'Import Pelanggan',
        ]);
        // End Log
        return redirect()->back()->with('success', 'Data Berhasil Diimpor');
    }

    public function ubahStatus(Request $request)
    {
        DB::beginTransaction();
        try {
            $ids = $request->ids;
            $user = User::findOrFail($ids);
            if ($user->pembayaran_rutin_id != null) {
                $user->status_iuran = $request->setTo;
                $query = $user->save();
                if ($query) {
                    // Log Activity
                    LogActivity::create([
                        'ip_address' => request()->ip(),
                        'user_id' => Auth::user()->id,
                        'previous_url' => URL::previous(),
                        'current_url' => URL::current(),
                        'file' => 'PelangganController.php',
                        'action' => 'Update Status Pelanggan Sukses',
                    ]);
                    // End Log
                    DB::commit();
                    return response()->json(['success' => true]);
                }
            } else {
                // Log Activity
                LogActivity::create([
                    'ip_address' => request()->ip(),
                    'user_id' => Auth::user()->id,
                    'previous_url' => URL::previous(),
                    'current_url' => URL::current(),
                    'file' => 'PelangganController.php',
                    'action' => 'Update Status Pelanggan Gagal',
                ]);
                // End Log
                DB::rollback();
                return response()->json(['error' => true]);
            }
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => true]);
        }
    }

    public function cetakQR($id)
    {
        DB::beginTransaction();
        try {
            $user = User::where('id', $id)->get();
            $customPaper = [0, 0, 370.00, 283.80];
            $pdf = \PDF::loadView('pdf.cetak-qr', compact('user'))->setPaper($customPaper, 'landscape');
            DB::commit();
            return  $pdf->download('Kode QR Pelanggan -' . $user->first()->name . '.pdf');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Dicetak');
        }
    }

    public function cetakQRAll(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = User::whereIn('id', json_decode($request->id_user))->get();
            $customPaper = [0, 0, 370.00, 283.80];
            $pdf = \PDF::loadView('pdf.cetak-qr', compact('user'))->setPaper($customPaper, 'landscape');
            DB::commit();
            return $pdf->download('Kode QR Pelanggan.pdf');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Dicetak');
        }
    }

    public function ubahStatusAll(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = User::whereIn('id', $request->id_user)->get();
            foreach ($user as $item) {
                if ($item->pembayaran_rutin_id != null) {
                    $item->status_iuran = $item->status_iuran == 0 ? 1 : ($item->status_iuran == 1 ? 0 : 1);
                    $item->save();
                    // Log Activity
                    LogActivity::create([
                        'ip_address' => request()->ip(),
                        'user_id' => Auth::user()->id,
                        'previous_url' => URL::previous(),
                        'current_url' => URL::current(),
                        'file' => 'PelangganController.php',
                        'action' => 'Ubah Status Data Pelanggan Sukses',
                    ]);
                } else {
                    // Log Activity
                    LogActivity::create([
                        'ip_address' => request()->ip(),
                        'user_id' => Auth::user()->id,
                        'previous_url' => URL::previous(),
                        'current_url' => URL::current(),
                        'file' => 'PelangganController.php',
                        'action' => 'Ubah Status Data Pelanggan Gagal',
                    ]);
                    // End Log
                }
            }
            DB::commit();
            return response()->json(['success' => true, 'info' => 'Data yang dipilih berhasil diubah statusnya']);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'info' => $e]);
        }
    }
}
