@extends('errors.layout.app'){{-- Meta --}}
@section('meta-name', '408 Request Timeout')
@section('meta-description', 'Data 408 Request Timeout')
@section('meta-keyword', '408 Request Timeout')
{{-- End Meta --}}
@section('title', '408 Request Timeout')
@section('content')
    <div class="main-wrapper">
        <div class="error-box">
            <h1>408</h1>
            <h3 class="h2 mb-3"><i class="fas fa-exclamation-circle"></i> Oops! Ada Kesalahan!</h3>
            <p class="h4 font-weight-normal">Terjadi kesalahan saat mengakses halaman ini, silahkan hubungi Administrator</p>
            <a href="#" onclick="goBack()" class="btn btn-primary">Kembali</a>
        </div>
    </div>
@endsection
