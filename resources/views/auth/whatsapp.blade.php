@extends('auth.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Masuk Via WhatsApp')
@section('meta-description', 'Data Masuk Via WhatsApp')
@section('meta-keyword', 'Masuk Via WhatsApp')
{{-- End Meta --}}
@section('title', 'Masuk Via WhatsApp')
@section('javascriptInclude')
    <script>
        $('#no_telp').bind('keyup paste', function() {
            this.value = +this.value.replace(/[^0-9]/g, '');
        });
    </script>
@endsection
@section('content')
    <div class="main-wrapper login-body">
        <div class="login-wrapper">
            <div class="container">
                <img class="img-fluid logo-login mb-2" src="{{ config('mitra.icon_text') }}" alt="Logo">
                <div class="loginbox">
                    <div class="login-right">
                        <div class="login-right-wrap">
                            <h1>Masuk</h1>
                            <p class="account-subtitle">Untuk masuk, kami akan mengirimkan Kode OTP ke nomor WhatsApp dan
                                Email akun
                                anda yang
                                telah terdaftar pada
                                sistem</p>
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="form-group">
                                    <label class="form-control-label">Nomor WhatsApp Akun</label>
                                    <div class="input-group">
                                        <span class="input-group-text " style="font-size: 12px">
                                            +62
                                        </span>
                                        <input id="no_telp" type="number"
                                            class="form-control pass-input @error('no_telp') is-invalid @enderror"
                                            name="no_telp" placeholder="Nomor Anda" min="0"
                                            onKeyPress="if(this.value.length==15) return false;" required
                                            autocomplete="current-no_telp">
                                        @error('no_telp')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                </div>
                                <input type="hidden" name="id_login" value="{{ Crypt::encrypt('1') }}">
                                <button class="btn btn-lg btn-block btn-primary" type="submit">Kirim Kode OTP</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
