<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\SystemNotification;
use Illuminate\Support\Facades\Auth;

class NotifikasiAdminComposer
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
        if (Auth::check()) {
            $all_notifikasi = SystemNotification::where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->get();
        }
        $view->with('system_notif', $all_notifikasi);
    }
}
