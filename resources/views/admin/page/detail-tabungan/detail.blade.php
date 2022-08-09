@extends('admin.layouts.kasir')
{{-- Meta --}}
@section('meta-name', 'Detail Tabungan')
@section('meta-description', 'Data Detail Tabungan')
@section('meta-keyword', 'Detail Tabungan')
{{-- End Meta --}}
@section('title', 'Detail Tabungan Nasabah')
@section('content')

    <!-- Content wrapper start -->
    <div class="content-wrapper">

        <!-- Row start -->
        <div class="row gutters">
            <div class="col-xl-12">
                <!-- Card start -->
                <div class="card">
                    <div class="card-header">
                        <a href="{{ $back == 1 ? route('rekapan-tabungan') : route('list-tabungan') }}"
                            class="btn btn-danger mb-3"><i class="icon-arrow-left"></i>Kembali</a>
                        <div class="text-end">
                            <a href="{{ route('rekapan-tabungan.history', [$user->id]) }}" class="btn btn-success mb-3"><i
                                    class="icon-restore"></i>History Transaksi</a>
                        </div>
                    </div>
                </div>
                <!-- Card end -->

                <!-- Card start -->
                <div class="card">
                    <div class="card-header-lg">
                        <h4>Detail Rekapan Tabungan</h4>
                    </div>
                    <div class="card-body">
                        <div class="card-body">

                            <!-- Row start -->
                            <div class="row justify-content-between">

                                <div class="col-xl-5 col-lg-5 col-md-5 col-sm-5 col-12">

                                    <!-- Row start -->
                                    <div class="row gutters">
                                        <div class="col-12">
                                            <div class="form-section-header light-bg">Data Nasabah</div>
                                        </div>
                                        <div class="col-12">
                                            <!-- Field wrapper start -->
                                            <div class="field-wrapper">
                                                <input class="form-control" disabled type="text"
                                                    value="{{ $user->no_member . ' -- ' . $user->name }}">
                                                <div class="field-placeholder">Nama Nasabah</div>
                                            </div>
                                            <!-- Field wrapper end -->
                                        </div>
                                        <div class="col-12">
                                            <!-- Field wrapper start -->
                                            <div class="field-wrapper">
                                                <div class="input-group">
                                                    <input type="text" disabled value="0{{ $user->no_telp }}"
                                                        class="form-control">
                                                </div>
                                                <div class="field-placeholder">No Telepon/Whatsapp</div>
                                            </div>
                                            <!-- Field wrapper end -->
                                        </div>
                                        <div class="col-12">
                                            <!-- Field wrapper start -->
                                            <div class="field-wrapper">
                                                <div class="input-group">
                                                    <input type="text" disabled value="{{ $user->email }}"
                                                        class="form-control">
                                                </div>
                                                <div class="field-placeholder">Email Nasabah</div>
                                            </div>
                                            <!-- Field wrapper end -->
                                        </div>
                                        <div class="col-12">
                                            <!-- Field wrapper start -->
                                            <div class="field-wrapper">
                                                <div class="input-group">
                                                    <input type="text" disabled
                                                        value="{{ $user->no_rekening == null ? 'Belum Ditambahkan' : $user->no_rekening }}"
                                                        class="form-control">
                                                </div>
                                                <div class="field-placeholder">No Rekening</div>
                                            </div>
                                            <!-- Field wrapper end -->
                                        </div>
                                        <div class="col-12">
                                            <!-- Field wrapper start -->
                                            <div class="field-wrapper">
                                                <div class="input-group">
                                                    <input type="text" disabled
                                                        value="{{ $user->alamat == null ? 'Belum Ditambahkan' : $user->alamat }}"
                                                        class="form-control">
                                                </div>
                                                <div class="field-placeholder">Alamat Nasabah</div>
                                            </div>
                                            <!-- Field wrapper end -->
                                        </div>
                                        <div class="col-12">
                                            <!-- Field wrapper start -->
                                            <div class="field-wrapper">
                                                <div class="input-group">
                                                    <input type="text" disabled
                                                        value="{{ $user->status_iuran == 1 ? 'Aktif Membayar Iuran' : 'Nonakif Membayar Iuran' }}"
                                                        class="form-control">
                                                </div>
                                                <div class="field-placeholder">Status Iuran</div>
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
                                                                <th colspan="5" class="pt-3 pb-3">Data Tabungan</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <h6 class="mt-2">Total Kredit (Pemasukan)</h6>
                                                                </td>
                                                                <td colspan="3">
                                                                    <h6 class="mt-2" id="total_penarikan">
                                                                        @currency($tabungan->debet)
                                                                    </h6>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <h6 class="mt-2">Total Debet (Pengeluaran)
                                                                    </h6>
                                                                </td>
                                                                <td colspan="3">
                                                                    <h6 class="mt-2">
                                                                        @currency($tabungan->kredit)
                                                                    </h6>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <h5 class="mt-2">Total Saldo</h5>
                                                                </td>
                                                                <td colspan="3">
                                                                    <h5 class="mt-2 text-success" id="sisa_saldo">
                                                                        @currency($tabungan->saldo)
                                                                    </h5>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
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
