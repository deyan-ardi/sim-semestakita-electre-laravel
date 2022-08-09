@extends('errors.layout.app')
{{-- Meta --}}
@section('meta-name', '404 Not Found')
@section('meta-description', 'Data 404 Not Found')
@section('meta-keyword', '404 Not Found')
{{-- End Meta --}}
@section('title', '404 Not Found')
@section('content')
    <div class="main-wrapper">
        <div class="error-box">
            <h1>404</h1>
            <h3 class="h2 mb-3"><i class="fas fa-exclamation-circle"></i> Oops! Anda Kesasar!</h3>
            <p class="h4 font-weight-normal">Halaman yang dicari tidak ditemukan </p>
            <a href="#" onclick="goBack()" class="btn btn-primary">Kembali</a>
        </div>
    </div>
@endsection
