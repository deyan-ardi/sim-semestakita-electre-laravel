<?php

namespace App\Http\Controllers\Enduser;

use Exception;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Models\PengaduanUser;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class HubungiController extends Controller
{
    public function index()
    {
        $feedback = PengaduanUser::where('user_id', Auth::user()->id)->orderBy('updated_at', 'DESC')->get();
        return view('enduser.page.hubungi.index', compact('feedback'));
    }

    public function tambah_feedback()
    {
        return view('enduser.page.hubungi.tambah');
    }

    public function ubah_feedback($id)
    {
        $find = PengaduanUser::where('id', $id)->where('user_id', Auth::user()->id)->firstOrfail();
        return view('enduser.page.hubungi.ubah', compact('find'));
    }

    public function store(Request $request)
    {
        $validator = $this->_validator($request->all());

        if ($validator->fails()) {
            $validator->validate();
            return redirect(route('enduser.hubungi.tambah'))->with('error', 'Gagal Menambahkan Feedback Baru, Periksa Inputan Anda');
        }
        DB::beginTransaction();
        try {
            if ($request->file('gambar')) {
                $imagePath = $request->file('gambar');
                $path = $imagePath->store('feedback', 'public');
            } else {
                $path = null;
            }

            PengaduanUser::create([
                'id' => Uuid::uuid4(),
                'user_id' => Auth::user()->id,
                'judul' => ucWords($request->judul),
                'kategori' => $request->kategori,
                'konten' => ucWords($request->konten),
                'gambar' => $path,
            ]);
            $pesan_notif = 'Pengaduan Layanan baru telah masuk ke sistem, Silahkan ditanggapi';
            $this->storeNotification('null', 'pengaduan', 'Pengaduan Layanan TPST', $pesan_notif);
            if ($request->file('gambar')) {
                $imageFile = $request->file('gambar');
                $imageName = $imageFile->getClientOriginalName();
                $users = User::where('role', 1)->orWhere('role', 2)->orWhere('role', 3)->get();
                foreach ($users as $user) {
                    $message = Controller::message_notifikasi(Auth::user()->name, $request->judul, $request->konten);
                    // Upload file only work when Up To Hostiing
                    $file = config('app.url') . Storage::url($path);
                    // $file = "https://semestakita.id/assets/landing/img/mobile.png";
                    if ($user->no_telp != '') {
                        Controller::sendMessageWithFile($user->no_telp, $file, $message);
                        Controller::email_notifikasi_file($user->email, $user->name, $request->konten, $request->judul, $file, $imageName);
                    } else {
                        Controller::email_notifikasi_file($user->email, $user->name, ucWords($request->konten), ucWords($request->judul), $file, $imageName);
                    }
                }
            } else {
                $users = User::where('role', 1)->orWhere('role', 2)->orWhere('role', 3)->get();
                foreach ($users as $user) {
                    $message = Controller::message_notifikasi(Auth::user()->name, $request->judul, $request->konten);
                    if ($user->no_telp != '') {
                        // send message to whatsapp number
                        Controller::sendMessage($user->no_telp, $message);
                        Controller::email_notifikasi($user->email, $user->name, ucWords($request->konten), ucWords($request->judul));
                    } else {
                        Controller::email_notifikasi($user->email, $user->name, ucWords($request->konten), ucWords($request->judul));
                    }
                }
            }
            DB::commit();
            return redirect(route('enduser.hubungi.index'))->with('success', 'Berhasil Mengirimkan Feedback Ke Admin');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi Kesalahan Saat Mengirimkan Pesan');
        }
    }

    public function update(Request $request, $id)
    {
        $validator = $this->_validator($request->all());
        if ($validator->fails()) {
            $validator->validate();
            return redirect(route('enduser.hubungi.ubah'))->with('error', 'Gagal Mengubah Feedback, Periksa Inputan Anda');
        }
        DB::beginTransaction();
        try {
            if ($request->file('gambar')) {
                $imagePath = $request->file('gambar');
                $path = $imagePath->store('feedback', 'public');
            } else {
                $path = null;
            }

            $find = PengaduanUser::where('id', $id)->where('user_id', Auth::user()->id)->firstOrfail();
            $find->judul = ucWords($request->judul);
            $find->kategori = $request->kategori;
            $find->konten = ucWords($request->konten);
            $find->gambar = $path;
            $find->save();
            if ($request->file('gambar')) {
                $imageFile = $request->file('gambar');
                $imageName = $imageFile->getClientOriginalName();
                $users = User::where('role', 1)->orWhere('role', 2)->orWhere('role', 3)->get();
                foreach ($users as $user) {
                    $message = Controller::message_notifikasi(Auth::user()->name, $request->judul, $request->konten);
                    // Upload file only work when Up To Hostiing
                    $file = config('app.url') . Storage::url($path);
                    // $file = "https://semestakita.id/assets/landing/img/mobile.png";
                    if ($user->no_telp != '') {
                        Controller::sendMessageWithFile($user->no_telp, $file, $message);
                        Controller::email_notifikasi_file($user->email, $user->name, $request->konten, $request->judul, $file, $imageName);
                    } else {
                        Controller::email_notifikasi_file($user->email, $user->name, ucWords($request->konten), ucWords($request->judul), $file, $imageName);
                    }
                }
            } else {
                $users = User::where('role', 1)->orWhere('role', 2)->orWhere('role', 3)->get();
                foreach ($users as $user) {
                    $message = Controller::message_notifikasi(Auth::user()->name, $request->judul, $request->konten);
                    if ($user->no_telp != '') {
                        // send message to whatsapp number
                        Controller::sendMessage($user->no_telp, $message);
                        Controller::email_notifikasi($user->email, $user->name, ucWords($request->konten), ucWords($request->judul));
                    } else {
                        Controller::email_notifikasi($user->email, $user->name, ucWords($request->konten), ucWords($request->judul));
                    }
                }
            }
            DB::commit();
            return redirect(route('enduser.hubungi.index'))->with('success', 'Berhasil Mengubah Feedback Dan Mengirimkan Feedback Ke Admin');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi Kesalahan Saat Mengubah Pesan');
        }
    }

    public function filter(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal_awal' => ['required', 'date'],
            'tanggal_akhir' => ['required', 'after:tanggal_awal'],
            'status' => ['required', 'string'],
        ]);
        if ($validator->fails()) {
            $validator->validate();
            return redirect(route('enduser.hubungi.index'))->with('error', 'Gagal Memfilter Data, Periksa Inputan Anda');
        }
        if (ucWords($request->status) == 'Semua') {
            $feedback = PengaduanUser::where('updated_at', '>=', $request->tanggal_awal . ' 00:00:00')->where('updated_at', '<=', $request->tanggal_akhir . ' 23:59:00')->where('user_id', Auth::user()->id)->get();
        } else {
            $feedback = PengaduanUser::where('updated_at', '>=', $request->tanggal_awal . ' 00:00:00')->where('updated_at', '<=', $request->tanggal_akhir . ' 23:59:00')->where('user_id', Auth::user()->id)->where('kategori', ucWords($request->status))->get();
        }
        return view('enduser.page.hubungi.index', compact('feedback'));
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $find = PengaduanUser::where('id', $id)->where('user_id', Auth::user()->id)->firstOrfail();
            if (! empty($find->gambar)) {
                Storage::delete('public/' . $find->gambar);
            }
            $find->delete();
            DB::commit();
            return redirect(route('enduser.hubungi.index'))->with('success', 'Berhasil Menghapus Feedback');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi Kesalahan Saat Menghapus Pesan');
        }
    }
    public function _validator(array $data)
    {
        return Validator::make($data, [
            'judul' => ['required', 'string', 'max:100'],
            'gambar' => ['nullable', 'mimes:png,jpeg', 'max:2048', 'image'],
            'konten' => ['required'],
            'kategori' => ['required'],
        ]);
    }
}
