<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImgComposer
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
        $file = Auth::user()->foto;
        $view->with('file', $file);
    }
}
