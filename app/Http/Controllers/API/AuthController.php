<?php

namespace App\Http\Controllers\API;

use Exception;
use Carbon\Carbon;
use Seshac\Otp\Otp;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use App\Models\LogActivity;
use Laravel\Passport\Token;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class AuthController extends Controller
{
    use ThrottlesLogins;
    use AuthenticatesUsers;
    public function __construct()
    {
        $this->middleware('auth:api')->except(['login', 'logoutOtherDevice']);
    }
    protected function hasTooManyLoginAttempts(Request $request)
    {
        $attempts = 3;
        $lockoutMinutes = 5;
        return $this->limiter()->tooManyAttempts(
            $this->throttleKey($request),
            $attempts,
            $lockoutMinutes
        );
    }

    public function fetch()
    {
        DB::beginTransaction();
        try {
            $user = User::where('id', Auth()->user()->id)->first();
            if (! $user) {
                return ResponseFormatter::error([
                    'success' => false,
                    'message' => 'Data tidak ditemukan',
                ], 'Not Found', 404);
            }
            // Log Activity
            LogActivity::create([
                'ip_address' => request()->ip(),
                'user_id' => Auth::user()->id,
                'previous_url' => URL::previous(),
                'current_url' => URL::current(),
                'file' => 'API\AuthController.php',
                'action' => 'Mengambil Data User Login Via API',
            ]);
            // End Log
            DB::commit();
            return ResponseFormatter::success(
                $user,
                'Data User Didapat'
            );
        } catch (Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'success' => false,
                'message' => $e,
            ], 'Internal Server Error', 500);
        }
    }

    public function login(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'no_telp' => 'required',
                'remember_me' => 'boolean',
            ]);
            if ($this->hasTooManyLoginAttempts($request)) {
                $this->fireLockoutEvent($request);
                // return $this->sendLockoutResponse($request);
                return ResponseFormatter::error([
                    'success' => false,
                    'message' => 'Terlalu Banyak Percobaan Login',
                ], 'Authentication Failed', 401);
            }
            $this->incrementLoginAttempts($request);
            $user = User::where('role', 3)->where('no_telp', $request->no_telp)->first();
            if ($user != null) {
                //get token by usr id
                if ($user->is_mobile == 1) {
                    return ResponseFormatter::error([
                        'success' => false,
                        'message' => 'Akun sudah Login diperangkat Lain',
                    ], 'Multiple Device Login Found', 403);
                }

                $token =
                    Token::where('user_id', $user->id)->orderBy('created_at', 'DESC')->first();
                if ($token != null) {
                    //update the access token revoked to true
                    Token::where('user_id', $user->id)
                        ->update(['revoked' => true]);
                }
                $tokenResult = $user->createToken('myApp');
                $accessToken = $tokenResult->token;
                $expired =  $accessToken->expires_at = Carbon::now()->addMinutes(5);

                //generate OTP
                $otp = Otp::setValidity(1)  // otp validity time in mins
                    ->setMaximumOtpsAllowed(100)
                    ->setLength(4)  // Lenght of the generated otp
                    ->setOnlyDigits(true)  // generated otp contains mixed characters ex:ad2312
                    ->generate($user->no_telp);
                $accessToken->save();

                //the message
                $message = Controller::message_otp($user->name, $otp->token, $expired);
                if ($request->no_telp != '') {
                    // send message to whatsapp number
                    // Static Method
                    $send_wa = Controller::sendMessage($user->no_telp, $message);
                    $send_email = Controller::email_otp($user->email, $otp->token, $user->name, $expired);
                    if ($send_wa['kode'] != 200 && $send_email['kode'] != 200) {
                        DB::rollback();
                        return ResponseFormatter::error([
                            'message' => 'Something went wrong',
                            'error' => 'Fitur sedang dalam perbaikan, silahkan coba lagi nanti',
                        ], 'Internal Server Error', 500);
                    }
                }
                $user->is_mobile = 1;
                $user->save();
                // Log Activity
                LogActivity::create([
                    'ip_address' => request()->ip(),
                    'user_id' => $user->id,
                    'previous_url' => URL::previous(),
                    'current_url' => URL::current(),
                    'file' => 'API\AuthController.php',
                    'action' => 'Login Ke Dalam Sistem Melalui API',
                ]);
                // End Log
                DB::commit();
                return ResponseFormatter::success([
                    'success' => true,
                    'access_token' =>
                    $tokenResult->accessToken,
                    'expires_at' => Carbon::parse(
                        $expired
                    )->toDateTimeString(),
                    'user' => $user,
                    'otp' => $otp->token,
                    'return' =>  Auth::user(),
                ], 'Authenticated');
            }
            DB::rollBack();
            return ResponseFormatter::error([
                'success' => false,
                'message' => 'Nomor hp tidak terdaftar pada sistem kami',
            ], 'Authentication Failed', 401);
        } catch (Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'success' => false,
                'message' => $e,
            ], 'Internal Server Error', 500);
        }
    }

    public function checkTokenUser()
    {
        DB::beginTransaction();
        try {
            $token = Token::where('user_id', Auth()->user()->id)->orderBy('created_at', 'DESC')->first();
            if ($token != null) {
                if ($token->revoked == false || Carbon::parse($token->expires_at)->format('Y-m-d H:i:s') >= Carbon::now()) {
                    DB::commit();
                    return ResponseFormatter::success([
                        'success' => true,
                        'message' => 'Token Valid',
                    ], 'Authenticated');
                }
            }
            DB::rollback();
            return ResponseFormatter::error([
                'success' => false,
                'message' => 'Token tidak valid',
                'data' => $token,
            ], 'Unauthenticated', 401);
        } catch (Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'success' => false,
                'message' => $e,
            ], 'Internal Server Error', 500);
        }
    }

    public function logoutOtherDevice(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'no_telp' => 'required',
                'remember_me' => 'boolean',
            ]);
            $user = User::where('role', 3)->where('no_telp', $request->no_telp)->first();
            $token = Token::where('user_id', $user->id)->orderBy('created_at', 'DESC')->get();
            if ($token->count() > 0) {
                foreach ($token as $u) {
                    $u->revoked = true;
                    $u->save();
                }
                $user->is_mobile = 0;
                $user->save();
                DB::commit();
                return ResponseFormatter::success($token, 'Other Device Log Out');
            }
            DB::rollback();
            return ResponseFormatter::error([
                'sucess' => false,
                'message' => 'Tidak Ada Token Ditemukan',
            ], 'Authentication Failed', 401);
        } catch (Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'success' => false,
                'message' => $e,
            ], 'Internal Server Error', 500);
        }
    }
    public function logout(Request $request)
    {
        DB::beginTransaction();
        try {
            $u = $request->user()->token()->revoke();
            if ($u) {
                $request->user()->is_mobile = 0;
                $request->user()->save();
                // Log Activity
                LogActivity::create([
                    'ip_address' => request()->ip(),
                    'user_id' => $request->user()->id,
                    'previous_url' => URL::previous(),
                    'current_url' => URL::current(),
                    'file' => 'API\AuthController.php',
                    'action' => 'Logout Via API',
                ]);
                // End Log
                DB::commit();
                return ResponseFormatter::success($u, 'Token Revoked');
            }
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Unauthorized',
            ], 'Failed', 401);
        } catch (Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'success' => false,
                'message' => $e,
            ], 'Internal Server Error', 500);
        }
    }

    public function updateProfile(Request $request)
    {
        $this->validate($request, [
            'name'     => 'required',
            'alamat'   => 'required',
        ]);
        DB::beginTransaction();
        try {
            $user = User::find(Auth::user()->id);

            //jika validasi berhasil maka lakukan update
            $user->update([
                'name'     => $request->name,
                'alamat'   => $request->alamat,
            ]);

            if ($user) {
                // Log Activity
                LogActivity::create([
                    'ip_address' => request()->ip(),
                    'user_id' => Auth::user()->id,
                    'previous_url' => URL::previous(),
                    'current_url' => URL::current(),
                    'file' => 'API\AuthController.php',
                    'action' => 'Mengupdate Data Profil',
                ]);
                // End Log

                DB::commit();
                return ResponseFormatter::success([
                    'success' => true,
                    'user' => $user,
                ], 'Update profile berhasil');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'success' => false,
                'message' => $e,
            ], 'Internal Server Error', 500);
        }
    }

    public function updatePhoto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'foto' => 'required|image|max:2048',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(['error' => $validator->errors()], 'Validasi gagal', 400);
        }
        DB::beginTransaction();
        try {
            $data = Auth::user();
            $user = User::find($data->id);
            $filename = $user->foto;

            if ($request->file('foto')) {
                $file = $request->file('foto');
                $filename = Uuid::uuid6() . '_profile_pict.' . $file->getClientOriginalExtension();
                File::delete(storage_path('app/public/users/' . $data->foto));
                $file->storeAs('public/users', $filename);

                $user->update([
                    'foto'   => $filename,
                ]);
                // Log Activity
                LogActivity::create([
                    'ip_address' => request()->ip(),
                    'user_id' => Auth::user()->id,
                    'previous_url' => URL::previous(),
                    'current_url' => URL::current(),
                    'file' => 'API\AuthController.php',
                    'action' => 'Mengupdate Foto Profil',
                ]);
                // End Log
                DB::commit();
                return ResponseFormatter::success(
                    $user,
                    'Upload Foto Berhasil'
                );
            }
            DB::rollback();
            return ResponseFormatter::error([
                'success' => false,
                'message' => 'Upload Foto Terlebih Dahulu',
            ], 'Bad Request', 400);
        } catch (Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'success' => false,
                'message' => $e,
            ], 'Internal Server Error', 500);
        }
    }
}
