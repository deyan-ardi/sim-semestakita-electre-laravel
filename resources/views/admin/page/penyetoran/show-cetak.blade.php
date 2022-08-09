@extends('admin.layouts.kasir')
{{-- Meta --}}
@section('meta-name', 'Kasir Penyetoran Sampah, Selesai Transaksi')
@section('meta-description', 'Data Kasir Penyetoran Sampah, Selesai Transaksi')
@section('meta-keyword', 'Kasir Penyetoran Sampah, Selesai Transaksi')
{{-- End Meta --}}
@section('title', 'Kasir Penyetoran Sampah - Selesai Transaksi')
@section('footer')
    <script>
        $(document).ready(function() {
            window.history.pushState(null, "", window.location.href);
            window.onpopstate = function() {
                window.history.pushState(null, "", window.location.href);
            };
        });
    </script>
@endsection
@section('content')

    <!-- Content wrapper start -->
    <div class="content-wrapper">

        <!-- Row start -->
        <div class="row gutters">
            <div class="col-xl-12">
                <!-- Card start -->
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('penyetoran') }}" class="btn btn-danger mb-3"><i
                                class="icon-arrow-left"></i>Kembali</a>
                    </div>
                </div>
                <!-- Card end -->

                <!-- Card start -->
                <div class="card">
                    <div class="card-header-lg">
                        <h4>Informasi Nabung Sampah</h4>
                    </div>
                    <div class="card-body">
                        <div class="card-body">
                            <!-- Row start -->
                            <div class="row justify-content-center">
                                <div class="col-xl-8 text-center col-lg-8 col-md-12 col-sm-12 col-12">
                                    <h5>Nabung Sampah Dengan Kode Transaksi {{ $rekapan->kode_transaksi }} Berhasil
                                        Dilakukan, Dana Hasil Setoran Sampah dari Nasabah {{ $rekapan->user->name }} Telah
                                        Masuk Ke Tabungan Nasabah, Silahkan pilih menu berikut untuk melanjutkan</h5>
                                    <div class="row mt-4">
                                        <div class="col-4 text-center">
                                            <form target="_blank" rel="noopener noreferrer"
                                                action="{{ route('penyetoran.cetak.aksi', $rekapan) }}" method="post">
                                                @csrf
                                                <button type="submit" class="btn btn-primary"> Cetak Invoice</button>
                                            </form>
                                        </div>
                                        <div class="col-4 text-center">
                                            <a href="{{ route('penyetoran') }}"><button class="btn btn-success">Tambah
                                                    Nabung Lain</button></a>
                                        </div>
                                        <div class="col-4 text-center">
                                            <a href="{{ route('rekapan-sampah') }}"><button class="btn btn-warning">Lihat
                                                    History</button></a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!-- Row end -->
                        </div>
                    </div>
                </div>
                <!-- Card end -->
            </div>
        </div>
        <!-- Row end -->
    </div>
@endsection
