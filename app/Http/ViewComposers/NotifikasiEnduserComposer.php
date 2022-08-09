<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\SystemNotification;
use Illuminate\Support\Facades\Auth;

class NotifikasiEnduserComposer
{
    protected $request;

    /**
     * ActiveMenuComposer constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function compose(View $view)
    {
        if (Auth::check() && Auth::user()->role != 1) {
            $all_notifikasi = SystemNotification::where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->get();
        } else {
            $all_notifikasi = SystemNotification::orderBy('created_at', 'DESC')->get();
        }
        $view->with('notifikasi', $all_notifikasi);
    }
}
