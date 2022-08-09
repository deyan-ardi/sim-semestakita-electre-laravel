<?php

namespace App\Http\Controllers\Enduser;

use App\Models\Notifikasi;
use App\Models\SystemNotification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function index()
    {
        $detail = SystemNotification::where('key', 'notif')->get();
        foreach ($detail as $d) {
            $d->status = 'sudah_dibaca';
            $d->save();
        }
        $find = Notifikasi::where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->get();
        return view('enduser.page.notifikasi.index', compact('find'));
    }

    public function sistem($id)
    {
        $detail = SystemNotification::where('id', $id)->firstOrFail();
        $detail->status = 'sudah_dibaca';
        if ($detail->save()) {
            return view('enduser.page.notifikasi.detail', compact('detail'));
        }
    }
}
