@extends('admin.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Master Data, Informasi Sistem')
@section('meta-description', 'Data Master Data, Informasi Sistem')
@section('meta-keyword', 'Master Data, Informasi Sistem')
{{-- End Meta --}}
@section('title', 'Master - Bantuan dan Informasi Sistem')
@section('content')

    <!-- Content wrapper start -->
    <div class="content-wrapper">

        <!-- Row start -->
        <div class="row gutters">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

                <h4 class="mb-3">Master - Bantuan dan Info Sistem</h4>
                <!-- Card start -->
                <div class="card">
                    <div class="card-header-lg">
                        <h4 class="d-flex align-items-center"><i class="icon-heart icon-large me-3"></i>Total Versi Aplikasi
                            ({{ $sistem_info->count() }})</h4>
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end">
                                    <div class="custom-btn-group">
                                        <a href="{{ route('sistem.info') }}" class="btn btn-primary rounded"
                                            type="button">
                                            <span class="icon-refresh refresh"></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>




                <!-- Card start -->
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <h5 class="mt-3">Daftar Versi Aplikasi</h5>
                            </div>
                            <div class="col-6">
                                <div class="d-flex justify-content-end">
                                    <div class="custom-btn-group">
                                        <a href="{{ route('sistem.info.tambah') }}" class="btn btn-primary rounded"
                                            type="button">+
                                            Tambah Versi</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive mt-4">
                            <table id="highlightRowColumn" class="table custom-table">
                                <thead>
                                    <tr>
                                        <th>Aksi</th>
                                        <th>Kode Versi</th>
                                        <th>Nama Versi</th>
                                        <th>Tanggal Rilis</th>
                                        <th>Fitur-Fitur</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sistem_info as $c)
                                        <tr>
                                            <td>
                                                <div class="actions">
                                                    <a href="{{ route('sistem.info.edit', [$c->id]) }}"
                                                        data-toggle="tooltip" data-placement="top" title="Ubah Versi"
                                                        data-original-title="Edit">
                                                        <i class="icon-edit1 text-info"></i>
                                                    </a>
                                                    <form id="delete-{{ $c->id }}"
                                                        action="{{ route('sistem.info.destroy', [$c->id]) }}"
                                                        method="POST">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button type="submit" data-formid="{{ $c->id }}"
                                                            data-nama="{{ $c->nama_versi }}" data-toggle="tooltip"
                                                            data-placement="top" title="Hapus Versi"
                                                            class="btn btn-link text-decoration-none delete-button">
                                                            <i class="icon-trash text-danger"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                            <td>
                                                {{ $c->kode_versi }}
                                            </td>
                                            <td>
                                                {{ $c->nama_versi }}
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($c->tanggal_rilis)->format('d F Y') }}</td>
                                            <td>
                                                {!! $c->konten !!}
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
