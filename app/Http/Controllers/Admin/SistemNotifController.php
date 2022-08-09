<?php

namespace App\Http\Controllers\Admin;

use App\Models\SystemNotification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SistemNotifController extends Controller
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

    public function index($id)
    {
        $detail = SystemNotification::where('id', $id)->firstOrFail();
        if (Auth::user()->role != 1) {
            $detail->status = 'sudah_dibaca';
            $detail->save();
        }
        return view('admin.page.sistem-notif.index', compact('detail'));
    }
}
