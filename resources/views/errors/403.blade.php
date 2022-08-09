@extends('errors.layout.app')
{{-- Meta --}}
@section('meta-name', '403 Access Denied')
@section('meta-description', 'Data 403 Access Denied')
@section('meta-keyword', '403 Access Denied')
{{-- End Meta --}}
@section('title', '403 Access Denied')
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
