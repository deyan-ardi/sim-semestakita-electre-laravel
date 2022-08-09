<?php

namespace App\Http\Controllers\Enduser;

use App\Http\Controllers\Controller;

class TentangController extends Controller
{
    public function index()
    {
        return view('enduser.page.tentang.index');
    }
}
