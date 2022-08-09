@extends('admin.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Master Data, Konfigurasi Sistem')
@section('meta-description', 'Data Master Data, Konfigurasi Sistem')
@section('meta-keyword', 'Master Data, Konfigurasi Sistem')
{{-- End Meta --}}
@section('title', 'Master - Konfigurasi Sistem')
@section('content')

    <!-- Content wrapper start -->
    <div class="content-wrapper">

        <!-- Row start -->
        <div class="row gutters">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
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
                <!-- Card start -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-title mb-3">
                            Tambah Data Konfigurasi Kriteria Penilaian
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="text-danger mb-3 d-flex justify-content-end">* Wajib Diisi</div>
                        {{-- Form Start --}}
                        <form action="{{ route('konfigurasi.kriteria.store') }}" method="POST">
                            @csrf

                            <div class="row gutters mb-3">
                                <label class="col-sm-2 col-form-label">Nama Kriteria Penilaian<span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input name="nama_kriteria" value="{{ old('nama_kriteria') ?? '' }}"
                                        style="text-transform: capitalize;" type="text" class="form-control" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-10 offset-sm-2">
                                    <div class="form-actions-footer">
                                        <a href="{{ route('konfigurasi') }}" class="btn btn-danger">Kembali</a>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        {{-- Form end --}}
                    </div>
                </div>
                <!-- Card end -->

            </div>
        </div>
        <!-- Row end -->
    </div>
@endsection
