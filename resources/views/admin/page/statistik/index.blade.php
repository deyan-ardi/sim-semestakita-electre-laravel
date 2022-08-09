@extends('admin.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Statistik Ssampah Keseluruhan')
@section('meta-description', 'Data Statistik Ssampah Keseluruhan')
@section('meta-keyword', 'Statistik Ssampah Keseluruhan')
{{-- End Meta --}}
@section('title', 'Statistik Sampah - Keseluruhan')

@section('content')

    <!-- Content wrapper start -->
    <div class="content-wrapper">
        @php
            $anorganik = 0;
            $organik = 0;
            $b3 = 0;
            $residu = 0;
            foreach ($all_sampah as $all) {
                if ($all->jenis_sampah == 'organik') {
                    $organik++;
                } elseif ($all->jenis_sampah == 'nonorganik') {
                    $anorganik++;
                } elseif ($all->jenis_sampah == 'B3') {
                    $b3++;
                } else {
                    $residu++;
                }
            }
        @endphp
        <h4 class="mb-3">Statistik Sampah Keseluruhan</h4>
        <!-- Row start -->
        <div class="row gutters">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                <div class="stats-tile">
                    <div class="sale-icon">
                        <i class="icon-shopping-bag1"></i>
                    </div>
                    <div class="sale-details">
                        <h3>{{ $organik }} Kategori</h3>
                        <p>Total Kategori Organik</p>
                    </div>

                </div>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                <div class="stats-tile">
                    <div class="sale-icon">
                        <i class="icon-shopping-bag1"></i>
                    </div>
                    <div class="sale-details">
                        <h3 class="text-warning">{{ $anorganik }} Kategori</h3>
                        <p>Total Kategori Anorganik</p>
                    </div>

                </div>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                <div class="stats-tile">
                    <div class="sale-icon">
                        <i class="icon-shopping-bag1"></i>
                    </div>
                    <div class="sale-details">
                        <h3 class="text-danger">{{ $b3 }} Kategori</h3>
                        <p>Total Kategori B3</p>
                    </div>

                </div>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                <div class="stats-tile">
                    <div class="sale-icon">
                        <i class="icon-shopping-bag1"></i>
                    </div>
                    <div class="sale-details">
                        <h3 class="text-secondary">{{ $residu }} Kategori</h3>
                        <p>Total Kategori Residu</p>
                    </div>

                </div>
            </div>
        </div>
        <!-- Row end -->
        <!-- Row start -->
        @php
            $jml_nonorganik = 0;
            $jml_organik = 0;
            $jml_B3 = 0;
            $jml_residu = 0;
            foreach ($all_sampah as $all) {
                if ($all->jenis_sampah == 'organik') {
                    $jml_organik = $jml_organik + $all->total_sampah;
                } elseif ($all->jenis_sampah == 'nonorganik') {
                    $jml_nonorganik = $jml_nonorganik + $all->total_sampah;
                } elseif ($all->jenis_sampah == 'B3') {
                    $jml_B3 = $jml_B3 + $all->total_sampah;
                } else {
                    $jml_residu = $jml_residu + $all->total_sampah;
                }
            }
            $tot_all_sampah = $jml_nonorganik + $jml_organik + $jml_B3 + $jml_residu;
            $jmlh_akhir_nonorganik = number_format($jml_nonorganik == 0 || $tot_all_sampah == 0 ? 0 : ($jml_nonorganik / $tot_all_sampah) * 100, 2);
            $jmlh_akhir_organik = number_format($jml_organik == 0 || $tot_all_sampah == 0 ? 0 : ($jml_organik / $tot_all_sampah) * 100, 2);
            $jmlh_akhir_B3 = number_format($jml_B3 == 0 || $tot_all_sampah == 0 ? 0 : ($jml_B3 / $tot_all_sampah) * 100, 2);
            $jmlh_akhir_residu = number_format($jml_residu == 0 || $tot_all_sampah == 0 ? 0 : ($jml_residu / $tot_all_sampah) * 100, 2);
        @endphp
        <div class="row gutters">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Statistik Sampah Keseluruhan</div>
                        <div class="graph-day-selection" role="group">
                            <a href="#"><button type="button"
                                    class="btn active">{{ \Carbon\Carbon::now()->format('d F Y,H:i') }}
                                    WITA</button></a>
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
                                    <h6 class="stats-title">Total Sampah Organik</h6>
                                    <p class="stats-amount">{{ $jml_organik }} Kg</p>
                                </div>
                            </li>
                            <li class="stats-list-item primary">
                                <div class="stats-icon">
                                    <i class="icon-archive1"></i>
                                </div>
                                <div class="stats-info">
                                    <h6 class="stats-title">Total Sampah Anorganik</h6>
                                    <p class="stats-amount">{{ $jml_nonorganik }} Kg</p>
                                </div>
                            </li>
                            <li class="stats-list-item primary">
                                <div class="stats-icon">
                                    <i class="icon-archive1"></i>
                                </div>
                                <div class="stats-info">
                                    <h6 class="stats-title">Total Sampah B3</h6>
                                    <p class="stats-amount">{{ $jml_B3 }} Kg</p>
                                </div>
                            </li>
                            <li class="stats-list-item primary">
                                <div class="stats-icon">
                                    <i class="icon-archive1"></i>
                                </div>
                                <div class="stats-info">
                                    <h6 class="stats-title">Total Sampah Residu</h6>
                                    <p class="stats-amount">{{ $jml_residu }} Kg</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Statistik Sampah Organik</div>
                        <div class="graph-day-selection" role="group">
                            <a href="#"><button type="button" class="btn active">Hingga
                                    {{ \Carbon\Carbon::now()->format('d F Y,H:i') }}
                                    WITA</button></a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="earningsGraphOrganik" class="chart-height-md"></div>

                        <ul class="stats-list-container">
                            <li class="stats-list-item primary">
                                <div class="stats-icon">
                                    <i class="icon-archive1"></i>
                                </div>
                                <div class="stats-info">
                                    <h6 class="stats-title">Total Sampah Organik</h6>
                                    <p class="stats-amount">{{ $jml_organik }} Kg</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Statistik Sampah Anorganik</div>
                        <div class="graph-day-selection" role="group">
                            <a href="#"><button type="button" class="btn active">Hingga
                                    {{ \Carbon\Carbon::now()->format('d F Y,H:i') }}
                                    WITA</button></a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="earningsGraphNonorganik" class="chart-height-md"></div>

                        <ul class="stats-list-container">
                            <li class="stats-list-item primary">
                                <div class="stats-icon">
                                    <i class="icon-archive1"></i>
                                </div>
                                <div class="stats-info">
                                    <h6 class="stats-title">Total Sampah Anorganik</h6>
                                    <p class="stats-amount">{{ $jml_nonorganik }} Kg</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>

            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Statistik Sampah B3</div>
                        <div class="graph-day-selection" role="group">
                            <a href="#"><button type="button" class="btn active">Hingga
                                    {{ \Carbon\Carbon::now()->format('d F Y,H:i') }}
                                    WITA</button></a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="earningsGraphB3" class="chart-height-md"></div>

                        <ul class="stats-list-container">
                            <li class="stats-list-item primary">
                                <div class="stats-icon">
                                    <i class="icon-archive1"></i>
                                </div>
                                <div class="stats-info">
                                    <h6 class="stats-title">Total Sampah B3</h6>
                                    <p class="stats-amount">{{ $jml_B3 }} Kg</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Statistik Sampah Residu</div>
                        <div class="graph-day-selection" role="group">
                            <a href="#"><button type="button" class="btn active">Hingga
                                    {{ \Carbon\Carbon::now()->format('d F Y,H:i') }}
                                    WITA</button></a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="earningsGraphResidu" class="chart-height-md"></div>

                        <ul class="stats-list-container">
                            <li class="stats-list-item primary">
                                <div class="stats-icon">
                                    <i class="icon-archive1"></i>
                                </div>
                                <div class="stats-info">
                                    <h6 class="stats-title">Total Sampah Residu</h6>
                                    <p class="stats-amount">{{ $jml_residu }} Kg</p>
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
        var total_sampah = {{ $tot_all_sampah }};
        var jml_akhir_nonorganik = {{ $jmlh_akhir_nonorganik }};
        var jml_akhir_organik = {{ $jmlh_akhir_organik }};
        var jml_akhir_B3 = {{ $jmlh_akhir_B3 }};
        var jml_akhir_residu = {{ $jmlh_akhir_residu }};

        // Organik
        var organik_name = {!! $organik_name !!};
        var organik_jml = [{{ $organik_jml }}];

        // Nonorganik
        var nonorganik_name = {!! $nonorganik_name !!};
        var nonorganik_jml = [{{ $nonorganik_jml }}];

        // B3
        var B3_name = {!! $B3_name !!};
        var B3_jml = [{{ $B3_jml }}];

        // Residu
        var residu_name = {!! $residu_name !!};
        var residu_jml = [{{ $residu_jml }}];
    </script>
    <script src="{{ asset('assets/admin/vendor/apex/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/admin/vendor/apex/custom/statistik/ordersGraph.js') }}"></script>
    <script src="{{ asset('assets/admin/vendor/apex/custom/statistik/earningsGraphOrganik.js') }}"></script>
    <script src="{{ asset('assets/admin/vendor/apex/custom/statistik/earningsGraphNonorganik.js') }}"></script>
    <script src="{{ asset('assets/admin/vendor/apex/custom/statistik/earningsGraphB3.js') }}"></script>
    <script src="{{ asset('assets/admin/vendor/apex/custom/statistik/earningsGraphResidu.js') }}"></script>
@endsection
