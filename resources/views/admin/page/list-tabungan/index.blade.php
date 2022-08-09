@extends('admin.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Rekapan Tabungan Nasabah')
@section('meta-description', 'Data Rekapan Tabungan Nasabah')
@section('meta-keyword', 'Rekapan Tabungan Nasabah')
{{-- End Meta --}}
@section('title', 'Rekapan Tabungan Nasabah')
@section('content')

    <!-- Content wrapper start -->
    <div class="content-wrapper">

        <!-- Row start -->
        <div class="row gutters">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

                <h4 class="mb-3">Rekapan Tabungan Nasabah</h4>
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
                        <form class="row" action="{{ route('list-tabungan.filter') }}" method="GET">
                            <div class="col-lg-10">
                                <div class="row">
                                    <div class="col-sm-2 d-flex align-items-center">
                                        <span class="p">Filter Data</span>
                                    </div>
                                    <div class="col-sm-5">
                                        <div class="form-group m-0">
                                            <label class="m-0 mb-2" for="tanggal_awal"><small>Tanggal
                                                    Awal</small></label>
                                            <input type="date" id="tanggal_awal" class="form-control " name="tanggal_awal"
                                                value="{{ old('tanggal_awal') ?? request()->tanggal_awal }}">

                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                        <div class="form-group m-0">
                                            <label class="m-0 mb-2" for="tanggal_akhir"><small>Tanggal
                                                    Akhir</small></label>
                                            <input type="date" id="tanggal_akhir" class="form-control "
                                                name="tanggal_akhir"
                                                value="{{ old('tanggal_akhir') ?? request()->tanggal_akhir }}">

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="row">
                                    <div class="col-sm-12">
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
                    $total = 0;
                    $kredit = 0;
                    $debet = 0;
                    foreach ($tabungan as $t) {
                        $total = $total + $t->saldo;
                        $kredit = $kredit + $t->kredit;
                        $debet = $debet + $t->debet;
                    }
                @endphp
                <div class="row">
                    <div class="col-lg-4 col-md-4">
                        <div class="card">
                            <div class="card-header-lg">
                                <p class="text-left">Total Saldo</p>
                                <div class="text-right">
                                    <h6>@currency($total)</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <div class="card">
                            <div class="card-header-lg">
                                <p class="text-left ">Total Kredit</p>
                                <div class="text-right">
                                    <h6 class="text-danger">@currency($kredit)</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <div class="card">
                            <div class="card-header-lg">
                                <p class="text-left">Total Debet</p>
                                <div class="text-right">
                                    <h6 class="text-success">@currency($debet)</h6>
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
                                <h5 class="mt-3">Daftar Tabungan Nasabah (Debit dan Kredit)</h5>
                            </div>
                            <div class="col-6">
                                <div class="d-flex justify-content-end">
                                    <div class="custom-btn-group">
                                        <form action="{{ route('list-tabungan.export') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="tanggal_awal"
                                                value="{{ request()->tanggal_awal }}">
                                            <input type="hidden" name="tanggal_akhir"
                                                value="{{ request()->tanggal_akhir }}">
                                            <button class="btn btn-info rounded"><i class="icon-print refresh"></i> Export
                                                Semua</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive mt-4">
                            <table id="highlightRowColumn" class="table custom-table">
                                <thead>
                                    <tr>
                                        <th>Fitur</th>
                                        <th>No Member</th>
                                        <th>Nama Nasabah</th>
                                        <th>Total Debet (Dana Keluar)</th>
                                        <th>Total Kredit (Dana Masuk)</th>
                                        <th>Total Saldo</th>
                                        <th>Update Terakhir</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tabungan as $t)
                                        <tr>
                                            <td>
                                                <div class="actions">
                                                    <a href="{{ route('rekapan-tabungan.detail', [$t->user_id, 2]) }}"
                                                        data-toggle="tooltip" data-placement="top" title="Detail Tabungan"
                                                        data-original-title="Detail Tabungan">
                                                        <i class="icon-eye text-info"></i>
                                                    </a>
                                                    <a target="_blank" rel="noopener noreferrer"
                                                        href="{{ route('list-tabungan.export.single', [$t->id]) }}"
                                                        data-toggle="tooltip" data-placement="top" title="Print"
                                                        data-original-title="Print">
                                                        <i class="icon-print text-success ms-3"></i>
                                                    </a>
                                                </div>
                                            </td>
                                            <td>{{ $t->user->no_member }}</td>
                                            <td>{{ $t->user->name }}</td>
                                            <td>@currency($t->kredit)</td>
                                            <td>@currency($t->debet)</td>
                                            <td>@currency($t->saldo)</td>
                                            <td>{{ \Carbon\Carbon::parse($t->updated_at)->format('d F Y, H:i') }} WITA
                                            </td>
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
