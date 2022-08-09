<?php

namespace App\Http\Controllers\Enduser;

use App\Models\Artikel;
use App\Models\Kategori;
use App\Models\Tabungan;
use App\Models\TagihanIuran;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $artikel = Artikel::where('kategori', '!=', 'Bibit Tanaman')->where('kategori', '!=', 'Produksi')->orderBy('created_at', 'DESC')->get();
        $kategori = Kategori::all();
        $user = Auth::user();
        $tagihan = TagihanIuran::where('user_id', $user->id)->where('status', '!=', 'PAID')->get();
        $tabungan = Tabungan::where('user_id', $user->id)->get();
        return view('enduser.page.dashboard.index', ['tagihan' => $tagihan, 'user' => $user, 'tabungan' => $tabungan, 'kategori' => $kategori, 'artikel' => $artikel]);
    }
}
