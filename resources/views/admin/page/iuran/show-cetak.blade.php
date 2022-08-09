@extends('admin.layouts.kasir')
{{-- Meta --}}
@section('meta-name', 'Kasir Pembayaran Iuran Rutin, Selesai Transaksi')
@section('meta-description', 'Data Kasir Pembayaran Iuran Rutin, Selesai Transaksi')
@section('meta-keyword', 'Kasir Pembayaran Iuran Rutin, Selesai Transaksi')
{{-- End Meta --}}
@section('title', 'Kasir Pembayaran Iuran Rutin - Selesai Transaksi')
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
                        <a href="{{ route('iuran.kasir') }}" class="btn btn-danger mb-3"><i
                                class="icon-arrow-left"></i>Kembali</a>
                    </div>
                </div>
                <!-- Card end -->

                <!-- Card start -->
                <div class="card">
                    <div class="card-header-lg">
                        <h4>Informasi Pembayaran Tagihan Iuran</h4>
                    </div>
                    <div class="card-body">
                        <div class="card-body">
                            <!-- Row start -->
                            <div class="row justify-content-center">
                                <div class="col-xl-8 text-center col-lg-8 col-md-12 col-sm-12 col-12">
                                    <h5>Penyetoran Sampah Dengan Kode Transaksi @foreach ($pembayaran as $data)
                                            {{ $data->no_pembayaran . ',' }}
                                        @endforeach Berhasil
                                        Dilakukan, {{ $pembayaran->count() }} Tagihan Iuran Atas Nama
                                        {{ $pembayaran[0]->user->name }} Dengan No Tagihan @foreach ($pembayaran as $data)
                                            {{ $data->no_tagihan . ',' }}
                                        @endforeach Telah
                                        Berhasil Dilunasi, Silahkan pilih menu berikut untuk melanjutkan</h5>
                                    <div class="row mt-4">
                                        <div class="col-4 text-center">
                                            @php
                                                $id_bayar = [];
                                                foreach ($pembayaran as $data) {
                                                    array_push($id_bayar, $data->id);
                                                }
                                                $arr_id = base64_encode(json_encode($id_bayar));
                                            @endphp
                                            <form target="_blank" rel="noopener noreferrer"
                                                action="{{ route('iuran.kasir.cetak.aksi', [$arr_id]) }}" method="post">
                                                @csrf
                                                <button type="submit" class="btn btn-primary"> Cetak Invoice</button>
                                            </form>
                                        </div>
                                        <div class="col-4 text-center">
                                            <a href="{{ route('iuran.kasir') }}"><button class="btn btn-info">Tambah
                                                    Pembayaran Lain</button></a>
                                        </div>
                                        <div class="col-4 text-center">
                                            <a href="{{ route('rekapan-iuran') }}"><button class="btn btn-warning">Lihat
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
