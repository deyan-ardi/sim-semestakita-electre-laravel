<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function forgetPasswordStore(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $find = User::where('email', $request->email)->first();
        if (! empty($find)) {
            $token = Uuid::uuid6()->toString();
            DB::table('password_resets')->where('email', $find->email)->delete();
            $link_security = url(route('reset.password.getEmail', ['token' => $token, 'email' => $request->email]));
            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now(),
            ]);
            Controller::email_reset($request->email, $link_security, $request->email);
            return redirect()->back()->with('success', 'Email untuk reset kata sandi dikirim');
        }
        return redirect()->back()->with('error', 'Akun dengan Email tersebut tidak ditemukan');
    }

    public function resetPassword(Request $request, $token)
    {
        if (! empty($token) && ! empty($request->email)) {
            $reset =  DB::table('password_resets')->where('token', $token)->where('email', $request->email)->first();
            if (! empty($reset)) {
                $expired = Carbon::parse($reset->created_at)->addMinutes(30)->format('Y-m-d H:i:s');
                $now = Carbon::now()->format('Y-m-d H:i:s');
                if ($now >= $expired) {
                    DB::table('password_resets')->where('email', $request->email)->delete();
                    return redirect(route('password.request'))->with('error', 'Link telah kedaluwarsa');
                }
                return view('auth.passwords.reset', ['token' => $token, 'email' => $request->email]);
            }
            return redirect(route('password.request'))->with('error', 'Permintaan Reset Kata Sandi dengan Email tersebut tidak ditemukan');
        }
        return abort(403, 'Invalid Signature');
    }

    public function resetPasswordStore(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed|same:password_confirmation',
            'password_confirmation' => 'required|min:8|same:password',
        ]);

        $find = User::where('email', $request->email)->first();
        $reset = DB::table('password_resets')->where('token', $request->token)->where('email', $request->email)->first();
        if (! empty($find) && ! empty($reset)) {
            $expired = Carbon::parse($reset->created_at)->addMinutes(30)->format('Y-m-d H:i:s');
            $now = Carbon::now()->format('Y-m-d H:i:s');
            if ($now <= $expired) {
                User::where('email', $find->email)->update(['password' => Hash::make($request->password)]);
                DB::table('password_resets')->where(['email' => $find->email])->delete();
                return redirect(route('login'))->with('success', 'Kata Sandi Berhasil Diubah');
            }
            return redirect(route('password.request'))->with('error', 'Link telah kedaluwarsa');
        }
        return redirect(route('password.request'))->with('error', 'Permintaan Reset Kata Sandi dengan Email tersebut tidak ditemukan');
    }
}
