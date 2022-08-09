@extends('admin.layouts.kasir')
{{-- Meta --}}
@section('meta-name', 'Notifikasi Sistem')
@section('meta-description', 'Data Notifikasi Sistem')
@section('meta-keyword', 'Notifikasi Sistem')
{{-- End Meta --}}
@section('title', 'Detail Notifikasi Sistem')
@section('content')

    <!-- Content wrapper start -->
    <div class="content-wrapper">

        <!-- Row start -->
        <div class="row gutters">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('admin') }}" class="btn btn-danger mb-3"><i
                                class="icon-arrow-left"></i>Kembali</a>
                    </div>
                </div>
                <!-- Card start -->
                <div class="card">
                    <div class="card-header-lg">
                        <h4></h4>
                    </div>
                    <div class="card-body">
                        <div class="row gutters justify-content-center">
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                <h5>NOTIFIKASI {{ strtoupper($detail->judul) }}</h5>
                                <p>Diterima {{ \Carbon\Carbon::parse($detail->created_at)->format('d F Y H:i') }} WITA</p>
                                <p class="mt-4">{{ $detail->konten }}</p>
                                @php
                                    if ($detail->key == 'pengaduan') {
                                        $send = route('pengaduan');
                                    } elseif ($detail->key == 'iuran') {
                                        $send = route('rekapan-iuran');
                                    } elseif ($detail->key == 'nabung') {
                                        $send = route('rekapan-sampah');
                                    } elseif ($detail->key == 'angkut') {
                                        $send = route('pengangkutan-penilaian');
                                    } elseif ($detail->key == 'tarik') {
                                        $send = route('rekapan-tabungan');
                                    } elseif ($detail->key == 'jemput') {
                                        $send = route('penjemputan');
                                    } else {
                                        $send = route('notifikasi');
                                    }
                                @endphp
                                <a href="{{ $send }}"><button type="button"
                                        class="btn btn-primary mt-5 col-12">Lihat
                                        Informasi</button></a>
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
