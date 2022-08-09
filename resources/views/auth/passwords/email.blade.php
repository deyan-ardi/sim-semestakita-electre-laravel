@extends('auth.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Lupa Kata Sandi, Kirim Email')
@section('meta-description', 'Data Lupa Kata Sandi, Kirim Email')
@section('meta-keyword', 'Lupa Kata Sandi, Kirim Email')
{{-- End Meta --}}
@section('title', 'Lupa Kata Sandi - Kirim Email')
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
                            <form method="POST" action="{{ route('reset.password.aksi') }}">
                                @csrf
                                <div class="form-group">
                                    <input id="email" type="email" placeholder="example@mail.com"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
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
