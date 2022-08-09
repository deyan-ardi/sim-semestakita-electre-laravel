@extends('enduser.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Tagihan Iuran')
@section('meta-description', 'Data Tagihan Iuran')
@section('meta-keyword', 'Tagihan Iuran')
{{-- End Meta --}}
@section('title', 'Tagihan Iuran')
@section('content')
    <div class="content container-fluid">
        {{-- Informasi Tabungan --}}
        <div class="section-header">
            <h3 class="section-title">Informasi Tagihan Iuran</h3>
            <div class="line"></div>
        </div>

        @php
            $iuran_paid = 0;
            $iuran_unpaid = 0;
            $iuran_overdue = 0;
            
            foreach ($tagihan as $v) {
                if ($v->status == 'UNPAID') {
                    $iuran_unpaid = $iuran_unpaid + $v->total_tagihan;
                } elseif ($v->status == 'OVERDUE') {
                    $iuran_unpaid = $iuran_unpaid + $v->total_tagihan;
                    $iuran_overdue = $iuran_overdue + $v->total_tagihan;
                }
            }
        @endphp

        <div class="balance-info">
            <div class="row">
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="dash-widget-icon bg-3">
                                    <i class="fas fa-dollar-sign"></i>
                                </span>
                                <div class="dash-count">
                                    <div class="dash-title">Total Tagihan</div>
                                    <div class="dash-counts">
                                        <h4 class="text-success">{{ $tagihan->count() }} Tagihan</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="dash-widget-icon bg-1">
                                    <i class="fas fa-exclamation"></i>
                                </span>
                                <div class="dash-count">
                                    <div class="dash-title">Total Belum Bayar</div>
                                    <div class="dash-counts">
                                        <h4 class="text-warning">@currency($iuran_unpaid)</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="dash-widget-icon bg-9">
                                    <i class="fas fa-calendar-times"></i>
                                </span>
                                <div class="dash-count">
                                    <div class="dash-title">Total Tenggat Waktu</div>
                                    <div class="dash-counts">
                                        <h4 class="text-danger">@currency($iuran_overdue)</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- End of Informasi Tabungan --}}

        <div class="row">
            <div class="col-12 m-b-100">
                <div class="page-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="section-title">Daftar Tagihan Iuran Sampah</h3>
                            <div class="line"></div>
                        </div>
                        <div class="col-auto">
                            <a class="btn btn-primary filter-btn" href="javascript:void(0);" id="filter_search"> Filter
                                <i class="fas fa-filter"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div id="filter_inputs" class="card filter-card">
                    <div class="card-body pb-4">
                        <div class="row">
                            <div class="col-12">
                                <form class="row" action="{{ route('enduser.tagihan.filter', $user->id) }}"
                                    method="GET">
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-sm-1 d-flex align-items-center">
                                                <span class="p">Filter Data</span>
                                            </div>
                                            <div class="col-sm-3">
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
                                            <div class="col-sm-3">
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
                                            <div class="col-sm-3">
                                                <div class="form-group m-0">
                                                    <label class="m-0 mb-2" for="status"><small>Status</small></label>
                                                    <select name="status" id="status"
                                                        class="form-control @error('status') is-invalid @enderror">
                                                        <option value="Semua"
                                                            {{ request()->status == 'Semua' ? 'selected' : '' }}>
                                                            Semua
                                                        </option>

                                                        <option value="Unpaid"
                                                            {{ request()->status == 'Unpaid' ? 'selected' : '' }}>Unpaid
                                                        </option>
                                                        <option value="Overdue"
                                                            {{ request()->status == 'Overdue' ? 'selected' : '' }}>Overdue
                                                        </option>
                                                    </select>
                                                    @error('status')
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

                <div class="bill-history">
                    <div class="row">
                        @if ($tagihan->count() > 0)
                            @foreach ($tagihan as $v)
                                <div class="col-sm-12 col-lg-6">
                                    <a href="{{ route('enduser.tagihan.detail', [$v->id]) }}">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="text-success">{{ $v->no_tagihan }}
                                                    - Iuran Bulan
                                                    {{ date('F Y', strtotime($v->created_at)) }}
                                                    @if ($v->status == 'PAID')
                                                        <span class="badge bg-success-light">
                                                            PAID
                                                        </span>
                                                    @elseif ($v->status == 'UNPAID')
                                                        <span class="badge bg-warning-light">
                                                            Unpaid
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning-light">
                                                            Overdue
                                                        </span>
                                                    @endif
                                                </h5>
                                                <ul class="text-dark">
                                                    <li>&#9900; Tagihan Sebesar @currency($v->total_tagihan)</li>
                                                    <li>&#9900; Tagihan Dibuat Tanggal
                                                        {{ date('d F Y', strtotime($v->created_at)) }}</li>
                                                    <li>&#9900; Jatuh Tempo
                                                        Pada Tanggal
                                                        {{ date('d F Y', strtotime($v->due_date)) }}</li>
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
                                        Belum Ada Data Tagihan Iuran Rutin
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom-script')
    <script>
        $(document).ready(function() {
            var table = $('#bill-table').DataTable({
                responsive: true,
                paging: true
            });
        });
    </script>
@endsection
