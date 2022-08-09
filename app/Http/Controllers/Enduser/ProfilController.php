<?php

namespace App\Http\Controllers\Enduser;

use Exception;
use Carbon\Carbon;
use Seshac\Otp\Otp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Encryption\DecryptException;

class ProfilController extends Controller
{
    public function index()
    {
        return view('enduser.page.profil.index');
    }

    public function aksi(Request $request, User $user)
    {
        if ($user->no_telp == $request->phone) {
            $phone = ['required', 'string', 'digits_between:8,15'];
        } else {
            $phone = ['required', 'string', 'digits_between:8,15', 'unique:users,no_telp'];
        }
        $validator = Validator::make($request->all(), [
            'phone' => $phone,
            'address' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'profil' => ['mimes:jpeg,png,jpg', 'max:1024', 'image'],
        ]);
        if ($validator->fails()) {
            $validator->validate();
            return redirect(route('enduser.profil.index'))->with('error', 'Gagal Mengubah Profil, Silahkan Periksa Inputan Anda');
        }
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
        DB::transaction(
            function () use ($request, $user, $path) {
                $user->alamat = ucWords($request->address);
                $user->name = ucwords($request->name);
                $user->foto = $path;
                $user->save();
            }
        );

        if ($user->no_telp != $request->phone) {
            return $this->sendOtpWhatsapp($request);
        }
        return redirect(route('enduser.profil.index'))->with('success', 'Berhasil Mengubah Profil');
    }

    public function aksi_rekening(Request $request, User $user)
    {
        try {
            if (! empty($user->no_rekening)) {
                $rekening = $user->no_rekening;
                if ($rekening == $request->no_rek) {
                    $valid_rekening = ['required', 'string'];
                } else {
                    $valid_rekening = ['required', 'string', 'unique:users,no_rekening'];
                }
            } else {
                $valid_rekening = ['required', 'string', 'unique:users,no_rekening'];
            }
            $validator = Validator::make($request->all(), [
                'bank' => ['required', 'string'],
                'no_rek' => $valid_rekening,
            ]);
            if ($validator->fails()) {
                $validator->validate();
                return redirect(route('enduser.profil.rekening'))->with('error', 'Gagal Mengubah Nomor Rekening, Silahkan Periksa Inputan Anda');
            }
            if (! empty($user->no_rekening) && $user->no_rekening == $request->no_rek && $request->bank == $user->nama_bank) {
                return redirect(route('enduser.profil.rekening'))->with('success', 'Data Disimpan, Tidak Terjadi Perubahan');
            }
            return $this->sendOtpWhatsapp($request);
        } catch (DecryptException $error) {
            dd($error);
            abort(500);
        }
    }

    public function view_rekening()
    {
        if (Auth::user()->role == 4) {
            return view('enduser.page.profil.rekening');
        }
        abort(404);
    }

    public function view_security()
    {
        return view('enduser.page.profil.security');
    }

    public function aksi_security(Request $request, User $user)
    {
        if ($request->email == $user->email) {
            $val = ['required', 'email'];
        } else {
            $val = ['required', 'email', 'unique:users'];
        }
        $validator = Validator::make($request->all(), [
            'password' => ['nullable', 'string', 'min:8', 'required_with:re-password', 'same:re-password'],
            're-password' => ['nullable', 'string', 'min:8'],
            'email' => $val,
        ]);
        if ($validator->fails()) {
            $validator->validate();
            return redirect(route('enduser.profil.security'))->with('error', 'Gagal Mengubah Keamanan Akun, Silahkan Periksa Inputan Anda');
        }
        if (strtolower($request->email) == strtolower($user->email)) {
            $email = $user->email;
            $re_email = null;
        } else {
            $email = $user->email;
            $string = '0123456789bcdfghjklmnpqrstvwxyz';
            $token = substr(str_shuffle($string), 0, 50);
            if (! empty($request->password)) {
                $password = Hash::make($request->password);
            } else {
                $password = $user->password;
            }
            DB::transaction(
                function () use ($request, $user, $token, $password) {
                    $user->re_email = $request->email;
                    $user->re_token = $token;
                    $user->re_expired = time() + 1800;
                    $user->password = $password;
                    $user->save();
                }
            );
            $link_security = url(route('enduser.profil.ganti.email', [$user->id, 'expired' => $user->re_expired, 'token' => $user->re_token]));
            $link_name = $user->name;
            Controller::email_security($request->email, $link_security, $link_name);
            return redirect()->back()->with('success', 'Kami Telah Mengirimkan Kode Aktivasi Ke Alamat Email Baru Anda ');
        }
        if (! empty($request->password)) {
            $password = Hash::make($request->password);
        } else {
            $password = $user->password;
        }
        DB::transaction(
            function () use ($user, $re_email, $email, $password) {
                $user->re_email = $re_email;
                $user->email = $email;
                $user->password = $password;
                $user->save();
            }
        );
        return redirect(route('enduser.profil.security'))->with('success', 'Akun Berhasil Diperbaharui');
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
                if (! empty($request->no_rek) && ! empty($request->bank)) {
                    if ($user->no_telp != '') {
                        $otp = Otp::setValidity(1)  // otp validity time in mins
                            ->setMaximumOtpsAllowed(100)
                            ->setLength(4)  // Lenght of the generated otp
                            ->setOnlyDigits(true)  // generated otp contains mixed characters ex:ad2312
                            ->generate($user->no_telp);
                        $accessToken->save();
                        //the message
                        $message = Controller::message_rekening($user->name, $request->bank, $request->no_rek, $otp->token, $expired);
                        Controller::sendMessage($request->phone, $message);
                        Controller::email_otp($user->email, $otp->token, $user->name, $expired);
                    } else {
                        $otp = Otp::setValidity(1)  // otp validity time in mins
                            ->setMaximumOtpsAllowed(100)
                            ->setLength(4)  // Lenght of the generated otp
                            ->setOnlyDigits(true)  // generated otp contains mixed characters ex:ad2312
                            ->generate($user->email);
                        $accessToken->save();
                        Controller::email_otp($user->email, $otp->token, $user->name, $expired);
                    }
                } else {
                    $otp = Otp::setValidity(1)  // otp validity time in mins
                        ->setMaximumOtpsAllowed(100)
                        ->setLength(4)  // Lenght of the generated otp
                        ->setOnlyDigits(true)  // generated otp contains mixed characters ex:ad2312
                        ->generate($request->phone);
                    $accessToken->save();
                    //the message
                    $message = Controller::message_profil($user->name, $request->phone, $otp->token, $expired);
                    if ($request->phone != '') {
                        $send_wa = Controller::sendMessage($request->phone, $message);
                        if ($send_wa['kode'] != 200) {
                            DB::rollBack();
                            return redirect(route('enduser.profil.index'))->with('error', 'Fitur ini sedang dalam perbaikan, silahkan coba lagi nanti');
                        }
                    }
                }
            } else {
                DB::rollBack();
                abort(500);
            }

            if (! empty($request->no_rek) && ! empty($request->bank)) {
                $session_data = [
                    'request_from' => $user->id,
                    'new_rekening' => $request->no_rek,
                    'new_bank' => $request->bank,
                    'phone' => null,
                    'token' => Crypt::encrypt($otp->token),
                    'expired' => $expired->timestamp,
                ];
                Session::put('request_change', $session_data);
                DB::commit();
                return redirect(route('enduser.validasi.token.rekening'))->with('success', 'Kode OTP Berhasil Dikirim Ke Whatsapp dan Email Anda Anda');
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
            return redirect(route('enduser.validasi.token.phone'))->with('success', 'Kode OTP Berhasil Dikirim Ke Whatsapp Anda');
        } catch (Exception $error) {
            DB::rollBack();
            dd($error);
            abort(500);
        }
    }

    public function validasiTokenRekening()
    {
        $session_data = Session::get('request_change');
        if (! empty($session_data) && ! empty($session_data['new_rekening'] && ! empty($session_data['new_bank']))) {
            $find = User::where('id', $session_data['request_from'])->firstOrFail();

            return view('enduser.page.profil.tokenValidasiRekening', ['expired' => Carbon::createFromTimestamp($session_data['expired'])->format('M d, Y H:i:s'), 'user' => $find, 'expired_timestamp' => $session_data['expired'], 'bank' => $session_data['new_bank'], 'rekening' => $session_data['new_rekening']]);
        }
        abort(404);
    }

    public function validasiTokenPhone()
    {
        $session_data = Session::get('request_change');
        if (! empty($session_data) && ! empty($session_data['phone'])) {
            $find = User::where('id', $session_data['request_from'])->firstOrFail();

            return view('enduser.page.profil.tokenValidasiPhone', ['expired' => Carbon::createFromTimestamp($session_data['expired'])->format('M d, Y H:i:s'), 'user' => $find, 'expired_timestamp' => $session_data['expired'], 'phone' => $session_data['phone']]);
        }
        abort(404);
    }

    public function validasiTokenAksi(Request $request)
    {
        $request->validate([
            'otp1' => 'required|string|max:1',
            'otp2' => 'required|string|max:1',
            'otp3' => 'required|string|max:1',
            'otp4' => 'required|string|max:1',
        ]);
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
                    // return $this->sendLockoutResponse($request);
                    if (! empty($session_data['new_rekening']) && ! empty($session_data['new_bank'])) {
                        return redirect(route('enduser.profil.rekening'))->with('error', 'Sistem Mendeteksi Tindakan Spam, Silahkan Ulangi');
                    }
                    return redirect(route('enduser.profil.index'))->with('error', 'Sistem Mendeteksi Tindakan Spam, Silahkan Ulangi');
                }

                if ($token_input == $decrypted) {
                    if (Carbon::now()->format('Y-m-d H:i:s') <= $expired_date) {
                        if (! empty($session_data['new_rekening']) && ! empty($session_data['new_bank'])) {
                            $find->no_rekening = $session_data['new_rekening'];
                            $find->nama_bank = $session_data['new_bank'];
                            $find->save();
                            Session::forget('request_change');
                            Session::forget('check_spam');
                            return redirect(route('enduser.profil.rekening'))->with('success', 'Berhasil Mengganti Data Rekening');
                        }
                        $find->no_telp = $session_data['phone'];
                        $find->save();
                        Session::forget('request_change');
                        Session::forget('check_spam');
                        return redirect(route('enduser.profil.index'))->with('success', 'Berhasil Mengganti Data Nomor Whatsapp');
                    }
                    if (! empty($session_check_is_spam)) {
                        $attempt = $session_check_is_spam['attempt'] + 1;
                    } else {
                        $attempt = 0;
                    }
                    Session::put('check_spam', [
                        'attempt' => $attempt,
                    ]);
                    if (! empty($session_data['new_rekening']) && ! empty($session_data['new_bank'])) {
                        return redirect(route('enduser.validasi.token.rekening'))->with('error', 'Kode OTP Telah Kedaluwarsa, Silahkan Kirim Ulang OTP');
                    }
                    return redirect(route('enduser.validasi.token.phone'))->with('error', 'Kode OTP Telah Kedaluwarsa, Silahkan Kirim Ulang OTP');
                }
                if (! empty($session_check_is_spam)) {
                    $attempt = $session_check_is_spam['attempt'] + 1;
                } else {
                    $attempt = 0;
                }
                Session::put('check_spam', [
                    'attempt' => $attempt,
                ]);
                if (! empty($session_data['new_rekening']) && ! empty($session_data['new_bank'])) {
                    return redirect(route('enduser.validasi.token.rekening'))->with('error', 'Kode OTP Yang Dimasukkan Salah');
                }
                return redirect(route('enduser.validasi.token.phone'))->with('error', 'Kode OTP Yang Dimasukkan Salah');
            } catch (DecryptException $error) {
                dd($error);
            }
        } else {
            abort(404);
        }
    }
}
