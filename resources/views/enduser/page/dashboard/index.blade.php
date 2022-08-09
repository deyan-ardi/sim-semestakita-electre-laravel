@extends('enduser.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Dashboard Pengguna')
@section('meta-description', 'Data Dashboard Pengguna')
@section('meta-keyword', 'Dashboard Pengguna')
{{-- End Meta --}}
@section('title', 'Dashboard Pengguna')
@section('content')
    <div class="content container-fluid dashboard-container">
        <div class="row">
            <div class="col-xl-12 d-flex">
                <div class="card flex-fill welcome-card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="welcome-text">
                                <h6 class="text-primary m-b-12">Selamat Datang,</h6>
                                <h5>{{ $user->no_member }} - {{ $user->name }}</h5>
                                <p class="text-muted">{{ $user->role == 4 ? 'NASABAH' : 'PELANGGAN' }}
                                    {{ config('mitra.name') }}</p>
                            </div>
                            <div class="logo-img">
                                <img src="{{ config('mitra.icon') }}" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="card-body m-t-24">
                        <div class="text-center">
                            <div class="row">
                                <div class="col-4 m-b-24">
                                    <a href="{{ route('enduser.penyetoran.index') }}">
                                        <span class="card-menu bg-danger"><i class="fas fa-trash-restore"></i></span>
                                        <h6 class="text-muted">Penyetoran Sampah</h6>
                                    </a>
                                </div>
                                <div class="col-4 m-b-24">
                                    <a href="{{ route('enduser.produksi.index') }}">
                                        <span class="card-menu bg-success"><i class="fas fa-seedling"></i></span>
                                        <h6 class="text-muted"> Hasil Produksi</h6>
                                    </a>
                                </div>
                                <div class="col-4 m-b-24">
                                    <a href="{{ route('enduser.riwayat.index') }}">
                                        <span class="card-menu bg-info"><i class="fas fa-history"></i></span>
                                        <h6 class="text-muted"> Riwayat Pembayaran</h6>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @php
                $tot_tagihan = 0;
            @endphp
            @foreach ($tagihan as $tagihan)
                @php
                    $tot_tagihan = $tot_tagihan + $tagihan->total_tagihan;
                @endphp
            @endforeach
            <div class="col-xl-6 col-12">
                <a href="{{ route('enduser.tagihan.index') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="dash-widget-icon bg-6">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                </span>
                                <div class="dash-count">
                                    <div class="dash-title">Total Tagihan Iuran</div>
                                    <div class="dash-counts">
                                        @if ($user->status_iuran == 1)
                                            <h4 class="text-info">@currency($tot_tagihan)</h4>
                                        @else
                                            <small>Anda Tidak Aktif Membayar Iuran</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-6 col-12">
                <a href="{{ route('enduser.tabungan.index') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="dash-widget-icon bg-8">
                                    <i class="fas fa-wallet"></i>
                                </span>
                                <div class="dash-count">
                                    <div class="dash-title">Total Tabungan</div>
                                    <div class="dash-counts">
                                        @if ($user->role == 4)
                                            <h4 class="text-primary">@currency($tabungan[0]->saldo)</h4>
                                        @else
                                            <small>Anda Belum Terdaftar Sebagai Nasabah</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-12 col-12">
                <a href="{{ route('enduser.rekapan-penilaian.index') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="dash-widget-icon bg-5">
                                    <i class="fas fa-award"></i>
                                </span>
                                <div class="dash-count">
                                    <div class="dash-title">Rekomendasi Pemilah Aktif</div>
                                    <div class="dash-counts">
                                        <h4 class="text-warning">Periode
                                            {{ \Carbon\Carbon::now()->isoFormat('MMMM Y') }}
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @php
                $organik = 0;
                $nonorganik = 0;
                $b3 = 0;
                $residu = 0;
                
                foreach ($kategori as $v) {
                    if ($v->jenis_sampah == 'organik') {
                        $organik = $organik + $v->total_sampah;
                    } elseif ($v->jenis_sampah == 'nonorganik') {
                        $nonorganik = $nonorganik + $v->total_sampah;
                    } elseif ($v->jenis_sampah == 'B3') {
                        $b3 = $b3 + $v->total_sampah;
                    } elseif ($v->jenis_sampah == 'residu') {
                        $residu = $residu + $v->total_sampah;
                    }
                }
                
                $statistik = [$organik, $nonorganik, $b3, $residu];
            @endphp
            <div class="col-xl-12">
                <div class="comp-section">
                    <div class="section-header">
                        <h3 class="section-title">Statistik Sampah</h3>
                        <div class="line"></div>
                    </div>
                    <div class="card flex-fill">
                        <div class="card-body">
                            {{-- <h4 class="text-center mb-5">Statistik Sampah 1 Tahun Terakhir</h4> --}}
                            <div id="statistik-sampah"></div>
                            <div class="text-center text-muted chart-index">
                                <div class="row">
                                    <div class="col-xl-3 col-6">
                                        <div class="mt-4">
                                            <p class="mb-2 text-truncate"><i class="fas fa-circle text-success mr-1"></i>
                                                Organik</p>
                                            <h5>{{ $organik }} Kg</h5>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-6">
                                        <div class="mt-4">
                                            <p class="mb-2 text-truncate"><i class="fas fa-circle text-warning mr-1"></i>
                                                Anorganik</p>
                                            <h5>{{ $nonorganik }} Kg</h5>
                                            <a href="{{ route('enduser.statistik.anorganik') }}"
                                                class="btn btn-primary">Detail</a>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-6">
                                        <div class="mt-4">
                                            <p class="mb-2 text-truncate"><i class="fas fa-circle text-danger mr-1"></i>
                                                B3
                                            </p>
                                            <h5>{{ $b3 }} Kg</h5>
                                            <a href="{{ route('enduser.statistik.b3') }}"
                                                class="btn btn-primary">Detail</a>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-6">
                                        <div class="mt-4">
                                            <p class="mb-2 text-truncate"><i class="fas fa-circle text-secondary mr-1"></i>
                                                Residu
                                            </p>
                                            <h5>{{ $residu }} Kg</h5>
                                            <a href="{{ route('enduser.statistik.residu') }}"
                                                class="btn btn-primary">Detail</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 m-b-40">
                <div class="comp-section">
                    <div class="section-header">
                        <h3 class="section-title">Artikel</h3>
                        <div class="line"></div>
                    </div>
                    <div class="row">
                        <div class="blog-list owl-carousel owl-theme">
                            @if ($artikel->count() <= 0)
                                <div class="item pb-3">
                                    <a href="#!">
                                        <div class="card">
                                            <p class="card-title text-center flex-fill p-4 m-4"
                                                style="color: rgba(0, 0, 0, 0.5); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                Belum Ada Artikel
                                            </p>
                                        </div>
                                    </a>
                                </div>
                            @else
                                @foreach ($artikel as $v)
                                    <div class="item">
                                        <a href="{{ route('enduser.artikel.detail', $v->slug) }}">
                                            <div class="card">
                                                <img src="{{ asset('storage/' . $v->gambar) }}"
                                                    alt="Artikel Image {{ $v->judul }}" class="card-img-top img-md">
                                            </div>
                                            <div class="card-img-overlay  d-flex align-items-center p-0">
                                                <p class="card-title text-center flex-fill p-4"
                                                    style="background-color: rgba(0, 0, 0, 0.5); color: white; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                    {{ $v->judul }}
                                                </p>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom-script')
    <script>
        $(window).on('load', function() {
            // Owl Carousel
            var owl = $('.owl-carousel');
            owl.owlCarousel({
                loop: true,
                margin: 10,
                autoplay: true,
                autoplayTimeout: 5000,
                autoplayHoverPause: true,
                responsiveClass: true,
                responsiveRefreshRate: true,
                responsive: {
                    0: {
                        items: 1,
                        stagePadding: 15
                    },
                    690: {
                        items: 2,
                        stagePadding: 15
                    },
                    1000: {
                        items: 3
                    }
                }
            });

            // Apex Chart
            var options = {
                chart: {
                    type: 'donut'
                },
                series: <?php echo json_encode($statistik); ?>,
                // series: [10000, 32000, 15000],
                labels: ['Organik', 'Anorganik', 'B3', 'Residu'],
                colors: ['#07a653', '#ffbf43', '#e13247', '#6c757c'],
                legend: {
                    show: false
                }
            }
            var chart = new ApexCharts(document.querySelector("#statistik-sampah"), options);

            chart.render();
        });
    </script>
@endsection
