@extends('enduser.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Penyetoran Sampah')
@section('meta-description', 'Data Penyetoran Sampah')
@section('meta-keyword', 'Penyetoran Sampah')
{{-- End Meta --}}
@section('title', 'Penyetoran Sampah')
@section('content')
    <div class="content container-fluid pickup-section">
        <div class="row">
            <div class="col-12 m-b-100">
                <div class="page-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="section-title">Riwayat Penyetoran Sampah</h3>
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
                                <form class="row" action="{{ route('enduser.penyetoran.filter') }}"
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
                <h5 class="section-class">
                    {{ empty(request()->tanggal_awal) || empty(request()->tanggal_akhir) ? \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') : \Carbon\Carbon::parse(request()->tanggal_awal)->isoFormat('dddd, D MMMM Y') . ' - ' . \Carbon\Carbon::parse(request()->tanggal_akhir)->isoFormat('dddd, D MMMM Y') }}
                </h5>
                <div class="pickup-history">
                    <div class="row">
                        @if ($user->role == 5)
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-body text-center">
                                        Anda belum menjadi nasabah, yuk coba
                                        mendaftar menjadi
                                        nasabah
                                    </div>
                                </div>
                            </div>
                        @else
                            @if ($rekapan->count() > 0)

                                @foreach ($rekapan as $v)
                                    <div class="col-sm-12 col-lg-6">
                                        <a href="{{ route('enduser.penyetoran.detail', [$v->id]) }}">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h5 class="text-success">{{ $v->kode_transaksi }}
                                                    </h5>
                                                    <ul class="text-dark">
                                                        <li>&#9900; <span class="badge bg-success-light p"> Berhasil</span>
                                                        </li>
                                                        <li>&#9900; Total Sampah {{ $v->total_sampah }} Kg</li>
                                                        <li>&#9900; Total Beli @currency($v->total_beli)</li>
                                                        <li>&#9900; Transaksi Pada
                                                            {{ \Carbon\Carbon::parse($v->tanggal)->isoFormat('D MMMM Y') }}
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            Belum Ada Data Riwayat Penyetoran Sampah
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
