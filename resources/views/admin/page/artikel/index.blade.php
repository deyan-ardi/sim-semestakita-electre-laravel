@extends('admin.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Manajemen Artikel & Produk')
@section('meta-description', 'Data Manajemen Artikel & Produk')
@section('meta-keyword', 'Manajemen Artikel & Produk')
{{-- End Meta --}}
@section('title', 'Data Artikel & Produk')
@section('content')

    <!-- Content wrapper start -->
    <div class="content-wrapper">

        <!-- Row start -->
        <div class="row gutters">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

                <h4 class="mb-3">Manajemen Artikel dan Produk</h4>
                <!-- Card start -->
                <div class="card">
                    <div class="card-header-lg">
                        <h4 class="d-flex align-items-center"><i class="icon-book icon-large me-3"></i>Jumlah Artikel & Produk
                            ({{ $jumlahArtikel }})</h4>
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end">
                                    <div class="custom-btn-group">
                                        <a href="{{ route('artikel') }}" class="btn btn-primary rounded" type="button">
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
                                <h5 class="mt-3">Data Artikel dan Produk Mitra</h5>
                            </div>
                            <div class="col-6">
                                <div class="d-flex justify-content-end">
                                    <div class="custom-btn-group">
                                        <a href="{{ route('artikel.tambah') }}" class="btn btn-primary rounded"
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
                                        <th style="width: 10%">Aksi</th>
                                        <th>Tanggal</th>
                                        <th>Judul</th>
                                        <th style="width: 20%">Kategori</th>
                                        <th>Creator</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dataArtikel as $artikel)
                                        <tr>
                                            <td>
                                                <div class="actions">
                                                    <a href="{{ route('artikel.edit', [$artikel->id]) }}"
                                                        data-toggle="tooltip" data-placement="top" title="Ubah Artikel"
                                                        data-original-title="Edit">
                                                        <i class="icon-edit1 text-info"></i>
                                                    </a>
                                                    <form id="delete-{{ $artikel->id }}"
                                                        action="{{ route('artikel.delete', [$artikel->id]) }}"
                                                        method="POST">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button type="submit" data-formid="{{ $artikel->id }}"
                                                            data-nama="{{ $artikel->judul }}" data-toggle="tooltip"
                                                            data-placement="top" title="Hapus Artikel "
                                                            class="btn btn-link text-decoration-none fs-5 delete-button">
                                                            <i class="icon-trash text-danger"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($artikel->updated_at)->format('d F Y,H:i') }}
                                                WITA</td>
                                            <td>{{ $artikel->judul }}</td>
                                            <td>
                                                <p style="text-transform: capitalize">{{ $artikel->kategori }}</p>
                                            </td>
                                            <td>{{ $artikel->created_by }}</td>
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
