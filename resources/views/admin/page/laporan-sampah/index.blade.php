@extends('admin.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Rekapan Nabung Sampah')
@section('meta-description', 'Data Rekapan Nabung Sampah')
@section('meta-keyword', 'Rekapan Nabung Sampah')
{{-- End Meta --}}
@section('title', 'Rekapan Nabung Sampah')
@section('content')

    <!-- Content wrapper start -->
    <div class="content-wrapper">

        <!-- Row start -->
        <div class="row gutters">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

                <h4 class="mb-3">Rekapan Nabung Sampah</h4>
                <!-- Card start -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <p>Kesalahan input terdeteksi:</p>
                        <ul>
                            @foreach ($errors->all() as $index => $item)
                                <li>{{ $index + 1 }}. {{ $item }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="card">
                    <div class="card-body py-2">
                        <form class="row" action="{{ route('rekapan-sampah.filter') }}" method="GET">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-sm-2 d-flex align-items-center">
                                        <span class="p">Filter Data</span>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group m-0">
                                            <label class="m-0 mb-2" for="tanggal_awal"><small>Tanggal
                                                    Awal</small></label>
                                            <input type="date" id="tanggal_awal" class="form-control " name="tanggal_awal"
                                                value="{{ old('tanggal_awal') ?? request()->tanggal_awal }}">

                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group m-0">
                                            <label class="m-0 mb-2" for="tanggal_akhir"><small>Tanggal
                                                    Akhir</small></label>
                                            <input type="date" id="tanggal_akhir" class="form-control "
                                                name="tanggal_akhir"
                                                value="{{ old('tanggal_akhir') ?? request()->tanggal_akhir }}">

                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group m-0">
                                            <label class="m-0 mb-2" for=""></label>
                                            <div class="d-flex justify-content-end">
                                                <button class="btn btn-sm mt-2 btn-primary rounded px-5">Filter</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @php
                    $total_transaksi = $rekapan->count();
                    $total_sampah = 0;
                    foreach ($rekapan as $v) {
                        $total_sampah = $total_sampah + $v->total_sampah;
                    }
                @endphp
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <div class="card">
                            <div class="card-header-lg">
                                <p class="text-left ">Total Sampah</p>
                                <div class="text-right">
                                    <h6>{{ $total_sampah }} Kg</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="card">
                            <div class="card-header-lg">
                                <p class="text-left">Total Transaksi</p>
                                <div class="text-right">
                                    <h6 class="text-warning">{{ $total_transaksi }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Card end -->

                <!-- Card start -->
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <h5 class="mt-3">Rekapan Nabung Sampah</h5>
                            </div>
                            <div class="col-6">
                                <div id="new_btn" class="d-flex justify-content-end">
                                    <form action="{{ route('rekapan-sampah.export') }}" method="POST">
                                        @csrf
                                        <input type="hidden" value="{{ request()->tanggal_awal }}" name="tanggal_awal">
                                        <input type="hidden" value="{{ request()->tanggal_akhir }}" name="tanggal_akhir">
                                        <button type="submit" class="btn btn-info rounded">
                                            <i class="icon-print text-white"></i>
                                            <span class="text">
                                                Export
                                            </span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive mt-4">
                            <table id="highlightRowColumn" class="table custom-table">
                                <thead>
                                    <tr>
                                        <th>Aksi</th>
                                        <th>Tanggal</th>
                                        <th>Kode Transaksi</th>
                                        <th>No Member</th>
                                        <th>Nama</th>
                                        <th>Total Sampah</th>
                                        <th>Total Beli</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rekapan as $v)
                                        <tr>
                                            <td>
                                                <div class="actions">
                                                    <a href="{{ route('rekapan-sampah.detail', [$v->id]) }}"
                                                        data-toggle="tooltip" data-placement="top" title=""
                                                        data-original-title="Detail">
                                                        <i class="icon-eye1 text-info"></i>
                                                    </a>
                                                    <a target="_blank" rel="noopener noreferrer"
                                                        href="{{ route('rekapan-sampah.cetak.id', [$v->id]) }}"
                                                        data-toggle="tooltip" data-placement="top" title=""
                                                        data-original-title="Print">
                                                        <i class="icon-print text-success"></i>
                                                    </a>
                                                </div>
                                            </td>
                                            <td>{{ $v->created_at->format('d M Y') }}</td>
                                            <td>{{ $v->kode_transaksi }}</td>
                                            <td>{{ $v->user->no_member }}</td>
                                            <td>{{ $v->user->name }}</td>
                                            <td>{{ $v->total_sampah }} Kg</td>
                                            <td>@currency($v->total_beli)</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Card end -->

            </div>
        </div>
    </div>
    <!-- Row end -->

@endsection
