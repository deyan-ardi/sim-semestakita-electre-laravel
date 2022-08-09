@extends('admin.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Notifikasi')
@section('meta-description', 'Data Notifikasi')
@section('meta-keyword', 'Notifikasi')
{{-- End Meta --}}
@section('title', 'Notifikasi')
@section('content')

    <!-- Content wrapper start -->
    <div class="content-wrapper">

        <!-- Row start -->
        <div class="row gutters">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                @php
                    $total = 0;
                    $nasabah = 0;
                    $pelanggan = 0;
                    foreach ($notifikasi as $n) {
                        $total++;
                        if ($n->user->role == 4) {
                            $nasabah++;
                        } else {
                            $pelanggan++;
                        }
                    }
                @endphp
                <h4 class="mb-3">Manajemen Kirim Notifikasi</h4>
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
                        <form class="row" action="{{ route('notifikasi.filter') }}" method="GET">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-sm-2 d-flex align-items-center">
                                        <span class="p">Filter Data</span>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group m-0">
                                            <label class="m-0 mb-2" for="tanggal_awal"><small>Tanggal
                                                    Awal</small></label>
                                            <input type="date" id="tanggal_awal" class="form-control " name="tanggal_awal"
                                                value="{{ old('tanggal_awal') ?? request()->tanggal_awal }}" required>

                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group m-0">
                                            <label class="m-0 mb-2" for="tanggal_akhir"><small>Tanggal
                                                    Akhir</small></label>
                                            <input type="date" id="tanggal_akhir" required class="form-control "
                                                name="tanggal_akhir"
                                                value="{{ old('tanggal_akhir') ?? request()->tanggal_akhir }}">

                                        </div>
                                    </div>
                                    <div class="col-sm-2">
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
                <div class="row">
                    <div class="col-lg-4 col-md-4">
                        <div class="card">
                            <div class="card-header-lg">
                                <p class="text-left">Total Notifikasi Terkirim</p>
                                <div class="text-right">
                                    <h6>{{ $total }} Notif</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <div class="card">
                            <div class="card-header-lg">
                                <p class="text-left ">Total Notifikasi Untuk Nasabah</p>
                                <div class="text-right">
                                    <h6 class="text-success">{{ $nasabah }} Notif</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <div class="card">
                            <div class="card-header-lg">
                                <p class="text-left">Total Notifikasi Untuk Pelanggan</p>
                                <div class="text-right">
                                    <h6 class="text-warning">{{ $pelanggan }} Notif</h6>
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
                                <h5 class="mt-3">Daftar Notifikasi Terkirim</h5>
                            </div>
                            <div class="col-6">
                                <div class="d-flex justify-content-end">
                                    <div class="custom-btn-group">
                                        <a href="{{ route('notifikasi.create') }}" class="btn btn-primary rounded"><i
                                                class="icon-plus refresh"></i>
                                            Buat Notifikasi</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive mt-4">
                            <table id="highlightRowColumn" class="table custom-table">
                                <thead>
                                    <tr>
                                        <th>Fitur</th>
                                        <th>Tanggal</th>
                                        <th>No Member</th>
                                        <th>Nama Member</th>
                                        <th>Kontak</th>
                                        <th>Judul Notifikasi</th>
                                        <th>Gambar</th>
                                        <th>Konten</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($notifikasi as $n)
                                        <tr>
                                            <td>
                                                <div class="actions">
                                                    <a href="{{ route('notifikasi.edit', [$n->id]) }}"
                                                        data-toggle="tooltip" data-placement="top" title="Edit Notifikasi"
                                                        data-original-title="Edit">
                                                        <i class="icon-edit1 text-primary"></i>
                                                    </a>
                                                    <form id="delete-{{ $n->id }}"
                                                        action="{{ route('notifikasi.delete', [$n->id]) }}"
                                                        method="POST">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button type="submit" data-formid="{{ $n->id }}"
                                                            data-nama="{{ $n->user->name }}" data-toggle="tooltip"
                                                            data-placement="top" title="Hapus Notifikasi"
                                                            class="btn btn-link text-decoration-none delete-button">
                                                            <i class="icon-trash text-danger"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($n->updated_at)->format('d F Y, H:i') }} WITA
                                            </td>
                                            <td>{{ $n->user->no_member }}</td>
                                            <td>{{ $n->user->name }}</td>
                                            <td><a href="https://api.whatsapp.com/send?phone=62{{ $n->user->no_telp }}"
                                                    class="text-primary"> 0{{ $n->user->no_telp }}</a></td>
                                            <td>{{ $n->judul }}</td>
                                            <td>
                                                @if (empty($notifikasi->gambar))
                                                    Tidak Ada Gambar
                                                @else
                                                    <img class="img-profile img-thumbnail border-primary " width="50%"
                                                        src="{{ asset('storage/' . $n->gambar) }}">
                                                @endif
                                            </td>
                                            <td>{!! $n->konten !!}</td>
                                            <td>{{ $n->user->role == 4 ? 'Nasabah' : 'Pelanggan' }}</td>
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
