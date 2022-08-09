@extends('admin.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Daftar Permintaan Pengangkutan Sampah')
@section('meta-description', 'Data Daftar Permintaan Pengangkutan Sampah')
@section('meta-keyword', 'Daftar Permintaan Pengangkutan Sampah')
{{-- End Meta --}}
@section('title', 'Daftar Permintaan Pengangkutan Sampah')
@section('content')

    <!-- Content wrapper start -->
    <div class="content-wrapper">

        <!-- Row start -->
        <div class="row gutters">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

                <h4 class="mb-3">Daftar Permintaan Pengangkutan Sampah</h4>
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
                        <form class="row" action="{{ route('penjemputan.filter') }}" method="GET">
                            <div class="col-lg-7">
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
                            <div class="col-lg-5">
                                <div class="row">
                                    <div class="col-sm-7">
                                        <div class="form-group m-0">
                                            <label class="m-0 mb-2" for="status"><small>Status</small></label>
                                            <select name="status" required id="status" class="form-control ">
                                                <option value="semua"
                                                    {{ request()->status == 'Semua' || old('status') == 'Semua' ? 'selected' : '' }}>
                                                    Semua</option>
                                                <option value="pending"
                                                    {{ request()->status == 'pending' || old('status') == 'pending' ? 'selected' : '' }}>
                                                    Pending</option>
                                                <option value="lunas"
                                                    {{ request()->status == 'lunas' || old('status') == 'lunas' ? 'selected' : '' }}>
                                                    Lunas/Sukses
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
                    $sukses = 0;
                    $pending = 0;
                    foreach ($pengajuan as $p) {
                        $total = $total + $p->biaya;
                        if ($p->status == 'lunas') {
                            $sukses = $sukses + $p->biaya;
                        } else {
                            $pending = $pending + $p->biaya;
                        }
                    }
                @endphp
                <div class="row">
                    <div class="col-lg-4 col-md-4">
                        <div class="card">
                            <div class="card-header-lg">
                                <p class="text-left">Total Penjemputan</p>
                                <div class="text-right">
                                    <h6>@currency($total)</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <div class="card">
                            <div class="card-header-lg">
                                <p class="text-left ">Total Sukses</p>
                                <div class="text-right">
                                    <h6 class="text-success">@currency($sukses)</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <div class="card">
                            <div class="card-header-lg">
                                <p class="text-left">Total Pending</p>
                                <div class="text-right">
                                    <h6 class="text-danger">@currency($pending)</h6>
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
                                <h5 class="mt-3">Data Permintaan</h5>
                            </div>
                            <div class="col-6">
                                <div class="d-flex justify-content-end">
                                    <div class="custom-btn-group">
                                        <form action="{{ route('penjemputan.export') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="tanggal_awal"
                                                value="{{ request()->tanggal_awal }}">
                                            <input type="hidden" name="tanggal_akhir"
                                                value="{{ request()->tanggal_akhir }}">
                                            <input type="hidden" name="status" value="{{ request()->status }}">
                                            <button class="btn btn-info rounded"><i class="icon-print refresh"></i>
                                                Export
                                                Semua</button>
                                        </form>
                                        @if (Auth::user()->role != 6)
                                            <a href="{{ route('penjemputan.create') }}"
                                                class="btn btn-primary rounded"><i class="icon-plus refresh"></i>
                                                Tambah Data</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive mt-4">
                            <table id="highlightRowColumn" class="table custom-table">
                                <thead>
                                    <tr>
                                        @if (Auth::user()->role != 6)
                                            <th>Fitur</th>
                                        @endif
                                        <th>Tanggal</th>
                                        <th>Nama Pelanggan</th>
                                        <th>Alamat Pelanggan</th>
                                        <th>Kontak Pelanggan</th>
                                        <th>Lokasi Ambil</th>
                                        <th>Jarak</th>
                                        <th>Biaya</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pengajuan as $p)
                                        <tr>
                                            @if (Auth::user()->role != 6)
                                                <td>
                                                    @if ($p->status == 'pending')
                                                        <div class="actions">
                                                            <a href="{{ route('penjemputan.edit', [$p->id]) }}"
                                                                data-toggle="tooltip" data-placement="top"
                                                                title="Edit Pengajuan" data-original-title="Edit">
                                                                <i class="icon-edit1 text-warning"></i>
                                                            </a>
                                                            <a href="#" data-toggle="tooltip" data-bs-toggle="modal"
                                                                data-bs-target="#modalSetStatus-{{ $p->id }}"
                                                                data-placement="top" title="Ubah Status"
                                                                data-original-title="Ubah Status">
                                                                <i class="icon-cog text-info ms-3"></i>
                                                            </a>

                                                            <form id="delete-{{ $p->id }}"
                                                                action="{{ route('penjemputan.delete', [$p->id]) }}"
                                                                method="POST">
                                                                @method('DELETE')
                                                                @csrf
                                                                <button type="submit" data-formid="{{ $p->id }}"
                                                                    data-nama="{{ $p->nama_pelanggan }}"
                                                                    data-toggle="tooltip" data-placement="top"
                                                                    title="Hapus Pengajuan"
                                                                    class="btn btn-link text-decoration-none delete-button">
                                                                    <i class="icon-trash text-danger"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @endif
                                                </td>
                                            @endif
                                            <td>{{ $p->tanggal }}</td>
                                            <td>{{ $p->nama_pelanggan }}</td>
                                            <td>{{ $p->alamat_pelanggan }}</td>
                                            <td>0{{ $p->kontak_pelanggan }}</td>
                                            <td>{{ $p->lokasi_ambil }}</td>
                                            <td>{{ $p->jarak }} Km</td>
                                            <td>@currency($p->biaya)</td>
                                            <td>{{ ucwords($p->status) }}</td>
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

    @foreach ($pengajuan as $p)
        <!-- Modal -->
        <div class="modal fade" id="modalSetStatus-{{ $p->id }}" tabindex="-1"
            aria-labelledby="modalSetStatus-{{ $p->id }}Label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title" id="modalSetStatus-{{ $p->id }}Label">
                            Set
                            Status
                            Pengajuan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-danger mb-3 d-flex justify-content-end">
                            *
                            Wajib Diisi</div>
                        <form action="{{ route('penjemputan.set', [$p->id]) }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label class=mb-2" for="status"><small>Status <span
                                            class="text-danger">*</span></small></label>
                                <select name="status" required id="status"
                                    class="form-control @error('status') is-invalid @enderror">
                                    <option value="lunas" {{ $p->status == 'lunas' ? 'selected' : '' }}>
                                        Sukses</option>
                                    <option value="pending" {{ $p->status == 'pending' ? 'selected' : '' }}>
                                        Pending</option>
                                </select>
                                @error('bulan')
                                    <span class=" invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="modal-footer mt-3">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-primary">Simpan
                                    Status</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal end -->
    @endforeach
@endsection
