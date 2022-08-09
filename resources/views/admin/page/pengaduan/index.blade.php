@extends('admin.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Kritik, Pengaduan, dan Saran')
@section('meta-description', 'Data Kritik, Pengaduan, dan Saran')
@section('meta-keyword', 'Kritik, Pengaduan, dan Saran')
{{-- End Meta --}}
@section('title', 'Kritik, Pengaduan, dan Saran')
@section('content')

    <!-- Content wrapper start -->
    <div class="content-wrapper">
        @php
            $pengaduan = 0;
            $kritik = 0;
            $saran = 0;
        @endphp
        @foreach ($notifikasi as $n)
            @if ($n->kategori == 'Pengaduan')
                @php
                    $pengaduan++;
                @endphp
            @elseif ($n->kategori == 'Kritik')
                @php
                    $kritik++;
                @endphp
            @else
                @php
                    $saran++;
                @endphp
            @endif
        @endforeach
        <!-- Row start -->
        <div class="row gutters">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

                <h4 class="mb-3">Kritik, Pengaduan, dan Saran</h4>
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
                        <form class="row" action="{{ route('pengaduan.filter') }}" method="GET">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-sm-2 d-flex align-items-center">
                                        <span class="p">Filter Data</span>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group m-0">
                                            <label class="m-0 mb-2" for="tanggal_awal"><small>Tanggal
                                                    Awal</small></label>
                                            <input type="date" id="tanggal_awal" required class="form-control"
                                                name="tanggal_awal"
                                                value="{{ old('tanggal_awal') ?? request()->tanggal_awal }}">

                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group m-0">
                                            <label class="m-0 mb-2" for="tanggal_akhir"><small>Tanggal
                                                    Akhir</small></label>
                                            <input type="date" id="tanggal_akhir" required class="form-control"
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
                                <p class="text-left">Total Pengaduan</p>
                                <div class="text-right">
                                    <h6>{{ $pengaduan }} Notif</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <div class="card">
                            <div class="card-header-lg">
                                <p class="text-left ">Total Kritik</p>
                                <div class="text-right">
                                    <h6 class="text-success">{{ $kritik }} Notif</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <div class="card">
                            <div class="card-header-lg">
                                <p class="text-left">Total Saran</p>
                                <div class="text-right">
                                    <h6 class="text-warning">{{ $saran }} Notif</h6>
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
                                <h5 class="mt-3">Daftar Pengaduan Masuk</h5>
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
                                        <th>Judul Pengaduan</th>
                                        <th>Kategori</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($notifikasi as $n)
                                        <tr>
                                            <td>
                                                <div class="actions">
                                                    <a href="{{ route('pengaduan.detail', [$n->id]) }}"
                                                        data-toggle="tooltip" data-placement="top" title="Lihat Detail"
                                                        data-original-title="Lihat Detail">
                                                        <i class="icon-eye text-primary"></i>
                                                    </a>
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
                                                {{ $n->kategori }} Layanan
                                            </td>
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
