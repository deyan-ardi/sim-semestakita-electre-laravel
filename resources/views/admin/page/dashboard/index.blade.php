@extends('admin.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Dashboard Panel')
@section('meta-description', 'Data Dashboard Panel')
@section('meta-keyword', 'Dashboard Panel')
{{-- End Meta --}}
@section('title', 'Dashboard Admin')

@section('content')

    <!-- Content wrapper start -->
    <div class="content-wrapper">
        @php
            $total_kas = 0;
            $tot_iuran = 0;
            $tot_tabungan = 0;
            $tunggakan = 0;
            $pelunasan = 0;
            $tot_penjemputan = 0;
            $tot_tagihan = 0;
            $tot_rekap_harian = 0;
            foreach ($penjemputan as $p) {
                if ($p->status == 'lunas') {
                    $total_kas = $total_kas + $p->biaya;
                    $tot_penjemputan = $tot_penjemputan + $p->biaya;
                }
            }
            
            $tot_penarikan_bulan_ini = 0;
            $tot_penarikan_bulan_lalu = 0;
            foreach ($penarikan as $p) {
                if (\Carbon\Carbon::parse($p->updated_at)->format('m-Y') == date('m-Y')) {
                    $tot_penarikan_bulan_ini = $tot_penarikan_bulan_ini + $p->total_penarikan;
                } elseif (\Carbon\Carbon::parse($p->updated_at)->format('m-Y') == date('m-Y', strtotime('-1 month', strtotime(date('Y-m-d'))))) {
                    $tot_penarikan_bulan_lalu = $tot_penarikan_bulan_lalu + $p->total_penarikan;
                }
            }
            foreach ($tabungan as $t) {
                $total_kas = $total_kas + $t->saldo;
                $tot_tabungan = $tot_tabungan + $t->saldo;
            }
            
            foreach ($iuran as $i) {
                $total_kas = $total_kas + $i->total_tagihan;
                $tot_iuran = $tot_iuran + $i->total_tagihan;
            }
            
            foreach ($rekap_harian as $h) {
                $tot_rekap_harian = $tot_rekap_harian + $h->total_pemasukan;
                $total_kas = $total_kas + $h->total_pemasukan;
            }
            
            foreach ($tagihan as $t) {
                $tot_tagihan++;
                if ($t->status == 'PAID') {
                    $pelunasan++;
                } else {
                    $tunggakan++;
                }
            }
        @endphp
        <div class="row gutters">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="stats-tile">
                    <div class="sale-icon">
                        <img src="{{ $file }}" class="img-fluid" alt="User Avatar">
                    </div>
                    <div class="sale-details">
                        <h3>{{ Auth::user()->name }}</h3>
                        <p>{{ Auth::user()->role == 1? 'SUPER ADMIN': (Auth::user()->role == 2? 'PENGELOLA': (Auth::user()->role == 3? 'PEGAWAI': 'TAMU')) }}
                            {{ config('mitra.name') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Row start -->
        <div class="row gutters">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                <div class="stats-tile">
                    <div class="sale-icon">
                        <i class="icon-credit"></i>
                    </div>
                    <div class="sale-details">
                        <h3>@currency($total_kas)</h3>
                        <p>Total Keseluruhan Kas Organisasi</p>
                    </div>

                </div>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                <div class="stats-tile">
                    <div class="sale-icon">
                        <i class="icon-calendar"></i>
                    </div>
                    <div class="sale-details">
                        <h3 class="text-success">@currency($tot_iuran)</h3>
                        <p>Total Kas Dari Iuran Terbayar</p>
                    </div>

                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                <div class="stats-tile">
                    <div class="sale-icon">
                        <i class="icon-wallet"></i>
                    </div>
                    <div class="sale-details">
                        <h3 class="text-success">@currency($tot_tabungan)</h3>
                        <p>Total Kas Dari Tabungan Nasabah</p>
                    </div>

                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                <div class="stats-tile">
                    <div class="sale-icon">
                        <i class="icon-local_shipping"></i>
                    </div>
                    <div class="sale-details">
                        <h3 class="text-success">@currency($tot_penjemputan)</h3>
                        <p>Total Kas Dari Penjemputan</p>
                    </div>

                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                <div class="stats-tile">
                    <div class="sale-icon">
                        <i class="icon-login"></i>
                    </div>
                    <div class="sale-details">
                        <h3 class="text-success">@currency($tot_rekap_harian)</h3>
                        <p>Total Kas Dari Sampah Keluar</p>
                    </div>

                </div>
            </div>
        </div>
        <!-- Row end -->
        <!-- Row start -->
        <div class="row gutters">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

                <div class="card">
                    <div class="card-body">
                        <!-- Row start -->
                        <div class="row gutters">
                            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-4 col-12">
                                <div class="reports-summary">
                                    <div class="reports-summary-block">
                                        <h5>Tagihan Iuran</h5>
                                        <h6>Perbandingan Tunggakan dan Pelunasan Disetiap Bulannya</h6>
                                    </div>
                                    <div class="reports-summary-block">
                                        <h5>{{ $tunggakan }} Tunggakan</h5>
                                        <h6>Secara Total</h6>
                                    </div>
                                    <div class="reports-summary-block">
                                        <h5>{{ $pelunasan }} Pelunasan</h5>
                                        <h6>Secara Total</h6>
                                    </div>
                                    <div class="reports-summary-block">
                                        <h5>{{ $tot_tagihan }} Daftar Tagihan</h5>
                                        <h6>Secara Total</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-9 col-lg-9 col-md-8 col-sm-8 col-12">
                                <div class="row gutters">
                                    <div class="col-12">
                                        <div id="salesGraph" class="chart-height-xl"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Row end -->
                    </div>
                </div>

            </div>
        </div>
        <!-- Row end -->

        <!-- Row start -->
        <div class="row gutters">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Penyetoran Sampah</div>
                        <div class="graph-day-selection" role="group">
                            <a href="{{ route('rekapan-sampah') }}"><button type="button"
                                    class="btn active">Semua</button></a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="visitorsGraph" class="chart-height-md"></div>
                    </div>
                </div>

            </div>
        </div>
        <!-- Row end -->
        @php
            $total_harian = 0;
            $tot_keluar = 0;
            $tot_sampah_keluar = 0;
            $tot_masuk = 0;
            $tot_sampah_masuk = 0;
            foreach ($harian as $h) {
                if ($h->tanggal == date('Y-m-d')) {
                    $total_harian++;
                    if ($h->status == 'Keluar') {
                        $tot_keluar++;
                        $tot_sampah_keluar = $tot_sampah_keluar + $h->total_sampah;
                    } else {
                        $tot_masuk++;
                        $tot_sampah_masuk = $tot_sampah_masuk + $h->total_sampah;
                    }
                }
            }
            $persen_keluar = number_format($tot_keluar == 0 || $total_harian == 0 ? 0 : ($tot_keluar / $total_harian) * 100, 1);
            $persen_masuk = number_format($tot_masuk == 0 || $total_harian == 0 ? 0 : ($tot_masuk / $total_harian) * 100, 1);
        @endphp
        <!-- Row start -->
        <div class="row gutters">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">

                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Sampah Harian {{ \Carbon\Carbon::now()->format('d F Y') }}</div>
                        <div class="graph-day-selection" role="group">
                            <a href="{{ route('rekapan-harian') }}"><button type="button"
                                    class="btn active">Semua</button></a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="ordersGraph" class="chart-height-md"></div>

                        <ul class="stats-list-container">
                            <li class="stats-list-item primary">
                                <div class="stats-icon">
                                    <i class="icon-archive1"></i>
                                </div>
                                <div class="stats-info">
                                    <h6 class="stats-title">Sampah Masuk</h6>
                                    <p class="stats-amount">{{ $tot_sampah_masuk }} Kg</p>
                                </div>
                            </li>
                            <li class="stats-list-item primary">
                                <div class="stats-icon">
                                    <i class="icon-truck"></i>
                                </div>
                                <div class="stats-info">
                                    <h6 class="stats-title">Sampah Keluar</h6>
                                    <p class="stats-amount">{{ $tot_sampah_keluar }} Kg</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">

                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Penarikan Tabungan</div>
                        <div class="graph-day-selection" role="group">
                            <a href="{{ route('rekapan-tabungan') }}"><button type="button"
                                    class="btn active">Semua</button></a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="earningsGraph" class="chart-height-md"></div>

                        <ul class="stats-list-container">
                            <li class="stats-list-item primary">
                                <div class="stats-icon">
                                    <i class="icon-briefcase"></i>
                                </div>
                                <div class="stats-info">
                                    <h6 class="stats-title">Bulan Lalu</h6>
                                    <p class="stats-amount">@currency($tot_penarikan_bulan_lalu)</p>
                                </div>
                            </li>
                            <li class="stats-list-item primary">
                                <div class="stats-icon">
                                    <i class="icon-briefcase"></i>
                                </div>
                                <div class="stats-info">
                                    <h6 class="stats-title">Bulan Ini</h6>
                                    <p class="stats-amount">@currency($tot_penarikan_bulan_ini)</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
        <!-- Row end -->
    </div>
    <!-- Content wrapper end -->


@endsection
@section('footer')
    <!-- Apex Charts -->
    <script>
        var tunggakan = [{{ $arr_tunggakan }}];
        var pelunasan = [{{ $arr_pelunasan }}];
        var penyetoran = [{{ $arr_penyetoran }}];
        var penarikan = [{{ $arr_penarikan }}];
        var total_harian = {{ $total_harian }};
        var persen_masuk = {{ $persen_masuk }};
        var persen_keluar = {{ $persen_keluar }};
        var total_sampah = {{ $total_harian }};
    </script>
    <script src="{{ asset('assets/admin/vendor/apex/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/admin/vendor/apex/custom/home/salesGraph.js') }}"></script>
    <script src="{{ asset('assets/admin/vendor/apex/custom/home/ordersGraph.js') }}"></script>
    <script src="{{ asset('assets/admin/vendor/apex/custom/home/earningsGraph.js') }}"></script>
    <script src="{{ asset('assets/admin/vendor/apex/custom/home/visitorsGraph.js') }}"></script>
    {{-- <script src="{{ asset('assets/admin/vendor/apex/custom/home/sparkline.js') }}"></script> --}}
@endsection
