@extends('enduser.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Informasi Tabungan')
@section('meta-description', 'Data Informasi Tabungan')
@section('meta-keyword', 'Informasi Tabungan')
{{-- End Meta --}}
@section('title', 'Informasi Tabungan')
@section('content')
    <div class="content container-fluid balance-container">
        <div class="row">
            <div class="col-12 m-b-100">

                {{-- Informasi Tabungan --}}
                <div class="section-header">
                    <h3 class="section-title">Informasi Tabungan</h3>
                    <div class="line"></div>
                </div>
                <div class="balance-info">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="dash-widget-header">
                                        <span class="dash-widget-icon bg-8">
                                            <i class="fas fa-money-bill-wave"></i>
                                        </span>
                                        <div class="dash-count">
                                            <div class="dash-title">Total Saldo</div>
                                            <div class="dash-counts">
                                                <h4 class="text-primary">@currency($user->tabungan->saldo)</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="dash-widget-header">
                                        <span class="dash-widget-icon bg-3">
                                            <i class="fas fa-arrow-circle-down"></i>
                                        </span>
                                        <div class="dash-count">
                                            <div class="dash-title">Total Pemasukan</div>
                                            <div class="dash-counts">
                                                <h4 class="text-success">@currency($user->tabungan->debet)</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="dash-widget-header">
                                        <span class="dash-widget-icon bg-9">
                                            <i class="fas fa-arrow-circle-up"></i>
                                        </span>
                                        <div class="dash-count">
                                            <div class="dash-title">Total Penarikan</div>
                                            <div class="dash-counts">
                                                <h4 class="text-danger">@currency($user->tabungan->kredit)</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- End of Informasi Tabungan --}}

                {{-- Riwayat Penarikan --}}
                <div class="page-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="section-title">Riwayat Transaksi</h3>
                            <div class="line"></div>
                        </div>
                        <div class="col-auto">
                            <a class="btn btn-primary filter-btn" href="javascript:void(0);" id="filter_search"> Filter
                                <i class="fas fa-filter"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div id="filter_inputs" class="card filter-card pb-4">
                    <div class="card-body pb-0">
                        <div class="row">
                            <div class="col-12">
                                <form class="row" action="{{ route('enduser.tabungan.filter', $user->id) }}"
                                    method="GET">
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-sm-2 d-flex align-items-center">
                                                <span class="p">Filter Data</span>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group m-0">
                                                    <label class="m-0 mb-2" for="tanggal_awal"><small>Tanggal
                                                            Awal</small></label>
                                                    <input type="date" id="tanggal_awal"
                                                        class="form-control @error('tanggal_awal') is-invalid @enderror"
                                                        name="tanggal_awal" value="{{ request()->tanggal_awal }}">
                                                    @error('tanggal_awal')
                                                        <span class=" invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group m-0">
                                                    <label class="m-0 mb-2" for="tanggal_akhir"><small>Tanggal
                                                            Akhir</small></label>
                                                    <input type="date" id="tanggal_akhir"
                                                        class="form-control @error('tanggal_akhir') is-invalid @enderror"
                                                        name="tanggal_akhir" value="{{ request()->tanggal_akhir }}">
                                                    @error('tanggal_akhir')
                                                        <span class=" invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="form-group m-0">
                                                    <label class="m-0 mb-2" for=""></label>
                                                    <div class="d-flex justify-content-end">
                                                        <button
                                                            class="btn btn-sm mt-2 btn-primary rounded px-5">Filter</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Penarikan --}}
                <div class="balance-history">
                    <div class="row">
                        <div class="col-sm-12">
                            <h5 class="section-title">Riwayat Penarikan -
                                {{ empty(request()->tanggal_awal) || empty(request()->tanggal_akhir) ? \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') : \Carbon\Carbon::parse(request()->tanggal_awal)->isoFormat('D MMMM Y') . ' s/d ' . \Carbon\Carbon::parse(request()->tanggal_akhir)->isoFormat('D MMMM Y') }}
                            </h5>
                        </div>
                        @if ($rekapan_penarikan->count() > 0)
                            @foreach ($rekapan_penarikan as $v)
                                <div class="col-sm-12 col-lg-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="text-success">{{ $v->no_penarikan }}
                                            </h5>
                                            <ul class="text-dark">
                                                <li>&#9900; <span class="badge bg-success-light p"> Berhasil</span>
                                                </li>
                                                <li>&#9900; Penarikan Sebesar @currency($v->total_penarikan)</li>
                                                <li>&#9900; Transaksi Pada
                                                    {{ \Carbon\Carbon::parse($v->created_at)->isoFormat('D MMMM Y') }}
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-body text-center">
                                        Belum Ada Data Riwayat Penarikan
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Pemasukan --}}
                <div class="row">
                    <div class="col-sm-12">
                        <h5 class="section-title mt-3">Riwayat Pemasukan -
                            {{ empty(request()->tanggal_awal) || empty(request()->tanggal_akhir) ? \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') : \Carbon\Carbon::parse(request()->tanggal_awal)->isoFormat(' D MMMM Y') . ' s/d ' . \Carbon\Carbon::parse(request()->tanggal_akhir)->isoFormat(' D MMMM Y') }}
                        </h5>
                    </div>

                    @if ($rekapan_pemasukan->count() > 0)
                        @foreach ($rekapan_pemasukan as $v)
                            <div class="col-sm-12 col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="text-success">{{ $v->kode_transaksi }}
                                        </h5>
                                        <ul class="text-dark">
                                            <li>&#9900; <span class="badge bg-success-light p"> Berhasil</span>
                                            </li>
                                            <li>&#9900; Pemasukan Sebesar @currency($v->total_beli)</li>
                                            <li>&#9900; Transaksi Pada
                                                {{ \Carbon\Carbon::parse($v->created_at)->isoFormat('D MMMM Y') }}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-body text-center">
                                    Belum Ada Data Riwayat Pemasukan
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
                {{-- End of Riwayat Transaksi --}}


            </div>
        </div>
    </div>
@endsection
@section('custom-script')
    <script>
        $(document).ready(function() {
            var table = $('#balance-table').DataTable({
                responsive: true,
                paging: true
            });
        });
    </script>
@endsection
