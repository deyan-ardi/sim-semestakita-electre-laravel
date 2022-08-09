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
                            <h1>Atur Ulang Kata Sandi</h1>
                            <p class="account-subtitle">Silahkan atur ulang kata sandimu disini.</p>
                            <form method="POST" action="{{ route('reset.password.update') }}">
                                @csrf
                                <input type="hidden" name="token" value="{{ $token }}">

                                <div class="form-group">
                                    <label class="form-control-label">Alamat Email</label>
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        placeholder="example@mail.com" value="{{ $email ?? old('email') }}" required
                                        autocomplete="email" autofocus>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Kata Sandi Baru</label>
                                    <div class="pass-group">
                                        <input id="password" type="password"
                                            class="form-control pass-input @error('password') is-invalid @enderror"
                                            name="password" required autocomplete="new-password" placeholder="********">
                                        <span class="fas fa-eye toggle-password" id="toggle-password-1"></span>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Konfirmasi Kata Sandi</label>
                                    <div class="pass-group">
                                        <input id="password-confirm" type="password" class="form-control pass-input-2"
                                            name="password_confirmation" required autocomplete="new-password"
                                            placeholder="********">
                                        <span class="fas fa-eye toggle-password" id="toggle-password-2"></span>
                                    </div>
                                </div>
                                <button class="btn btn-lg btn-block btn-primary" type="submit">Ubah Kata Sandi</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
