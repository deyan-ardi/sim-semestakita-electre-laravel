@extends('admin.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Master Data, Kategori Sampah')
@section('meta-description', 'Data Master Data, Kategori Sampah')
@section('meta-keyword', 'Master Data, Kategori Sampah')
{{-- End Meta --}}
@section('title', 'Master - Kategori Sampah')
@section('content')

    <!-- Content wrapper start -->
    <div class="content-wrapper">

        <!-- Row start -->
        <div class="row gutters">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

                <!-- Card start -->
                <h4 class="mb-3">Master - Kategori Sampah</h4>
                <!-- Card start -->
                <div class="card">
                    <div class="card-header-lg">
                        <div>
                            <h4 class="d-flex align-items-center"><i class="icon-trash icon-large me-2"></i>Jumlah Kategori
                                ({{ $jumlahKategori }}), Organik({{ $organik }}), Non
                                Organik({{ $nonorganik }}), B3({{ $tigaT }}), Residu({{ $residu }})</h4>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end">
                                    <div class="custom-btn-group">
                                        <a href="{{ route('kategori') }}" class="btn btn-primary rounded" type="button">
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
                                <h5 class="mt-3">Data Kategori Sampah</h5>
                            </div>
                            <div class="col-6">
                                <div class="d-flex justify-content-end">
                                    <div class="custom-btn-group">
                                        <a href="{{ route('kategori.tambah') }}" class="btn btn-primary rounded"
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
                                        <th style="width: 15%">Aksi</th>
                                        <th>Nama Kategori</th>
                                        <th>Jenis Sampah</th>
                                        <th>Harga Beli (Per Kg)</th>
                                        <th>Stok Sampah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kategoriSampah as $kategori)
                                        <tr>
                                            <td>
                                                <div class="actions">
                                                    <a href="{{ route('kategori.edit', [$kategori->id]) }}"
                                                        data-toggle="tooltip" data-placement="top" title="Ubah Kategori"
                                                        data-original-title="Edit">
                                                        <i class="icon-edit1 text-info"></i>
                                                    </a>
                                                    <form id="delete-{{ $kategori->id }}"
                                                        action="{{ route('kategori.delete', [$kategori->id]) }}"
                                                        method="POST">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button type="submit" data-formid="{{ $kategori->id }}"
                                                            data-nama="{{ $kategori->name }}" data-toggle="tooltip"
                                                            data-placement="top" title="Hapus Kategori "
                                                            class="btn btn-link text-decoration-none delete-button">
                                                            <i class="icon-trash text-danger"></i>
                                                        </button>

                                                    </form>
                                                </div>
                                            </td>
                                            <td>{{ $kategori->nama_kategori }}</td>
                                            <td>{{ ucWords($kategori->jenis_sampah) }}</td>
                                            <td>@currency($kategori->harga_beli)</td>
                                            <td>{{ $kategori->total_sampah }} KG</td>
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
        <!-- Row end -->
    </div>
@endsection
