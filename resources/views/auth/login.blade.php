@extends('auth.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Masuk Akun')
@section('meta-description', 'Data Masuk Akun')
@section('meta-keyword', 'Masuk Akun')
{{-- End Meta --}}
@section('title', 'Masuk Akun')
@section('content')
    <div class="main-wrapper login-body">
        <div class="login-wrapper">
            <div class="container">
                <img class="img-fluid logo-login mb-2" src="{{ config('mitra.icon_text') }}" alt="Logo">
                <div class="loginbox">
                    <div class="login-right">
                        <div class="login-right-wrap">
                            <h1>Masuk</h1>
                            <p class="account-subtitle">Silahkan masuk menggunakan email dan kata sandi yang terdaftar untuk
                                dapat menggunakan sistem.</p>
                            <form method="POST" action="{{ route('login') }}" id="form-login">
                                @csrf
                                <div class="form-group">
                                    <label class="form-control-label">Alamat Email</label>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                        name="email" value="{{ old('email') }}" placeholder="example@mail.com" required
                                        autocomplete="email" autofocus>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-control-label">Kata Sandi</label>
                                    <div class="pass-group">
                                        <input id="password" type="password"
                                            class="form-control pass-input @error('password') is-invalid @enderror"
                                            name="password" placeholder="********" required autocomplete="current-password">
                                        <span class="fas fa-eye toggle-password" id="toggle-password-1"></span>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" name="remember" class="custom-control-input"
                                                    {{ old('remember') ? 'checked' : '' }} id="cb1">
                                                <label class="custom-control-label" for="cb1">Ingat saya</label>
                                            </div>
                                        </div>
                                        <div class="col-6 text-right">
                                            <a class="forgot-link" href="{{ route('password.request') }}">Lupa kata
                                                sandi?</a>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="id_login" value="{{ Crypt::encrypt('0') }}">
                                <button class="btn btn-lg btn-block btn-primary" type="submit">Masuk</button>
                                <div class="login-or">
                                    <span class="or-line"></span>
                                    <span class="span-or">atau</span>
                                </div>

                                <div class="mb-3">
                                    <a href="{{ route('whatsapp.login') }}"><button type="button"
                                            class="btn facebook btn-lg btn-block border-success text-primary"><i
                                                class="fab fa-whatsapp" style="font-size:1.2rem"></i> Masuk dengan
                                            WhatsApp</button></a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <p class="text-center">{{ date('Y') }} &copy; <span class="text-success">PT Ganadev Multi Solusi</span></p>
            </div>
        </div>
    </div>
@endsection
