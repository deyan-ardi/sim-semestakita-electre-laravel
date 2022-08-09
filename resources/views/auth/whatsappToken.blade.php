@extends('auth.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Masuk Via WhatsApp, Kode OTP')
@section('meta-description', 'Data Masuk Via WhatsApp, Kode OTP')
@section('meta-keyword', 'Masuk Via WhatsApp, Kode OTP')
{{-- End Meta --}}
@section('title', 'Masuk Dengan WhatsApp - Kode OTP')
@section('content')
    <div class="main-wrapper login-body">
        <div class="login-wrapper">
            <div class="container">
                <img class="img-fluid logo-login mb-2" src="{{ config('mitra.icon_text') }}" alt="Logo">
                <div class="loginbox">
                    <div class="login-right">
                        <div class="login-right-wrap">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <p>Kode OTP Wajib Diisi</p>
                                </div>
                            @endif
                            <h1>Kode OTP</h1>
                            <p class="account-subtitle">Silahkan masukkan kode OTP yang telah dikirimkan melalui nomor
                                WhatsApp (<span class="text-primary">0{{ $user->no_telp }}</span>) dan Email Anda (<span
                                    class="text-primary">{{ $user->email }}</span>) </p>
                            <form method="POST" action="{{ route('whatsapp.login.validation') }}">
                                @csrf
                                <div class="form-group">
                                    <div class="row justify-content-center" id="otp-screen">
                                        <input id="otp1" type="text"
                                            class="form-control col-2 ml-2 text-center otp-input" name="otp1"
                                            min="0" maxlength="1" required autofocus>

                                        <input id="otp2" type="text"
                                            class="form-control col-2 ml-2 text-center otp-input" name="otp2"
                                            min="0" maxlength="1" required>

                                        <input id="otp3" type="text"
                                            class="form-control col-2 ml-2 text-center otp-input" name="otp3"
                                            min="0" maxlength="1" required>

                                        <input id="otp4" type="text"
                                            class="form-control col-2 ml-2 text-center otp-input" name="otp4"
                                            min="0" maxlength="1" required>
                                        <input type="hidden" name="no_telp" value="{{ $user->no_telp }}">
                                        <input type="hidden" name="id_login" value="{{ Crypt::encrypt('1') }}">
                                    </div>
                                </div>
                                <button class="btn btn-lg btn-block btn-primary" type="submit">Masuk</button>
                                <div class="login-or">
                                    <span class="or-line"></span>
                                    <span class="span-or">atau</span>
                                </div>
                            </form>
                            <div class="mb-3">
                                <p class="account-subtitle">Tidak menerima OTP atau OTP tidak bekerja ? <br> <span
                                        id="time_data" class="text-primary"></span></p>
                                <form action="{{ route('whatsapp.login.resend') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="no_telp" value="{{ $user->no_telp }}">
                                    <input type="hidden" name="id_login" value="{{ Crypt::encrypt('1') }}">
                                    <button type="submit" id="btn-ulangi"
                                        class="btn facebook btn-lg btn-block border-success text-primary">
                                        Kirim Ulang
                                        OTP </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascriptInclude')
    <script>
        var timeFromData = "{{ $expired }}"
        // Mengatur waktu akhir perhitungan mundur
        var countDownDate = new Date(timeFromData).getTime();

        // Memperbarui hitungan mundur setiap 1 detik
        var x = setInterval(function() {

            // Untuk mendapatkan tanggal dan waktu hari ini
            var now = new Date().getTime();

            // Temukan jarak antara sekarang dan tanggal hitung mundur
            var distance = countDownDate - now;

            // Perhitungan waktu untuk hari, jam, menit dan detik
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Keluarkan hasil dalam elemen dengan id = "demo"

            // Jika hitungan mundur selesai, tulis beberapa teks 
            if (distance <= 0) {
                clearInterval(x);
                document.getElementById("time_data").innerHTML = "Silahkan tekan tombol Kirim Ulang OTP";
                document.getElementById("btn-ulangi").classList.add('border-success');
                document.getElementById("btn-ulangi").classList.add('text-success');
                document.getElementById("btn-ulangi").classList.remove('border-danger');
                document.getElementById("btn-ulangi").classList.remove('text-danger');
                document.getElementById("btn-ulangi").removeAttribute('disabled');
            } else {
                document.getElementById("time_data").innerHTML = "Ulangi Dalam " + minutes + " menit " + seconds +
                    " detik ";
                document.getElementById("btn-ulangi").classList.add('border-danger');
                document.getElementById("btn-ulangi").classList.add('text-danger');
                document.getElementById("btn-ulangi").classList.remove('border-success');
                document.getElementById("btn-ulangi").classList.remove('text-success');
                document.getElementById("btn-ulangi").setAttribute('disabled', true);
            }
        }, 10);

        $('.otp-input').on("input", function() {
            $(this).val(this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1'));
        });
        for (let pin of $('#otp-screen').children()) {
            pin.onkeyup = function() {
                if (pin.nextElementSibling) {
                    pin.nextElementSibling.focus();
                }
            }
        }
    </script>

@endsection
