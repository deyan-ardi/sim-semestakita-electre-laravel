@extends('admin.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Master Data, Iuran Rutin')
@section('meta-description', 'Data Master Data, Iuran Rutin')
@section('meta-keyword', 'Master Data, Iuran Rutin')
{{-- End Meta --}}
@section('title', 'Master - Iuran Rutin')
@section('content')

    <!-- Content wrapper start -->
    <div class="content-wrapper">

        <!-- Row start -->
        <div class="row gutters">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

                <h4 class="mb-3">Master - Iuran Rutin</h4>
                <!-- Card start -->
                <div class="card">
                    <div class="card-header-lg">
                        <h4 class="d-flex align-items-center"><i class="icon-date_range icon-large me-3"></i>Iuran Rutin
                            ({{ $jumlahIuranRutin }})</h4>
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end">
                                    <div class="custom-btn-group">
                                        <a href="{{ route('iuran.master') }}" class="btn btn-primary rounded"
                                            type="button">
                                            <span class="icon-refresh refresh"></span>
                                        </a>
                                    </div>
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
                                <h5 class="mt-3">Data Iuran Rutin</h5>
                            </div>
                            <div class="col-6">
                                <div class="d-flex justify-content-end">
                                    <div class="custom-btn-group">
                                        <a href="{{ route('iuran.master.tambah') }}" class="btn btn-primary rounded"
                                            type="button">+
                                            Tambah</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive mt-4">
                            <table id="highlightRowColumn" class="table custom-table">
                                <thead>
                                    <tr>
                                        <th>Aksi</th>
                                        <th>Nama Tagihan</th>
                                        <th>Deskripsi</th>
                                        <th>Total Tagihan</th>
                                        <th>Tanggal Generate</th>
                                        <th>Durasi Pembayaran</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pembayaranRutin as $pembayaran)
                                        <tr>
                                            <td>
                                                <div class="actions">
                                                    <a href="{{ route('iuran.master.edit', [$pembayaran->id]) }}"
                                                        data-toggle="tooltip" data-placement="top" title="Ubah Pembayaran"
                                                        data-original-title="Edit">
                                                        <i class="icon-edit1 text-info"></i>
                                                    </a>
                                                    <form id="delete-{{ $pembayaran->id }}"
                                                        action="{{ route('iuran.master.delete', [$pembayaran->id]) }}"
                                                        method="POST">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button type="submit" data-formid="{{ $pembayaran->id }}"
                                                            data-nama="{{ $pembayaran->name }}" data-toggle="tooltip"
                                                            data-placement="top" title="Hapus Pembayaran "
                                                            class="btn btn-link text-decoration-none delete-button">
                                                            <i class="icon-trash text-danger"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                            <td>{{ $pembayaran->nama_pembayaran }}</td>
                                            <td>{{ $pembayaran->deskripsi }}</td>
                                            <td>@currency($pembayaran->total_biaya)</td>
                                            <td>Setiap tanggal {{ $pembayaran->tgl_generate }}</td>
                                            <td>{{ $pembayaran->durasi_pembayaran }} Hari</td>
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
