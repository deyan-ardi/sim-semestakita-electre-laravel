@extends('enduser.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Detail Penyetoran Sampah')
@section('meta-description', 'Data Detail Penyetoran Sampah')
@section('meta-keyword', 'Detail Penyetoran Sampah')
{{-- End Meta --}}
@section('title', 'Detail Penyetoran Sampah')
@section('content')
    <div class="content container-fluid pickup-section">
        <div class="row">
            <div class="col-12 m-b-100">
                <div class="back-button">
                    <a href="{{ route('enduser.penyetoran.index', $user->id) }}">
                        <i class="fas fa-chevron-left text-primary"></i>
                        Kembali
                    </a>
                </div>
                <div class="card">
                    <div class="card-body">
                        @php
                            $total_berat = 0;
                            $total_beli = 0;
                            
                            foreach ($detail as $v) {
                                $total_berat = $total_berat + $v->jumlah_sampah;
                                $total_beli = $total_beli + $v->sub_total;
                            }
                        @endphp
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
                                        <strong class="customer-text">Detail Penyetoran Sampah</strong>
                                        <p class="invoice-details invoice-details-two">
                                            {{ $user->name }} <br>
                                            {{ $user->alamat }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="invoice-info invoice-info2">
                                        <p class="invoice-details mt-4">
                                            <span class="text-bold">Kode Transaksi:</span>
                                            {{ $detail[0]->rekapan_sampah->kode_transaksi }}<br>

                                            <span class="text-bold">Tanggal: </span>
                                            {{ $detail[0]->created_at->format('d F Y') }}<br>
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
                                                    <th>No.</th>
                                                    <th class="text-center">Nama Item</th>
                                                    <th class="text-center">Harga Item</th>
                                                    <th class="text-center">Berat Sampah</th>
                                                    <th class="text-right">Sub Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php($count = 1)
                                                @foreach ($detail as $v)
                                                    <tr>
                                                        <td>{{ $count++ }}</td>
                                                        <td class="text-center">{{ $v->nama_kategori }}</td>
                                                        <td class="text-center">
                                                            @currency($v->harga_kategori)</td>
                                                        <td class="text-center">{{ $v->jumlah_sampah }} KG</td>
                                                        <td class="text-right">@currency($v->sub_total)</td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <td class="text-right" colspan="3">Total Berat:</td>
                                                    <td class="text-center" colspan="2"><strong>{{ $total_berat }}
                                                            KG</strong></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right" colspan="3">Total Keuntungan:</td>
                                                    <td class="text-center" colspan="2">
                                                        <strong>@currency($total_beli)</strong>
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
            var table = $('#pickup-table').DataTable({
                responsive: true,
                paging: true
            });
        });
    </script>
@endsection
