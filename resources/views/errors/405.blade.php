@extends('errors.layout.app')
{{-- Meta --}}
@section('meta-name', '405 Not Allowed')
@section('meta-description', 'Data 405 Not Allowed')
@section('meta-keyword', '405 Not Allowed')
{{-- End Meta --}}
@section('title', '405 Method Not Allowed')
@section('content')
    <div class="main-wrapper">
        <div class="error-box">
            <h1>403</h1>
            <h3 class="h2 mb-3"><i class="fas fa-exclamation-circle"></i> Oops! Anda Kesasar!</h3>
            <p class="h4 font-weight-normal">Halaman yang dicari tidak dapat diakses</p>
            <a href="#" onclick="goBack()" class="btn btn-primary">Kembali</a>
        </div>
    </div>
@endsection
