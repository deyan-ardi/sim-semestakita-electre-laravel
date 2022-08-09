<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\User;
use App\Models\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SecurityController extends Controller
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
            'file' => 'SecurityController.php',
            'action' => 'Halaman Awal Setting Security',
        ]);
        // End Log
        return view('admin.page.security.index');
    }

    public function update(Request $request, User $user)
    {
        if ($request->email == $user->email) {
            $val = ['required', 'email'];
        } else {
            $val = ['required', 'email', 'unique:users'];
        }
        $request->validate([
            'password' => ['nullable', 'string', 'min:8', 'required_with:re-password', 'same:re-password'],
            're-password' => ['nullable', 'string', 'min:8', 'required_with:password', 'same:password'],
            'email' => $val,
        ]);

        DB::beginTransaction();
        try {
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

                        // Log Activity
                        LogActivity::create([
                            'ip_address' => request()->ip(),
                            'user_id' => Auth::user()->id,
                            'previous_url' => URL::previous(),
                            'current_url' => URL::current(),
                            'file' => 'SecurityController.php',
                            'action' => 'Ubah Setting Security',
                        ]);
                        // End Log
                    }
                );

                $link_security = url(route('ganti.email', [$user->id, 'expired' => $user->re_expired, 'token' => $user->re_token]));
                $link_name = $user->name;
                Controller::email_security($request->email, $link_security, $link_name);
                DB::commit();
                return redirect()->back()->with('success', 'Kami Telah Mengirimkan Kode Aktivasi Ke Alamat Email Baru Anda ');
            }
            if (! empty($request->password)) {
                $password = Hash::make($request->password);
            } else {
                $password = $user->password;
            }

            $user->re_email = $re_email;
            $user->email = $email;
            $user->password = $password;
            $user->save();

            DB::commit();
            return redirect(route('ganti.keamanan'))->with('success', 'Akun Berhasil Diperbaharui');
        } catch (Exception $e) {
            DB::rollback();
            dd($e);
            return redirect()->back()->with('error', 'Akun Gagal Diperbaharui');
        }
    }
}
