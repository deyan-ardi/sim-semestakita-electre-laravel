<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class RedirectController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            if (Auth::user()->role == '1' || Auth::user()->role == '2' || Auth::user()->role == '3' || Auth::user()->role == '6') {
                return redirect(route('admin'));
            }
            return redirect(route('enduser.dashboard'));
        }
        return redirect(route('redirect.logout'));
    }

    public function logout_info()
    {
        return view('errors.logout');
    }
}
