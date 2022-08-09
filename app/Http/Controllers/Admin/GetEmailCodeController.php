<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\User;
use App\Models\LogActivity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class GetEmailCodeController extends Controller
{
    public function ganti_email(User $user)
    {
        DB::beginTransaction();
        try {
            if (! empty($user) && ! empty($user->re_email) && ! empty($user->re_token) && ! empty($user->re_expired)) {
                if ($user->re_token == request()->token) {
                    if (time() <= $user->re_expired) {
                        $user->email = $user->re_email;
                        $user->re_token = null;
                        $user->re_email = null;
                        $user->re_expired = null;
                        $user->save();

                        // Log Activity
                        LogActivity::create([
                            'ip_address' => request()->ip(),
                            'user_id' => $user->id,
                            'previous_url' => URL::previous(),
                            'current_url' => URL::current(),
                            'file' => 'GetEmailCodeController.php',
                            'action' => 'Token Check dan Ganti Email Security',
                        ]);
                        // End Log
                        Auth::logout();
                        DB::commit();
                        return redirect(route('login'))->with('success', 'Email Berhasil Diubah, Silahkan Login Ulang');
                    }
                    $user->re_token = null;
                    $user->re_email = null;
                    $user->re_expired = null;
                    $user->save();
                    DB::commit();
                    return abort(403, 'Expired');
                }
                DB::rollback();
                return abort(403, 'Invalid Signature');
            }
            DB::rollback();
            return abort(403, 'Invalid Signature');
        } catch (Exception $e) {
            DB::rollback();
            return abort(500);
        }
    }
}
