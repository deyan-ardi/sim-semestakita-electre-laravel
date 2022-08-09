<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Carbon\Carbon;
use Seshac\Otp\Otp;
use App\Models\User;
use App\Models\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Encryption\DecryptException;

class ProfilController extends Controller
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
        // Log Activity
        LogActivity::create([
            'ip_address' => request()->ip(),
            'user_id' => Auth::user()->id,
            'previous_url' => URL::previous(),
            'current_url' => URL::current(),
            'file' => 'ProfilController.php',
            'action' => 'Halaman Index Profil',
        ]);
        // End Log
        return view('admin.page.profil.index');
    }

    public function update(Request $request, User $user)
    {
        if ($user->no_telp == $request->phone) {
            $phone = ['required', 'string', 'digits_between:8,15'];
        } else {
            $phone = ['required', 'string', 'digits_between:8,15', 'unique:users,no_telp'];
        }

        $request->validate([
            'phone' => $phone,
            'address' => ['nullable', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'profil' => ['mimes:jpeg,png,jpg', 'max:1024', 'image'],
        ]);

        DB::beginTransaction();
        try {
            if (! empty($user->foto)) {
                if ($request->file('profil')) {
                    Storage::delete('public/' . $user->foto);
                    $imagePath = $request->file('profil');
                    $path = $imagePath->store('users', 'public');
                } else {
                    $path = $user->foto;
                }
            } else {
                if ($request->file('profil')) {
                    $imagePath = $request->file('profil');
                    $path = $imagePath->store('users', 'public');
                } else {
                    $path = null;
                }
            }

            $user->alamat = ucWords($request->address);
            $user->name = ucwords($request->name);
            $user->foto = $path;
            $user->save();

            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'ProfilController.php',
                'action' => 'Update Profil',
            ]);
            // End Log

            if ($user->no_telp != $request->phone) {
                return $this->sendOtpWhatsapp($request);
            }
            DB::commit();
            return redirect()->back()->with('success', 'Data Berhasil Diubah');
        } catch (Exception $e) {
            DB::rollback();
            dd($e);
            return redirect()->back()->with('error', 'Data Gagal Diubah');
        }
    }

    public function sendOtpWhatsapp(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = User::where('id', Auth::user()->id)->first();
            Session::forget('request_change');
            if ($user != null) {
                $tokenResult = $user->createToken('myApp');
                $accessToken = $tokenResult->token;
                $expired =  $accessToken->expires_at = Carbon::now()->addMinutes(5);
                //generate OTP
                $otp = Otp::setValidity(1)  // otp validity time in mins
                    ->setMaximumOtpsAllowed(100)
                    ->setLength(4)  // Lenght of the generated otp
                    ->setOnlyDigits(true)  // generated otp contains mixed characters ex:ad2312
                    ->generate($request->phone);
                $accessToken->save();
                //the message
                $message = Controller::message_profil($user->name, $request->phone, $otp->token, $expired);
                if ($request->phone != '') {
                    try {
                        $send_wa = Controller::sendMessage($request->phone, $message);
                        if ($send_wa['kode'] != 200) {
                            DB::rollBack();
                            return redirect(route('ganti.profil'))->with('error', 'Terjadi Kesalahan Saat Mengirim Pesan');
                        }
                    } catch (Exception $e) {
                        DB::rollBack();
                        return redirect(route('ganti.profil'))->with('error', 'Perubahan Nomor WhatsApp Belum Dapat Dilakukan, Fitur Verifikasi OTP Saat Ini Masih Dalam Perbaikan');
                    }
                }
            } else {
                DB::rollBack();
                abort(500);
            }
            $session_data = [
                'request_from' => $user->id,
                'new_rekening' => null,
                'new_bank' => null,
                'phone' => $request->phone,
                'token' => Crypt::encrypt($otp->token),
                'expired' => $expired->timestamp,
            ];
            Session::put('request_change', $session_data);
            DB::commit();
            return redirect(route('ganti.profil.validasi'))->with('success', 'Kode OTP Berhasil Dikirim Ke Whatsapp Anda');
        } catch (Exception $error) {
            DB::rollBack();
            dd($error);
        }
    }

    public function inputToken()
    {
        $session_data = Session::get('request_change');
        if (! empty($session_data) && ! empty($session_data['phone'])) {
            $find = User::where('id', $session_data['request_from'])->firstOrFail();
            return view('admin.page.profil.inputToken', ['expired' => Carbon::createFromTimestamp($session_data['expired'])->format('M d, Y H:i:s'), 'user' => $find, 'expired_timestamp' => $session_data['expired'], 'id_page' => 3, 'phone' => $session_data['phone']]);
        }
        abort(404);
    }

    public function validasiToken(Request $request)
    {
        $request->validate([
            'otp1' => 'required|string|max:1',
            'otp2' => 'required|string|max:1',
            'otp3' => 'required|string|max:1',
            'otp4' => 'required|string|max:1',
        ]);
        DB::beginTransaction();
        try {
            $session_data = Session::get('request_change');
            $session_check_is_spam = Session::get('check_spam');
            if (! empty($session_data)) {
                $user = $session_data['request_from'];
                $tokenResult = $session_data['token'];
                $expired = $session_data['expired'];
                $token_input = $request->otp1 . $request->otp2 . $request->otp3 . $request->otp4;
                $find = User::where('id', $user)->firstOrFail();
                try {
                    $decrypted = Crypt::decrypt($tokenResult);
                    $expired_date = Carbon::createFromTimestamp($expired)->format('Y-m-d H:i:s');
                    if (! empty($session_check_is_spam) && $session_check_is_spam['attempt'] >= 3) {
                        Session::forget('check_spam');
                        DB::rollback();
                        return redirect(route('ganti.profil'))->with('error', 'Sistem Mendeteksi Tindakan Spam, Silahkan Ulangi');
                    }
                    if ($token_input == $decrypted) {
                        if (Carbon::now()->format('Y-m-d H:i:s') <= $expired_date) {
                            $find->no_telp = $session_data['phone'];
                            $find->save();
                            Session::forget('request_change');
                            Session::forget('check_spam');
                            DB::commit();
                            return redirect(route('ganti.profil'))->with('success', 'Berhasil Mengganti Data Nomor WhatsApp');
                        }
                        if (! empty($session_check_is_spam)) {
                            $attempt = $session_check_is_spam['attempt'] + 1;
                        } else {
                            $attempt = 0;
                        }
                        Session::put('check_spam', [
                            'attempt' => $attempt,
                        ]);
                        DB::rollback();
                        return redirect(route('ganti.profil.validasi'))->with('error', 'Kode OTP Telah Kedaluwarsa, Silahkan Kirim Ulang OTP');
                    }
                    if (! empty($session_check_is_spam)) {
                        $attempt = $session_check_is_spam['attempt'] + 1;
                    } else {
                        $attempt = 0;
                    }
                    Session::put('check_spam', [
                        'attempt' => $attempt,
                    ]);
                    DB::rollback();
                    return redirect(route('ganti.profil.validasi'))->with('error', 'Kode OTP Yang Dimasukkan Salah');
                } catch (DecryptException $error) {
                    DB::rollback();
                    return redirect(route('ganti.profil.validasi'))->with('error', 'Kesalahan Menguraikan Kode');
                }
            } else {
                DB::rollback();
                abort(404);
            }
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Internal Server Error');
        }
    }
}
