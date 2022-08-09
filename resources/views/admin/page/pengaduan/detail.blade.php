@extends('admin.layouts.kasir')
{{-- Meta --}}
@section('meta-name', 'Detail Pengaduan, Kritik, dan Saran')
@section('meta-description', 'Data Detail Pengaduan, Kritik, dan Saran')
@section('meta-keyword', 'Detail Pengaduan, Kritik, dan Saran')
{{-- End Meta --}}
@section('title', 'Detail Pengaduan, Kritik, dan Saran')
@section('content')

    <!-- Content wrapper start -->
    <div class="content-wrapper">

        <!-- Row start -->
        <div class="row gutters">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('pengaduan') }}" class="btn btn-danger mb-3"><i
                                class="icon-arrow-left"></i>Kembali</a>
                    </div>
                </div>
                <!-- Card start -->
                <div class="card">
                    <div class="card-header-lg">
                        <h4>{{ $find->kategori }} Layanan Dari {{ $find->user->name }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row gutters">
                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
                                <div class="row gutters">
                                    @if (empty($find->gambar))
                                        <img src="{{ asset('assets/admin/img/default-artikel.png') }}"
                                            class="img-fluid img-preview" alt="Image">
                                    @else
                                        <img src="{{ asset('storage/' . $find->gambar) }}" class="img-fluid img-preview"
                                            alt="Image">
                                    @endif
                                </div>
                            </div>
                            <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12">
                                <h3>{{ $find->judul }}</h3>
                                <small>Kategori {{ $find->kategori }} Layanan &bull; Tanggal
                                    {{ \Carbon\Carbon::parse($find->updated_at)->format('d F Y,H:i') }} WITA &bull;
                                    Pengaduan Oleh {{ $find->user->name }}</small>
                                <div class="mt-4">
                                    {{ $find->konten }}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- Card end -->

            </div>
        </div>
        <!-- Row end -->

    </div>
    <!-- Content wrapper end -->


@endsection
