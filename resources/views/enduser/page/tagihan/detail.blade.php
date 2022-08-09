@extends('enduser.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Detail Tagihan Iuran')
@section('meta-description', 'Data Detail Tagihan Iuran')
@section('meta-keyword', 'Detail Tagihan Iuran')
{{-- End Meta --}}
@section('title', 'Detail Tagihan Iuran')
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
                                <div class="col-sm-12">
                                    <div class="invoice-logo">
                                        <img src="{{ asset('assets/enduser/img/logo.png') }}" alt="logo">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="invoice-item">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="invoice-info">
                                        <strong class="customer-text">Informasi Tagihan Iuran</strong>
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
                                            {{ $tagihan->no_tagihan }}<br>
                                            <span class="text-bold">Jatuh Tempo: </span>
                                            {{ date('d F Y', strtotime($tagihan->due_date)) }}<br>
                                            <span class="text-bold">Tanggal Pembayaran:
                                                @if ($tagihan->status == 'PAID')
                                                    {{ date('d F Y', strtotime($tagihan->updated_at)) }}
                                                @else
                                                    -
                                                @endif
                                            </span> <br>
                                            <span class="text-bold">Status:
                                                @if ($tagihan->status == 'PAID')
                                                    <span class="badge bg-success-light">Paid</span>
                                                @elseif ($tagihan->status == 'UNPAID')
                                                    <span class="badge bg-warning-light">Unpaid</span>
                                                @else
                                                    <span class="badge bg-danger-light">Overdue</span>
                                                @endif
                                            </span>
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
                                                        {{ date('F Y', strtotime($tagihan->tanggal)) }}</td>

                                                    <td class="text-center">@currency($tagihan->total_tagihan)</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right">
                                                        Total Tagihan:
                                                    </td>
                                                    <td class="text-center">
                                                        <span>@currency($tagihan->total_tagihan)</span>
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
