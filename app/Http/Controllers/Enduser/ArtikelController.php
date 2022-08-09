<?php

namespace App\Http\Controllers\Enduser;

use App\Models\Artikel;
use App\Http\Controllers\Controller;

class ArtikelController extends Controller
{
    public function detail_artikel(Artikel $artikel)
    {
        $artikel_lain = Artikel::where('kategori', '!=', 'Bibit Tanaman')->where('kategori', '!=', 'Produk')->orderBy('created_at', 'DESC')->get();
        return view('enduser.page.artikel.detail', ['artikel' => $artikel,  'artikel_lain' => $artikel_lain]);
    }
}
