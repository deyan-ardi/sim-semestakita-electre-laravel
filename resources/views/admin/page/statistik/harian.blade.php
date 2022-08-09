@extends('admin.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Statistik Sampah Harian')
@section('meta-description', 'Data Statistik Sampah Harian')
@section('meta-keyword', 'Statistik Sampah Harian')
{{-- End Meta --}}
@section('title', 'Statistik Sampah - Harian')

@section('content')

    <!-- Content wrapper start -->
    <div class="content-wrapper">
        <h4 class="mb-3">Statistik Sampah Harian</h4>
        <!-- Row start -->
        <div class="row gutters">
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class="stats-tile">
                    <div class="sale-icon">
                        <i class="icon-shopping-bag1"></i>
                    </div>
                    <div class="sale-details">
                        <h3>{{ $all_nabung->count() }} Transaksi</h3>
                        <p>Total Nabung Sampah Hari Ini</p>
                    </div>

                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class="stats-tile">
                    <div class="sale-icon">
                        <i class="icon-shopping-bag1"></i>
                    </div>
                    <div class="sale-details">
                        <h3>{{ $all_keluar->count() }} Transaksi</h3>
                        <p>Total Sampah Keluar Hari Ini</p>
                    </div>

                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                <div class="stats-tile">
                    <div class="sale-icon">
                        <i class="icon-shopping-bag1"></i>
                    </div>
                    <div class="sale-details">
                        <h3>{{ $all_masuk->count() }} Transaksi</h3>
                        <p>Total Sampah Masuk Hari Ini</p>
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
                        <div class="card-title">Data Sampah Dari Nabung Nasabah</div>
                        <div class="graph-day-selection" role="group">
                            <a href="#"><button type="button"
                                    class="btn active">{{ \Carbon\Carbon::now()->format('d F Y,H:i') }}
                                    WITA</button></a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="earningsGraphNabung" class="chart-height-md"></div>
                    </div>
                </div>

            </div>
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Data Sampah Dari Rekapan Sampah Keluar</div>
                        <div class="graph-day-selection" role="group">
                            <a href="#"><button type="button"
                                    class="btn active">{{ \Carbon\Carbon::now()->format('d F Y,H:i') }}
                                    WITA</button></a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="earningsGraphKeluar" class="chart-height-md"></div>
                    </div>
                </div>

            </div>

            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Data Sampah Dari Rekapan Sampah Masuk</div>
                        <div class="graph-day-selection" role="group">
                            <a href="#"><button type="button"
                                    class="btn active">{{ \Carbon\Carbon::now()->format('d F Y,H:i') }}
                                    WITA</button></a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="earningsGraphMasuk" class="chart-height-md"></div>
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
        var jml_nabung = [{{ $jml_nabung }}];
        var jml_keluar = [{{ $jml_keluar }}];
        var jml_masuk = [{{ $jml_masuk }}];
    </script>
    <script src="{{ asset('assets/admin/vendor/apex/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/admin/vendor/apex/custom/statistik/earningsGraphNabung.js') }}"></script>
    <script src="{{ asset('assets/admin/vendor/apex/custom/statistik/earningsGraphKeluar.js') }}"></script>
    <script src="{{ asset('assets/admin/vendor/apex/custom/statistik/earningsGraphMasuk.js') }}"></script>
@endsection
