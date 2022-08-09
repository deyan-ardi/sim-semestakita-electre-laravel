@extends('errors.layout.app')
{{-- Meta --}}
@section('meta-name', '500 Internal Server Error')
@section('meta-description', 'Data 500 Internal Server Error')
@section('meta-keyword', '500 Internal Server Error')
{{-- End Meta --}}
@section('title', '500 Internal Server Error')
@section('content')
    <div class="main-wrapper">
        <div class="error-box">
            <h1>500</h1>
            <h3 class="h2 mb-3"><i class="fas fa-exclamation-circle"></i> Oops! Ada Masalah!</h3>
            <p class="h4 font-weight-normal">Terjadi kesalahan saat mengakses halaman ini, silahkan hubungi administrator</p>
            <a href="#" onclick="goBack()" class="btn btn-primary">Kembali</a>
        </div>
    </div>
@endsection
