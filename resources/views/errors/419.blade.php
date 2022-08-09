@extends('errors.layout.app')
{{-- Meta --}}
@section('meta-name', '419 Halaman Kedaluwarsa')
@section('meta-description', 'Data 419 Halaman Kedaluwarsa')
@section('meta-keyword', '419 Halaman Kedaluwarsa')
{{-- End Meta --}}
@section('title', '419 Halaman Kedaluwarsa')
@section('content')
    <div class="main-wrapper">
        <div class="error-box">
            <h1>419</h1>
            <h3 class="h2 mb-3"><i class="fas fa-exclamation-circle"></i> Oops! Halaman Kedaluwarsa!</h3>
            <p class="h4 font-weight-normal">Halaman yang diakses telah kedaluwarsa, silahkan akses ulang</p>
            <a href="#" onclick="goBack()" class="btn btn-primary">Kembali</a>
        </div>
    </div>
@endsection
