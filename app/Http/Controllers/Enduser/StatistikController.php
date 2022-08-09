<?php

namespace App\Http\Controllers\Enduser;

use App\Models\Kategori;
use App\Http\Controllers\Controller;

class StatistikController extends Controller
{
    public function anorganik()
    {
        $all_sampah_nonorganik = Kategori::where('jenis_sampah', 'nonorganik')->get();

        // Statistik nonorganik
        $nonorganik_name = [];
        $nonorganik_jml = [];
        foreach ($all_sampah_nonorganik as $nonorganik) {
            array_push($nonorganik_name, '"' . $nonorganik->nama_kategori . '"');
            array_push($nonorganik_jml, $nonorganik->total_sampah);
        }
        $nonorganik_name = '[' . implode(',', $nonorganik_name) . ']';
        $nonorganik_jml = implode(',', $nonorganik_jml);

        return view('enduser.page.statistik.anorganik', ['all_sampah_nonorganik' => $all_sampah_nonorganik, 'nonorganik_name' => $nonorganik_name, 'nonorganik_jml' => $nonorganik_jml]);
    }

    public function b3()
    {
        $all_sampah_B3 = Kategori::where('jenis_sampah', 'B3')->get();

        // Statistik B3
        $B3_name = [];
        $B3_jml = [];
        foreach ($all_sampah_B3 as $B3) {
            array_push($B3_name, '"' . $B3->nama_kategori . '"');
            array_push($B3_jml, $B3->total_sampah);
        }
        $B3_name = '[' . implode(',', $B3_name) . ']';
        $B3_jml = implode(',', $B3_jml);

        return view('enduser.page.statistik.b3', ['all_sampah_B3' => $all_sampah_B3, 'B3_name' => $B3_name, 'B3_jml' => $B3_jml]);
    }

    public function residu()
    {
        $all_sampah_residu = Kategori::where('jenis_sampah', 'residu')->get();

        // Statistik Residu
        $residu_name = [];
        $residu_jml = [];
        foreach ($all_sampah_residu as $residu) {
            array_push($residu_name, '"' . $residu->nama_kategori . '"');
            array_push($residu_jml, $residu->total_sampah);
        }
        $residu_name = '[' . implode(',', $residu_name) . ']';
        $residu_jml = implode(',', $residu_jml);

        return view('enduser.page.statistik.residu', ['all_sampah_residu' => $all_sampah_residu, 'residu_name' => $residu_name, 'residu_jml' => $residu_jml]);
    }
}
