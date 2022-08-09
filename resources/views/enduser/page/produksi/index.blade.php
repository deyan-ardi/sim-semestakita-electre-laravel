@extends('enduser.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Produk Mitra')
@section('meta-description', 'Data Produk Mitra')
@section('meta-keyword', 'Produk Mitra')
{{-- End Meta --}}
@section('title', 'Data Produk Mitra')
@section('content')
    <div class="content container-fluid">
        <div class="row">
            <div class="col-12 m-b-100">

                <div class="page-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="section-title">Hasil Produksi dan Bibit Tanaman</h3>
                            <div class="line"></div>
                        </div>
                    </div>
                </div>

                <div class="production-section">
                    <div class="row">
                        @if ($data_produksi->count() <= 0)
                            <div class="col-md-12 col-sm-12 col-12">
                                <a href="#!">
                                    <div class="card">
                                        <div class="card-img-overlay  d-flex align-items-center p-0">
                                        </div>
                                        <p class="card-title text-center flex-fill p-4"
                                            style="color: black; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            Belum Ada Hasil Produksi dan Bibit Tanaman
                                        </p>
                                    </div>
                                </a>
                            </div>
                        @else
                            @foreach ($data_produksi as $v)
                                <div class="col-md-4 col-sm-6 col-12">
                                    <a href="{{ route('enduser.produksi.detail', $v->slug) }}">
                                        <div class="card">
                                            <img src="{{ asset('storage/' . $v->gambar) }}" class="card-img-top"
                                                alt="...">
                                            <div class="card-img-overlay  d-flex align-items-center p-0">
                                                <p class="card-title text-center flex-fill p-4"
                                                    style="background-color: rgba(0, 0, 0, 0.5); color: white; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                    {{ $v->judul }}
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <div class="d-flex justify-content-center ">
                        {{ $data_produksi->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
