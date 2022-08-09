@extends('auth.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Lupa Kata Sandi, Sandi Baru')
@section('meta-description', 'Data Lupa Kata Sandi, Sandi Baru')
@section('meta-keyword', 'Lupa Kata Sandi, Sandi Baru')
{{-- End Meta --}}
@section('title', 'Lupa Kata Sandi - Sandi Baru')
@section('content')
    <div class="main-wrapper login-body">
        <div class="login-wrapper">
            <div class="container">
                <img class="img-fluid logo-login mb-2" src="{{ config('mitra.icon_text') }}" alt="Logo">
                <div class="loginbox">
                    <div class="login-right">
                        <div class="login-right-wrap">
                            <h1>Lupa kata sandi</h1>
                            <p class="account-subtitle">Untuk mengatur ulang kata sandi silahkan masukkan email yang
                                terdaftar.</p>
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif
                            <form method="POST" action="{{ route('password.confirm') }}">
                                @csrf
                                <div class="form-group">
                                    <label class="form-control-label">Kata Sandi Baru</label>
                                    <div class="pass-group">
                                        <input id="password" type="password"
                                            class="form-control pass-input @error('password') is-invalid @enderror"
                                            name="password" required autocomplete="new-password">
                                        <span class="fas fa-eye toggle-password"></span>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <button class="btn btn-lg btn-block btn-primary" type="submit">Atur Ulang Kata
                                    Sandi</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
