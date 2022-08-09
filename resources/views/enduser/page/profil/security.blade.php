@extends('enduser.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Keamanan Pengguna')
@section('meta-description', 'Data Keamanan Pengguna')
@section('meta-keyword', 'Keamanan Pengguna')
{{-- End Meta --}}
@section('title', 'Keamanan Pengguna')
@section('content')
    <div class="content container-fluid">
        @include('enduser.widgets.profile_menu')
        <div class="row">
            <div class="col-12 m-b-100">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Informasi Keamanan Akun</h5>
                    </div>
                    <div class="card-body">
                        @if (!empty(Auth::user()->re_email))
                            <div class="alert alert-success">Permintaan Pergantian Email, Kami Mengirimkan Kode Aktivasi
                                Ke Alamat Email
                                <u>{{ Auth::user()->re_email }}</u>
                            </div>
                        @endif
                        <br>
                        <form method="POST" action="{{ route('enduser.profil.security.aksi', [Auth::user()->id]) }}">
                            @csrf
                            <div class="row form-group">
                                <label for="email" class="col-xl-2 col-form-label input-label">Alamat Email</label>
                                <div class="col-xl-10">
                                    <input type="email" name="email" required
                                        class="form-control @error('email') is-invalid @enderror" id="email"
                                        placeholder="Alamat Email Anda" value="{{ Auth::user()->email }}">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row form-group">
                                <label for="password" class="col-xl-2 col-form-label input-label">Kata Sandi Baru</label>
                                <div class="col-xl-10">
                                    <div class="pass-group">
                                        <input type="password" id="password" minlength="8" name="password"
                                            class="form-control pass-input @error('password') is-invalid @enderror"
                                            autocomplete="new-password" placeholder="********">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <span class="fas fa-eye toggle-password" id="toggle-password-1"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label for="password-confirm" class="col-xl-2 col-form-label input-label">Ulangi Kata Sandi
                                    Baru</label>
                                <div class="col-xl-10">
                                    <div class="pass-group">
                                        <input type="password" id="password-confirm" minlength="8" name="re-password"
                                            placeholder="********" class="form-control pass-input-2"
                                            autocomplete="new-password">
                                        <span class="fas fa-eye toggle-password" id="toggle-password-2"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
