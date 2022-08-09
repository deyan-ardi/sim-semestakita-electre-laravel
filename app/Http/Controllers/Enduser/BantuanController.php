<?php

namespace App\Http\Controllers\Enduser;

use App\Http\Controllers\Controller;

class BantuanController extends Controller
{
    public function index()
    {
        return view('enduser.page.bantuan.index');
    }
}
