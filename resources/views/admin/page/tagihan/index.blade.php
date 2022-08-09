@extends('admin.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Daftar Tagihan Iuran')
@section('meta-description', 'Data Daftar Tagihan Iuran')
@section('meta-keyword', 'Daftar Tagihan Iuran')
{{-- End Meta --}}
@section('title', 'Daftar Tagihan Iuran')
@section('content')

    <!-- Content wrapper start -->
    <div class="content-wrapper">

        <!-- Row start -->
        <div class="row gutters">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <h4 class="mb-3">Rekapan Tagihan Iuran</h4>
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
                <!-- Card start -->
                <div class="card">
                    <div class="card-body py-2">
                        <form class="row" action="{{ route('tagihan.filter') }}" method="GET">
                            <div class="col-lg-7">
                                <div class="row">
                                    <div class="col-sm-2 d-flex align-items-center">
                                        <span class="p">Filter Data</span>
                                    </div>
                                    <div class="col-sm-5">
                                        <div class="form-group m-0">
                                            <label class="m-0 mb-2" for="tanggal_awal"><small>Tanggal
                                                    Awal</small></label>
                                            <input type="date" id="tanggal_awal" class="form-control" name="tanggal_awal"
                                                value="{{ old('tanggal_awal') ?? request()->tanggal_awal }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                        <div class="form-group m-0">
                                            <label class="m-0 mb-2" for="tanggal_akhir"><small>Tanggal
                                                    Akhir</small></label>
                                            <input type="date" id="tanggal_akhir" class="form-control"
                                                name="tanggal_akhir"
                                                value="{{ old('tanggal_akhir') ?? request()->tanggal_akhir }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="row">
                                    <div class="col-sm-7">
                                        <div class="form-group m-0">
                                            <label class="m-0 mb-2" for="status"><small>Status</small></label>
                                            <select name="status" id="status" class="form-control">
                                                <option value="Semua"
                                                    {{ request()->status == 'Semua' || old('status') == 'Semua' ? 'selected' : '' }}>
                                                    Semua</option>
                                                <option value="Paid"
                                                    {{ request()->status == 'Paid' || old('status') == 'Paid' ? 'selected' : '' }}>
                                                    Paid</option>
                                                <option value="Unpaid"
                                                    {{ request()->status == 'Unpaid' || old('status') == 'Unpaid' ? 'selected' : '' }}>
                                                    Unpaid</option>
                                                <option value="Overdue"
                                                    {{ request()->status == 'Overdue' || old('status') == 'Overdue' ? 'selected' : '' }}>
                                                    Overdue
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-1"></div>
                                    <div class="col-sm-4">
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
                    $lunas = 0;
                    $belum = 0;
                    $overdue = 0;
                    foreach ($tagihan as $v) {
                        $total = $total + $v->total_tagihan;
                        if ($v->status == 'PAID') {
                            $lunas = $lunas + $v->total_tagihan;
                        } elseif ($v->status == 'OVERDUE') {
                            $overdue = $overdue + $v->total_tagihan;
                        } else {
                            $belum = $belum + $v->total_tagihan;
                        }
                    }
                @endphp
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <div class="card">
                            <div class="card-header-lg">
                                <p class="text-left">Total Pembayaran</p>
                                <div class="text-right">
                                    <h6>@currency($total)</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="card">
                            <div class="card-header-lg">
                                <p class="text-left ">Total Iuran Terbayar</p>
                                <div class="text-right">
                                    <h6 class="text-success">@currency($lunas)</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="card">
                            <div class="card-header-lg">
                                <p class="text-left">Total Belum Lunas</p>
                                <div class="text-right">
                                    <h6 class="text-danger">@currency($belum)</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="card">
                            <div class="card-header-lg">
                                <p class="text-left">Total Iuran Overdue</p>
                                <div class="text-right">
                                    <h6 class="text-warning">@currency($overdue)</h6>
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
                                <h5 class="mt-3">Daftar Tagihan Nasabah Iuran</h5>
                            </div>
                            @if (Auth::user()->role != 6)
                                <div class="col-6">
                                    <div class="d-flex justify-content-end">
                                        <div class="custom-btn-group">
                                            <a href="#" class="btn btn-info rounded" data-bs-toggle="modal"
                                                data-bs-target="#modalRegenerate"><i class="icon-refresh refresh"></i>
                                                Generate
                                                Ulang</a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="table-responsive mt-4">
                            <table id="highlightRowColumn" class="table custom-table">
                                <thead>
                                    <tr>
                                        <th>Tagihan Bulan</th>
                                        <th>No. Tagihan</th>
                                        <th>Kepada</th>
                                        <th>No Member</th>
                                        <th>Tagihan</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                        <th>Sub Total Tagihan</th>
                                        <th>Sub Total Denda</th>
                                        <th>Total Akhir Tagihan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tagihan as $v)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($v->tanggal)->format('F Y') }}</td>
                                            <td>{{ $v->no_tagihan }}</td>
                                            <td>{{ $v->user->name }}</td>
                                            <td>{{ $v->user->no_member }}</td>
                                            <td>{{ $v->user->pembayaran_rutin == null ? 'Belum Disetel' : $v->user->pembayaran_rutin->nama_pembayaran }}
                                                --
                                                @currency( $v->user->pembayaran_rutin == null ? 0 :
                                                $v->user->pembayaran_rutin->total_biaya)</td>
                                            <td class="text-danger">{{ $v->due_date }}</td>
                                            @if ($v->status == 'PAID')
                                                @php
                                                    $class = 'text-success';
                                                @endphp
                                            @elseif ($v->status == 'OVERDUE')
                                                @php
                                                    $class = 'text-warning';
                                                @endphp
                                            @else
                                                @php
                                                    $class = 'text-danger';
                                                @endphp
                                            @endif
                                            <td class="{{ $class }}">{{ $v->status }}</td>
                                            <td>@currency($v->sub_total)</td>
                                            <td>@currency($v->sub_total_denda)</td>
                                            <td>@currency($v->total_tagihan)</td>
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

    <!-- Modal -->
    <div class="modal fade" id="modalRegenerate" tabindex="-1" aria-labelledby="modalRegenerateLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title" id="modalRegenerateLabel">Regenerate Tagihan Overdue dan Unpaid</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-danger mb-3 d-flex justify-content-end">* Wajib Diisi</div>
                    <form action="{{ route('tagihan.regenerate') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label class=mb-2" for="bulan"><small>Bulan</small><span class="text-danger">*</span></label>
                            <select name="bulan" required id="bulan" class="form-control">
                                <option value="">-- Bulan Yang Diregenerate -- </option>
                                <option value="01" {{ old('bulan') == '01' ? 'selected' : '' }}>Januari</option>
                                <option value="02" {{ old('bulan') == '02' ? 'selected' : '' }}>Februari</option>
                                <option value="03" {{ old('bulan') == '03' ? 'selected' : '' }}>Maret</option>
                                <option value="04" {{ old('bulan') == '04' ? 'selected' : '' }}>April</option>
                                <option value="05" {{ old('bulan') == '05' ? 'selected' : '' }}>Mei</option>
                                <option value="06" {{ old('bulan') == '06' ? 'selected' : '' }}>Juni</option>
                                <option value="07" {{ old('bulan') == '07' ? 'selected' : '' }}>Juli</option>
                                <option value="08" {{ old('bulan') == '08' ? 'selected' : '' }}>Agustus</option>
                                <option value="09" {{ old('bulan') == '09' ? 'selected' : '' }}>September</option>
                                <option value="10" {{ old('bulan') == '10' ? 'selected' : '' }}>Oktober</option>
                                <option value="11" {{ old('bulan') == '11' ? 'selected' : '' }}>November</option>
                                <option value="12" {{ old('bulan') == '12' ? 'selected' : '' }}>Desember</option>
                            </select>
                        </div>
                        <div class="form-group mt-3">
                            <label class=mb-2" for="tagihan"><small>Tagihan Iuran</small><span
                                    class="text-danger">*</span></label>
                            <select name="tagihan" required id="tagihan" class="form-control">
                                <option value="">-- Tagihan Yang Diregenerate -- </option>
                                @foreach ($pembayaran as $v)
                                    <option value="{{ $v->id }}"
                                        {{ old('tagihan') == $v->id ? 'selected' : '' }}>
                                        {{ $v->nama_pembayaran }} --
                                        @currency($v->total_biaya)</option>
                                @endforeach
                            </select>
                        </div>
                        <small class="mt-3">* Regenerate ulang hanya untuk pembayaran tagihan iuran yang belum
                            dibayar (UNPAID, OVERDUE) dan berfungsi untuk memperpanjang jatuh tempo tagihan</small>
                        <div class="modal-footer mt-3">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Regenerate</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal end -->

@endsection
