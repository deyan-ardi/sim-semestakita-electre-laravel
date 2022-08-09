@extends('enduser.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Hubungi Pengelola')
@section('meta-description', 'Data Hubungi Pengelola')
@section('meta-keyword', 'Hubungi Pengelola')
{{-- End Meta --}}
@section('title', 'Hubungi Pengelola')
@section('content')
    <div class="content container-fluid contact-us-section">
        <div class="row">
            <div class="col-12 m-b-100">

                <div class="section-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="section-title">Informasi Feedback Pengguna</h3>
                            <div class="line"></div>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('enduser.hubungi.tambah') }}" class="btn btn-primary"><i
                                    class="fas fa-plus"></i> Tambah Feedback</a>
                        </div>
                    </div>
                </div>

                @php
                    $pengaduan = 0;
                    $kritik = 0;
                    $saran = 0;
                    foreach ($feedback as $f) {
                        if ($f->kategori == 'Pengaduan') {
                            $pengaduan++;
                        } elseif ($f->kategori == 'Kritik') {
                            $kritik++;
                        } else {
                            $saran++;
                        }
                    }
                @endphp
                <div class="feedback-info">
                    <div class="row">
                        <div class="col-xl-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="dash-widget-header">
                                        <span class="dash-widget-icon bg-2">
                                            <i class="fas fa-comment-alt"></i>
                                        </span>
                                        <div class="dash-count">
                                            <div class="dash-title">Total Pengaduan</div>
                                            <div class="dash-counts">
                                                <h4 class="text-info">{{ $pengaduan }} Feedback</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="dash-widget-header">
                                        <span class="dash-widget-icon bg-3">
                                            <i class="fas fa-check-double"></i>
                                        </span>
                                        <div class="dash-count">
                                            <div class="dash-title">Total Kritik</div>
                                            <div class="dash-counts">
                                                <h4 class="text-success">{{ $kritik }} Feedback</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="dash-widget-header">
                                        <span class="dash-widget-icon bg-1">
                                            <i class="fas fa-comments"></i>
                                        </span>
                                        <div class="dash-count">
                                            <div class="dash-title">Total Saran</div>
                                            <div class="dash-counts">
                                                <h4 class="text-warning">{{ $saran }} Feedback</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="page-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="section-title">Daftar Riwayat Feedback</h3>
                            <div class="line"></div>
                        </div>
                        <div class="col-auto">
                            <a class="btn btn-primary filter-btn" href="javascript:void(0);" id="filter_search"> Filter
                                <i class="fas fa-filter"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div id="filter_inputs" class="card filter-card">
                    <div class="card-body pb-4">
                        <div class="row">
                            <div class="col-12">
                                <form class="row" action="{{ route('enduser.hubungi.filter') }}" method="GET">
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-sm-1 d-flex align-items-center">
                                                <span class="p">Filter Data</span>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group m-0">
                                                    <label class="m-0 mb-2" for="tanggal_awal"><small>Tanggal
                                                            Awal</small></label>
                                                    <input type="date" required id="tanggal_awal" class="form-control"
                                                        name="tanggal_awal" value="{{ request()->tanggal_awal }}">
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group m-0">
                                                    <label class="m-0 mb-2" for="tanggal_akhir"><small>Tanggal
                                                            Akhir</small></label>
                                                    <input type="date" required id="tanggal_akhir" class="form-control"
                                                        name="tanggal_akhir" value="{{ request()->tanggal_akhir }}">
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group m-0">
                                                    <label class="m-0 mb-2" for="status"><small>Status</small></label>
                                                    <select required name="status" id="status"
                                                        class="form-control @error('status') is-invalid @enderror">
                                                        <option value="Semua"
                                                            {{ request()->status == 'Semua' ? 'selected' : '' }}>
                                                            Semua
                                                        </option>
                                                        <option value="Pengaduan"
                                                            {{ request()->status == 'Pengaduan' ? 'selected' : '' }}>
                                                            Pengaduan
                                                        </option>
                                                        <option value="Kritik"
                                                            {{ request()->status == 'Kritik' ? 'selected' : '' }}>
                                                            Kritik
                                                        </option>
                                                        <option value="Saran"
                                                            {{ request()->status == 'Saran' ? 'selected' : '' }}>
                                                            Saran
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="form-group m-0">
                                                    <label class="m-0 mb-2" for=""></label>
                                                    <div class="d-flex justify-content-end">
                                                        <button
                                                            class="btn btn-sm mt-2 btn-primary rounded px-5">Filter</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="feedback-history">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="datatable table table-stripped">
                                            <thead>
                                                <tr>
                                                    <th>Aksi</th>
                                                    <th>Tanggal</th>
                                                    <th>Judul</th>
                                                    <th>Gambar/Foto</th>
                                                    <th>Feedback</th>
                                                    <th>Kategori</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($feedback as $f)
                                                    <tr class="text-justify">
                                                        <td>
                                                            <div class="actions">
                                                                <a href="{{ route('enduser.hubungi.ubah', [$f->id]) }}"
                                                                    data-toggle="tooltip" data-placement="top"
                                                                    title="Edit Feedback" data-original-title="Edit">
                                                                    <i class="fas fa-edit text-warning mr-2"></i>
                                                                </a>
                                                                <a href="{{ route('enduser.hubungi.delete', [$f->id]) }}"
                                                                    data-toggle="tooltip" data-placement="top"
                                                                    title="Hapus Feedback" data-original-title="Hapus">
                                                                    <i class="fas fa-trash-alt text-danger"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                        <td>{{ \Carbon\Carbon::parse($f->updated_at)->format('d F Y,H:i') }}
                                                            WITA</td>
                                                        <td>
                                                            {{ $f->judul }}
                                                        </td>
                                                        <td>
                                                            <div class="avatar avatar-lg">
                                                                @if (!empty($f->gambar))
                                                                    <img src="{{ asset('storage/' . $f->gambar) }}" alt=""
                                                                        class="img-fluid" width="50%">
                                                                @else
                                                                    <img src="{{ asset('assets/admin/img/default-artikel.png') }}"
                                                                        alt="" class="img-fluid" width="50%">
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td>
                                                            {!! $f->konten !!}
                                                        </td>
                                                        <td>{{ ucWords($f->kategori) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom-script')
    <script>
        $(document).ready(function() {
            var table = $('#feedback-table').DataTable({
                responsive: true,
                paging: true
            });
        });
    </script>
@endsection
