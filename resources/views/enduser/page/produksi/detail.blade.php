@extends('enduser.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Detail Produk Mitra')
@section('meta-description', 'Data Detail Produk Mitra')
@section('meta-keyword', 'Detail Produk Mitra')
{{-- End Meta --}}
@section('title', 'Detail Produk Mitra')
@section('content')
    <div class="content container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="article-container m-b-100">
                    <div class="back-button">
                        <a href="{{ route('enduser.produksi.index') }}">
                            <i class="fas fa-chevron-left text-primary"></i>
                            Kembali
                        </a>
                    </div>
                    <div class="card article-card">
                        <div class="card-body">
                            <div class="article-title">
                                <h3>{{ $produksi->judul }}</h3>
                                <div class="article-info text-muted">
                                    <span><i class="fas fa-calendar-alt"></i>
                                        {{ $produksi->created_at->format('d F Y') }}</span>
                                    <span><i class="fas fa-user-edit"></i> {{ $produksi->created_by }}</span>
                                </div>
                            </div>
                            <div class="article-description">
                                <div class="row">
                                    <div class="col-md-4">
                                        <img src="{{ asset('storage/' . $produksi->gambar) }}" alt="Artikel Image"
                                            class="card-img-top article-img">
                                    </div>
                                    <div class="col-md-8 mt-4" style="text-transform: capitalize;text-align:justify">
                                        <h5 class="text-muted">{{ $produksi->kategori }} Hasil Produksi Kami</h5>
                                        <p class="text-muted">Stok Tersedia
                                            {{ $produksi->stok }} KG - Harga @currency($produksi->harga) /KG</p>
                                        {!! $produksi->konten !!}
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
