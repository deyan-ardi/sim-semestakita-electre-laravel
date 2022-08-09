@extends('enduser.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Detail Informasi Pembayaran Iuran')
@section('meta-description', 'Data Detail Informasi Pembayaran Iuran')
@section('meta-keyword', 'Detail Informasi Pembayaran Iuran')
{{-- End Meta --}}
@section('title', 'Detail Informasi Pembayaran Iuran')
@section('content')
    <div class="content container-fluid">
        <div class="row justify-content-center">
            <div class="col-xl-12 m-b-100">
                <div class="back-button">
                    <a href="{{ url()->previous() }}">
                        <i class="fas fa-chevron-left text-primary"></i>
                        Kembali
                    </a>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="invoice-item">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="invoice-logo">
                                        <img src="{{ asset('assets/enduser/img/logo.png') }}" alt="logo">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="invoice-item">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="invoice-info">
                                        <strong class="customer-text">Informasi Pembayaran</strong>
                                        <p class="invoice-details invoice-details-two">
                                            {{ $user->name }} <br>
                                            {{ $user->alamat }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="invoice-info invoice-info2">
                                        <p class="invoice-details mt-4">
                                            <span class="text-bold">No. Tagihan: </span>
                                            {{ $pembayaran->no_tagihan }}<br>
                                            <span class="text-bold">No. Pembayaran: </span>
                                            {{ $pembayaran->no_pembayaran }}<br>
                                            <span class="text-bold">Tanggal Bayar: </span>
                                            {{ $pembayaran->created_at->format('d F Y') }}<br>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="invoice-item invoice-table-wrap">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="invoice-table table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Deksripsi</th>
                                                    <th class="text-center">Jumlah Pembayaran</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Pembayaran Iuran Bulan
                                                        {{ $tagihan->created_at->format('F Y') }}
                                                    </td>
                                                    <td class="text-center">@currency($tagihan->sub_total)</td>
                                                </tr>
                                                <tr>
                                                    <td>Denda
                                                    </td>
                                                    <td class="text-center">@currency($tagihan->sub_total_denda)</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right">
                                                        Total Pembayaran:
                                                    </td>
                                                    <td class="text-center">@currency($tagihan->total_tagihan)
                                                    </td>
                                                </tr>
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

        });
    </script>
@endsection
