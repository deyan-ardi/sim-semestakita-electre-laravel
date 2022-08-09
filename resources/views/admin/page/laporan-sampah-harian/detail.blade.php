@extends('admin.layouts.kasir')
{{-- Meta --}}
@section('meta-name', 'Detail Rekapan Sampah Harian')
@section('meta-description', 'Data Detail Rekapan Sampah Harian')
@section('meta-keyword', 'Detail Rekapan Sampah Harian')
{{-- End Meta --}}
@section('title', 'Detail Rekapan Sampah Harian')
@section('content')

    <!-- Content wrapper start -->
    <div class="content-wrapper">

        <!-- Row start -->
        <div class="row gutters">
            <div class="col-xl-12">
                <!-- Card start -->
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('rekapan-harian') }}" class="btn btn-danger mb-3"><i
                                class="icon-arrow-left"></i>Kembali</a>
                    </div>
                </div>
                <!-- Card end -->

                <!-- Card start -->
                <div class="card">
                    <div class="card-header-lg">
                        <h4>Detail Rekapan Sampah Harian</h4>
                    </div>
                    <div class="card-body">
                        <div class="card-body">

                            <!-- Row start -->
                            <div class="row justify-content-between">

                                <div class="col-xl-5 col-lg-5 col-md-5 col-sm-5 col-12">

                                    <!-- Row start -->
                                    <div class="row gutters">
                                        <div class="col-12">
                                            <div class="form-section-header light-bg">Detail Rekapan Harian</div>
                                        </div>
                                        <div class="col-12">
                                            <!-- Field wrapper start -->
                                            <div class="field-wrapper">
                                                <div class="input-group">
                                                    <input type="text" disabled
                                                        value="Kasir Rekapan Sampah {{ $rekapan->status }}"
                                                        class="form-control">
                                                </div>
                                                <div class="field-placeholder">Status Kasir</div>
                                            </div>
                                            <!-- Field wrapper end -->
                                        </div>
                                        <div class="col-12">
                                            <!-- Field wrapper start -->
                                            <div class="field-wrapper">
                                                <div class="input-group">
                                                    <input type="text" disabled value="{{ $rekapan->kode_transaksi }}"
                                                        class="form-control">
                                                </div>
                                                <div class="field-placeholder">Kode Transaksi</div>
                                            </div>
                                            <!-- Field wrapper end -->
                                        </div>
                                        <div class="col-12">
                                            <!-- Field wrapper start -->
                                            <div class="field-wrapper">
                                                <div class="input-group">
                                                    <input type="text" disabled value="{{ $rekapan->tanggal }}"
                                                        class="form-control">
                                                </div>
                                                <div class="field-placeholder">Tanggal Transaksi</div>
                                            </div>
                                            <!-- Field wrapper end -->
                                        </div>
                                    </div>
                                    <!-- Row end -->

                                </div>
                                <div class="col-xl-7 col-lg-7 col-md-7 col-sm-7 col-12">

                                    <!-- Row start -->
                                    <div class="row gutters">

                                        <!-- Row start -->
                                        <div class="row gutters">
                                            <div class="col-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th colspan="7" class="pt-3 pb-3">Detail Transaksi</th>
                                                            </tr>
                                                            <tr>
                                                                <th>Item</th>
                                                                <th>Harga Item</th>
                                                                <th>Jumlah Sampah</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="body_table">
                                                            @foreach ($detail_rekapan as $v)
                                                                <tr>
                                                                    <td>{{ $v->nama_kategori }}</td>
                                                                    <td>@currency($v->harga_kategori)</td>
                                                                    <td>{{ $v->jumlah_sampah }} KG</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <p class="mt-2">Tipe Rekapan</p>
                                                                </td>
                                                                <td colspan="2">
                                                                    <p class="mt-2">Rekapan Harian Sampah
                                                                        {{ ucWords($rekapan->status) }}</p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <p class="mt-2">Diinput Oleh</p>
                                                                </td>
                                                                <td colspan="2">
                                                                    <p class="mt-2">{{ $rekapan->created_by }}
                                                                    </p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <h5 class="mt-2">Total Sampah
                                                                        {{ $rekapan->status }}</h5>
                                                                </td>
                                                                <td colspan="2">
                                                                    <h5 class="mt-2" id="total_akhir">
                                                                        {{ $rekapan->total_sampah }} KG</h5>
                                                                </td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>

                                            </div>
                                        </div>
                                        <!-- Row end -->

                                        <!-- Row start -->
                                        <div class="row gutters">

                                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                                <div class="form-actions-footer">
                                                    <div class="text-end">
                                                        <form target="_blank" rel="noopener noreferrer"
                                                            action="{{ route('rekapan-harian.cetak.aksi', $rekapan) }}"
                                                            method="post">
                                                            @csrf
                                                            <button type="submit" class="btn btn-primary">Cetak
                                                                Invoice</button>
                                                        </form>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <!-- Row end -->

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
