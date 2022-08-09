<?php

namespace App\Http\Controllers\Auth;

use Exception;
use Carbon\Carbon;
use Seshac\Otp\Otp;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Contracts\Encryption\DecryptException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @throws \Illuminate\Validation\ValidationException
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {
            $id_login = Crypt::decrypt($request->id_login);
            if ($id_login == 0) {
                $this->validateLogin($request);
            } else {
                $this->validateLoginWhatsapp($request);
            }

            // If the class is using the ThrottlesLogins trait, we can automatically throttle
            // the login attempts for this application. We'll key this by the username and
            // the IP address of the client making these requests into this application.
            if (
                method_exists($this, 'hasTooManyLoginAttempts') &&
                $this->hasTooManyLoginAttempts($request)
            ) {
                $this->fireLockoutEvent($request);

                return $this->sendLockoutResponse($request);
            }

            if ($id_login == 0) {
                if ($this->attemptLogin($request)) {
                    return $this->sendLoginResponse($request);
                }
            } else {
                if ($this->attemptLoginWhatsapp($request)) {
                    return $this->sendOtpWhatsapp($request);
                }
                return redirect(route('whatsapp.login'))->withErrors(['no_telp' => 'Identitas tersebut tidak cocok dengan data kami.']);
            }
        } catch (DecryptException $error) {
            dd($error);
        }
        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated(
            $request,
            $this->guard()->user()
        )) {
            return $response;
        }
        // $password = '12345678';
        // try {
        //     $id_login = Crypt::decrypt($request->id_login);
        //     if ($id_login == 0) {
        //         Auth::logoutOtherDevices($request->password, 'password');
        //         Auth::logoutOtherDevices($password, 'password');
        //     } else {
        //         Auth::logoutOtherDevices($password, 'password');
        //     }
        // } catch (DecryptException $error) {
        //     dd($error);
        // }
        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect()->intended($this->redirectPath());
    }

    // Whatsapp OTP Login Start

    public function formLoginWhatsapp()
    {
        Session::forget('whatsapp_login');
        return view('auth.whatsapp');
    }

    /**
     * Attempt to send OTP the user by whatsapp .
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return bool
     */
    public function sendOtpWhatsapp(Request $request)
    {
        try {
            $user = User::where('no_telp', (int) $request->no_telp)->first();
            Session::forget('whatsapp_login');
            if ($user != null) {
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
                    $send_wa = Controller::sendMessage($user->no_telp, $message);
                    $send_email = Controller::email_otp($user->email, $otp->token, $user->name, $expired);
                    if ($send_wa['kode'] == 200 || $send_email['kode'] == 200) {
                        $session_data = [
                            'user_to_login' => $user->id,
                            'token' => Crypt::encrypt($otp->token),
                            'expired' => $expired->timestamp,
                        ];
                        Session::put('whatsapp_login', $session_data);
                        return redirect(route('whatsapp.login.token'))->with('success', 'Kode OTP Berhasil Dikirim Ke WhatsApp dan Email Anda');
                    }
                    return redirect(route('whatsapp.login'))->withErrors(['no_telp' => 'Fitur ini sedang dalam perbaikan, coba lagi nanti']);
                }
            } else {
                return redirect(route('whatsapp.login'))->withErrors(['no_telp' => 'Identitas tersebut tidak cocok dengan data kami.']);
            }
        } catch (Exception $error) {
            dd($error);
            abort(500);
        }
    }

    public function formInputOtpWhatsapp()
    {
        $session_data = Session::get('whatsapp_login');
        if (! empty($session_data)) {
            $find = User::where('id', $session_data['user_to_login'])->firstOrFail();
            return view('auth.whatsappToken', ['expired' => Carbon::createFromTimestamp($session_data['expired'])->format('M d, Y H:i:s'), 'user' => $find, 'expired_timestamp' => $session_data['expired']]);
        }
        abort(404);
    }

    public function validOtpWhatsapp(Request $request)
    {
        $request->validate([
            'otp1' => 'required|string|max:1',
            'otp2' => 'required|string|max:1',
            'otp3' => 'required|string|max:1',
            'otp4' => 'required|string|max:1',
        ]);
        $session_data = Session::get('whatsapp_login');
        if (! empty($session_data)) {
            $user = $session_data['user_to_login'];
            $tokenResult = $session_data['token'];
            $expired = $session_data['expired'];
            $token_input = $request->otp1 . $request->otp2 . $request->otp3 . $request->otp4;
            $find = User::where('id', $user)->firstOrFail();
            try {
                $decrypted = Crypt::decrypt($tokenResult);
                $expired_date = Carbon::createFromTimestamp($expired)->format('Y-m-d H:i:s');
                if ($this->hasTooManyLoginAttempts($request)) {
                    $this->fireLockoutEvent($request);
                    // return $this->sendLockoutResponse($request);
                    return redirect(route('whatsapp.login'))->with('error', 'Sistem Mendeteksi Tindakan Spam, Silahkan Login Kembali');
                }
                if ($token_input == $decrypted) {
                    if (Carbon::now()->format('Y-m-d H:i:s') <= $expired_date) {
                        Auth::login($find);
                        Session::forget('whatsapp_login');
                        return $this->sendLoginResponse($request);
                    }
                    $this->incrementLoginAttempts($request);
                    return redirect(route('whatsapp.login.token'))->with('error', 'Kode OTP Telah Kedaluwarsa, Silahkan Kirim Ulang OTP');
                }
                $this->incrementLoginAttempts($request);
                return redirect(route('whatsapp.login.token'))->with('error', 'Kode OTP Yang Dimasukkan Salah');
            } catch (DecryptException $error) {
                dd($error);
            }
        } else {
            abort(404);
        }
    }
    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return bool
     */
    protected function attemptLoginWhatsapp(Request $request)
    {
        $password = '12345678';
        $user = User::where('no_telp', (int) $request->no_telp)->first();
        if ($user) {
            if (Hash::check($password, $user->password_whatsapp)) {
                return $user;
            }
            return false;
        }
        return false;
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @throws \Illuminate\Validation\ValidationException
     *
     * @return void
     */
    protected function validateLoginWhatsapp(Request $request)
    {
        $request->validate([
            'no_telp' => 'required|string|max:15',
        ]);
    }

    // End Whatsapp OTP Login
    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return redirect(route('redirect.logout'));
    }
}
