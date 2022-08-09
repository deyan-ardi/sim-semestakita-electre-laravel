<?php

namespace App\Http\Controllers\Enduser;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class GetEmailController extends Controller
{
    public function ganti_email(User $user)
    {
        if (! empty($user) && ! empty($user->re_email) && ! empty($user->re_token) && ! empty($user->re_expired)) {
            if ($user->re_token == request()->token) {
                if (time() <= $user->re_expired) {
                    DB::transaction(
                        function () use ($user) {
                            $user->email = $user->re_email;
                            $user->re_token = null;
                            $user->re_email = null;
                            $user->re_expired = null;
                            $user->save();
                        }
                    );
                    Auth::logout();

                    return redirect(route('login'))->with('success', 'Email Berhasil Diubah, Silahkan Login Ulang');
                }
                DB::transaction(
                    function () use ($user) {
                        $user->re_token = null;
                        $user->re_email = null;
                        $user->re_expired = null;
                        $user->save();
                    }
                );
                return abort(403, 'Expired');
            }
            return abort(403, 'Invalid Signature');
        }
        return abort(403, 'Invalid Signature');
    }
}
