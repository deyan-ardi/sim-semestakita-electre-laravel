<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use App\Models\Notifikasi;
use App\Models\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NotifikasiController extends Controller
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
        $notifikasi = Notifikasi::all();
        // Log Activity
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'NotifikasiController.php',
            'action' => 'Halaman Awal Notifikasi',
        ]);
        // End Log
        return view('admin.page.notifikasi.index', ['notifikasi' => $notifikasi]);
    }

    public function create()
    {
        $user = User::where('role', 4)->orWhere('role', 5)->get();
        // Log Activity
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'NotifikasiController.php',
            'action' => 'Form Tambah Notifikasi',
        ]);
        // End Log
        return view('admin.page.notifikasi.create', ['user' => $user]);
    }

    public function edit(Notifikasi $notifikasi)
    {
        $user = User::where('role', 4)->orWhere('role', 5)->get();
        // Log Activity
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'NotifikasiController.php',
            'action' => 'Form Ubah Notifikasi',
        ]);
        // End Log
        return view('admin.page.notifikasi.edit', ['user' => $user, 'notifikasi' => $notifikasi]);
    }
    public function filter(Request $request)
    {
        $request->validate([
            'tanggal_awal' => ['required', 'date', 'date_format:Y-m-d'],
            'tanggal_akhir' => ['required', 'after:tanggal_awal', 'date', 'date_format:Y-m-d'],
        ]);
        $notifikasi = Notifikasi::where('updated_at', '>=', $request->tanggal_awal . ' 00:00:00')->where('updated_at', '<=', $request->tanggal_akhir . ' 23:59:00')->get();
        // Log Activity
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'NotifikasiController.php',
            'action' => 'Filter Notifikasi',
        ]);
        // End Log
        return view('admin.page.notifikasi.index', ['notifikasi' => $notifikasi]);
    }
    public function update(Request $request, Notifikasi $notifikasi)
    {
        $request->validate([
            'user' => ['nullable', 'string'],
            'judul' => ['required', 'string', 'max:100'],
            'gambar' => ['nullable', 'mimes:png,jpeg,jpg', 'max:2048', 'image'],
            'konten' => ['required'],
        ]);
        DB::beginTransaction();
        try {
            if ($request->file('gambar')) {
                Storage::delete('public/' . $notifikasi->gambar);
                $imagePath = $request->file('gambar');
                $path = $imagePath->store('notifikasi', 'public');
            } else {
                $path = $notifikasi->gambar;
            }

            $notifikasi->user_id = $request->user;
            $notifikasi->judul = ucWords($request->judul);
            $notifikasi->konten = ucWords($request->konten);
            $notifikasi->gambar = $path;
            $notifikasi->save();

            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'target_user' => $notifikasi->user->name,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'NotifikasiController.php',
                'action' => 'Ubah Notifikasi',
            ]);
            // End Log

            // Send Email
            if ($request->file('gambar')) {
                $user = User::where('id', $request->user)->firstOrFail();
                $message = Controller::message_notifikasi(Auth::user()->name, $request->judul, $request->konten);
                // Upload file only work when Up To Hostiing
                $image_name = $request->file('gambar')->getClientOriginalName();
                $file = config('app.url') . Storage::url($path);
                // $file = "https://semestakita.id/assets/landing/img/mobile.png";
                if ($user->no_telp != '') {
                    Controller::sendMessageWithFile($user->no_telp, $file, $message);
                    Controller::email_notifikasi_file($user->email, $user->name, $request->konten, $request->judul, $file, $image_name);
                } else {
                    Controller::email_notifikasi_file($user->email, $user->name, ucWords($request->konten), ucWords($request->judul), $file, $image_name);
                }
            } else {
                $user = User::where('id', $request->user)->firstOrFail();
                $message = Controller::message_notifikasi(Auth::user()->name, $request->judul, $request->konten);
                if ($user->no_telp != '') {
                    // send message to whatsapp number
                    Controller::sendMessage($user->no_telp, $message);
                    Controller::email_notifikasi($user->email, $user->name, ucWords($request->konten), ucWords($request->judul));
                } else {
                    Controller::email_notifikasi($user->email, $user->name, ucWords($request->konten), ucWords($request->judul));
                }
            }
            DB::commit();
            return redirect()->back()->with('success', 'Data Berhasil Dikirim Ulang');
        } catch (Exception $e) {
            DB::rollback();
            dd($e);
            return redirect()->back()->with('error', 'Data Gagal Dikirim');
        }
    }
    public function store(Request $request)
    {
        $request->validate([
            'user' => ['nullable', 'string'],
            'judul' => ['required', 'string', 'max:100'],
            'gambar' => ['nullable', 'mimes:png,jpeg,jpg', 'max:2048', 'image'],
            'konten' => ['required'],
        ]);

        DB::beginTransaction();
        try {
            if ($request->file('gambar')) {
                $imagePath = $request->file('gambar');
                $path = $imagePath->store('notifikasi', 'public');
            } else {
                $path = null;
            }
            if ($request->pilih == 'semua') {
                $send_mail = User::where('role', 4)->orWhere('role', 5)->get();
            } elseif ($request->pilih == 'nasabah') {
                $send_mail = User::where('role', 4)->get();
            } elseif ($request->pilih == 'pelanggan') {
                $send_mail = User::where('role', 5)->get();
            } elseif ($request->pilih == 'custom') {
                $send_mail = User::where('id', $request->user)->get();
            }
            foreach ($send_mail as $send) {
                $user_notif = Notifikasi::create([
                    'id' => Uuid::uuid4(),
                    'user_id' => $send->id,
                    'judul' => ucWords($request->judul),
                    'konten' => ucWords($request->konten),
                    'gambar' => $path,
                ]);
                $pesan_notif = 'Pesan notifikasi baru telah dikirim untuk anda';
                $this->storeNotification($send->id, 'notif', 'Pesan Notifikasi', $pesan_notif);
                // Log Activity
                LogActivity::create([
                    'ip_address' => request()->ip(),
                    'user_id' => Auth::user()->id,
                    'target_user' => $user_notif->user->name,
                    'previous_url' => URL::previous(),
                    'current_url' => URL::current(),
                    'file' => 'NotifikasiController.php',
                    'action' => 'Store Notifikasi',
                ]);
                // End Log
            }
            if ($request->file('gambar')) {
                foreach ($send_mail as $user) {
                    $message = Controller::message_notifikasi(Auth::user()->name, $request->judul, $request->konten);
                    // Upload file only work when Up To Hostiing
                    $image_name = $request->file('gambar')->getClientOriginalName();
                    $file = config('app.url') . Storage::url($path);
                    // $file = "https://semestakita.id/assets/landing/img/mobile.png";
                    if ($user->no_telp != '') {
                        Controller::sendMessageWithFile($user->no_telp, $file, $message);
                        Controller::email_notifikasi_file($user->email, $user->name, $request->konten, $request->judul, $file, $image_name);
                    } else {
                        Controller::email_notifikasi_file($user->email, $user->name, ucWords($request->konten), ucWords($request->judul), $file, $image_name);
                    }

                    // Log Activity
                    LogActivity::create([
                        'ip_address' => request()->ip(),
                        'user_id' => Auth::user()->id,
                        'target_user' => $user->name,
                        'previous_url' => URL::previous(),
                        'current_url' => URL::current(),
                        'file' => 'NotifikasiController.php',
                        'action' => 'Send Notifikasi Dengan File',
                    ]);
                    // End Log
                }
            } else {
                foreach ($send_mail as $user) {
                    $message = Controller::message_notifikasi(Auth::user()->name, $request->judul, $request->konten);
                    if ($user->no_telp != '') {
                        // send message to whatsapp number
                        Controller::sendMessage($user->no_telp, $message);
                        Controller::email_notifikasi($user->email, $user->name, ucWords($request->konten), ucWords($request->judul));
                    } else {
                        Controller::email_notifikasi($user->email, $user->name, ucWords($request->konten), ucWords($request->judul));
                    }
                    // Log Activity
                    LogActivity::create([
                        'ip_address' => request()->ip(),
                        'user_id' => Auth::user()->id,
                        'target_user' => $user->name,
                        'previous_url' => URL::previous(),
                        'current_url' => URL::current(),
                        'file' => 'NotifikasiController.php',
                        'action' => 'Send Notifikasi Tanpa File',
                    ]);
                    // End Log
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Data Berhasil Dikirim');
        } catch (Exception $e) {
            DB::rollback();
            dd($e);
            return redirect()->back()->with('error', 'Data Gagal Dikirim');
        }
    }

    public function destroy(Notifikasi $notifikasi)
    {
        DB::beginTransaction();
        try {
            if (! empty($notifikasi->gambar)) {
                Storage::delete('public/' . $notifikasi->gambar);
            }
            $notifikasi->delete();
            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'target_user' => $notifikasi->user->name,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'NotifikasiController.php',
                'action' => 'Hapus Notifikasi',
            ]);
            // End Log
            DB::commit();
            return redirect()->back()->with('success', 'Data Berhasil Dihapus');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal Dihapus');
        }
    }
}
