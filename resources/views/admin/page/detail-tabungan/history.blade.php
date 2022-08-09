@extends('admin.layouts.kasir')
{{-- Meta --}}
@section('meta-name', 'History Tabungan')
@section('meta-description', 'Data History Tabungan')
@section('meta-keyword', 'History Tabungan')
{{-- End Meta --}}
@section('title', 'History Tabungan')
@section('content')

    <!-- Content wrapper start -->
    <div class="content-wrapper">

        <!-- Row start -->
        <div class="row gutters">
            <div class="col-xl-12">
                <!-- Card start -->
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('list-tabungan') }}" class="btn btn-danger mb-3"><i
                                class="icon-arrow-left"></i>Kembali</a>
                        <div class="text-end">
                            <form action="{{ route('detail-tabungan.cetak-history', [$user->id]) }}" target="_blank"
                                rel="noopener noreferrer" method="post">
                                @csrf
                                <button type="submit" class="btn btn-primary mb-3"><i class="icon-print"></i>Cetak
                                    History</button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Card end -->

                <!-- Card start -->
                <div class="card">
                    <div class="card-header-lg">
                        <h4>History Tabungan - {{ $user->no_member }} - {{ $user->name }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="card-body">

                            <!-- Row start -->
                            <div class="row justify-content-between">

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">

                                    <!-- Row start -->
                                    <div class="row gutters">
                                        <div class="col-12">
                                            <div class="form-section-header light-bg">Penyetoran/Kredit</div>
                                        </div>
                                        <div class="col-12">

                                        </div>
                                        <div class="col-12">
                                            <!-- Field wrapper start -->
                                            <div class="table-responsive mt-4">
                                                <table id="basicExample" class="table custom-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Tanggal</th>
                                                            <th>Kode Transaksi</th>
                                                            <th>Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($rekapan_sampah as $v)
                                                            <tr>
                                                                <td>{{ $v->created_at->format('d M Y, H:i') }} WITA</td>
                                                                <td><a href="{{ route('rekapan-sampah.search', [$v->id]) }}"
                                                                        class="text-primary">{{ $v->kode_transaksi }}</a>
                                                                </td>
                                                                <td>@currency($v->total_beli)</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <!-- Field wrapper end -->
                                        </div>
                                    </div>
                                    <!-- Row end -->

                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">

                                    <!-- Row start -->
                                    <div class="row gutters">
                                        <div class="col-12">
                                            <div class="form-section-header light-bg">Penarikan/Debet</div>
                                        </div>
                                        <div class="col-12">

                                        </div>
                                        <div class="col-12">
                                            <!-- Field wrapper start -->
                                            <div class="table-responsive mt-4">
                                                <table id="highlightRowColumn" class="table custom-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Tanggal</th>
                                                            <th>No Penarikan</th>
                                                            <th>Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($rekapan_penarikan as $v)
                                                            <tr>
                                                                <td>{{ $v->created_at->format('d M Y,H:i') }} WITA</td>
                                                                <td><a href="{{ route('rekapan-tabungan.search', [$v->id]) }}"
                                                                        class="text-primary"> {{ $v->no_penarikan }}</a>
                                                                </td>
                                                                <td>@currency($v->total_penarikan)</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <!-- Field wrapper end -->
                                        </div>
                                    </div>
                                    <!-- Row end -->

                                </div>

                            </div>
                            <!-- Row end -->

                        </div>
                    </div>
                </div>
                <!-- Card end -->
            </div>
        </div>
        <!-- Row end -->
    </div>
@endsection
