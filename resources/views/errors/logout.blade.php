@extends('errors.layout.app')
{{-- Meta --}}
@section('meta-name', 'Konfirmasi Log Out')
@section('meta-description', 'Data Konfirmasi Log Out')
@section('meta-keyword', 'Konfirmasi Log Out')
{{-- End Meta --}}
@section('title', 'Konfirmasi Log Out')
@section('content')
    <div class="main-wrapper login-body">
        <div class="login-wrapper">
            <div class="container">
                <img class="img-fluid logo-login mb-2" src="{{ config('mitra.icon_text') }}" alt="Logo">
                <div class="loginbox">
                    <div class="login-right">
                        <div class="login-right-wrap">
                            <h1>Sesi Telah Diakhiri</h1>
                            <p class="account-subtitle">Terimakasih sudah menggunakan Sistem Semesta Kita, Silahkan
                                kembali kehalaman Login</p>

                            <a href="{{ route('login') }}"><button type="submit" class="btn btn-block btn-primary" type="button">Kehalaman
                                Login</button></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
