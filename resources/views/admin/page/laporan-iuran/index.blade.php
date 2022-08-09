@extends('admin.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Rekapan Pembayaran Iuran')
@section('meta-description', 'Data Rekapan Pembayaran Iuran')
@section('meta-keyword', 'Rekapan Pembayaran Iuran')
{{-- End Meta --}}
@section('title', 'Rekapan Pembayaran Iuran')
@section('content')

    <!-- Content wrapper start -->
    <div class="content-wrapper">

        <!-- Row start -->
        <div class="row gutters">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <h4 class="mb-3">Rekapan Pembayaran Iuran</h4>
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
                        <form class="row" action="{{ route('rekapan-iuran.filter') }}" method="GET">
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
                                                value="{{ request()->tanggal_awal }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group m-0">
                                            <label class="m-0 mb-2" for="tanggal_akhir"><small>Tanggal
                                                    Akhir</small></label>
                                            <input type="date" id="tanggal_akhir" class="form-control "
                                                name="tanggal_akhir" value="{{ request()->tanggal_akhir }}">
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
                    $total_pembayaran = $rekapan_iuran->count();
                    $jumlah_pembayaran = 0;
                    foreach ($rekapan_iuran as $v) {
                        $jumlah_pembayaran = $jumlah_pembayaran + $v->total_tagihan;
                    }
                @endphp
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <div class="card">
                            <div class="card-header-lg">
                                <p class="text-left ">Total Pembayaran</p>
                                <div class="text-right">
                                    <h6>{{ $total_pembayaran }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="card">
                            <div class="card-header-lg">
                                <p class="text-left">Jumlah Pembayaran</p>
                                <div class="text-right">
                                    <h6 class="text-success">@currency($jumlah_pembayaran)</h6>
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
                                <h5 class="mt-3">Rekapan Pembayaran Iuran Rutin</h5>
                            </div>
                            <div class="col-6">
                                <div id="new_btn" class="d-flex justify-content-end">
                                    <form action="{{ route('rekapan-iuran.export') }}" method="POST">
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
                                        <th>Tanggal Dibayar</th>
                                        <th>Nama</th>
                                        <th>No Tagihan</th>
                                        <th>No Pembayaran</th>
                                        <th>Nama Tagihan</th>
                                        <th>Sub Total Tagihan</th>
                                        <th>Sub Total Denda</th>
                                        <th>Status Denda</th>
                                        <th>Total Akhir Tagihan</th>
                                        <th>Role</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rekapan_iuran as $v)
                                        <tr>
                                            <td>
                                                <div class="actions">
                                                    <a target="_blank" rel="noopener noreferrer"
                                                        href="{{ route('rekapan-iuran.cetak.id', [$v->id]) }}"
                                                        data-toggle="tooltip" data-placement="top" title=""
                                                        data-original-title="Print">
                                                        <i class="icon-print text-success"></i>
                                                    </a>
                                                </div>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($v->created_at)->format('d F Y H:i') }} WITA
                                            </td>
                                            <td>{{ $v->user->name }}</td>
                                            <td>{{ $v->no_tagihan }}</td>
                                            <td>{{ $v->no_pembayaran }}</td>
                                            <td>{{ $v->deskripsi }}</td>
                                            <td>@currency($v->sub_total)</td>
                                            <td>@currency($v->sub_total_denda)</td>
                                            <td>{{ $v->status_denda }}</td>
                                            <td>@currency($v->total_tagihan)</td>
                                            <td>{{ $v->user->role == 4 ? 'Nasabah' : 'Pelanggan' }}</td>
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
