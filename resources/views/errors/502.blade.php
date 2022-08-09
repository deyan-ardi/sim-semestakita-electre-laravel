@extends('errors.layout.app')
{{-- Meta --}}
@section('meta-name', '502 Bad Gateaway')
@section('meta-description', 'Data 502 Bad Gateaway')
@section('meta-keyword', '502 Bad Gateaway')
{{-- End Meta --}}
@section('title', '502 Bad Gateaway')
@section('content')
    <div class="main-wrapper">
        <div class="error-box">
            <h1>502</h1>
            <h3 class="h2 mb-3"><i class="fas fa-exclamation-circle"></i> Oops! Ada Masalah!</h3>
            <p class="h4 font-weight-normal">Terjadi kesalahan saat mengakses halaman ini, silahkan hubungi administrator</p>
            <a href="#" onclick="goBack()" class="btn btn-primary">Kembali</a>
        </div>
    </div>
@endsection
