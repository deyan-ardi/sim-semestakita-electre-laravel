<?php

namespace App\Http\Controllers\Enduser;

use App\Models\Artikel;
use App\Http\Controllers\Controller;

class ProduksiController extends Controller
{
    public function index()
    {
        $data_produksi = Artikel::where('kategori', '!=', 'Artikel')->where('kategori', '!=', 'Bantuan')->where('kategori', '!=', 'Cara Pemakaian')->orderBy('created_at', 'DESC')->paginate(8);
        return view('enduser.page.produksi.index', compact(['data_produksi']));
    }

    public function detail(Artikel $artikel)
    {
        return view('enduser.page.produksi.detail', ['produksi' => $artikel]);
    }
}
