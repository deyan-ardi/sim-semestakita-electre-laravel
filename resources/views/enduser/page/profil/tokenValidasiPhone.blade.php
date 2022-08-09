@extends('enduser.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Pergantian Nomor WhatsApp')
@section('meta-description', 'Data Pergantian Nomor WhatsApp')
@section('meta-keyword', 'Pergantian Nomor WhatsApp')
{{-- End Meta --}}
@section('title', 'Pergantian Nomor WhatsApp')
@section('custom-script')
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
@section('content')
    <div class="content container-fluid">
        @include('enduser.widgets.profile_menu')
        <div class="row">
            <div class="col-12 m-b-100">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Validasi Pergantian Nomor WhatsApp</h5>
                    </div>
                    <div class="card-body row justify-content-center">
                        <p class="text-center">Permintaan pergantian nomor WhatsApp telah diproses. Silahkan masukkan kode
                            OTP yang telah dikirimkan melalui
                            nomor
                            WhatsApp Baru anda (<span class="text-primary">0{{ $phone }}</span>) untuk memvalidasi
                        </p>
                        <div class="col-lg-6">
                            <form method="POST" action="{{ route('enduser.validasi.token.aksi') }}">
                                @csrf
                                <div class="form-group">
                                    <div class="row justify-content-center" id="otp-screen">
                                        <input id="otp1" type="text" class="form-control col-2 ml-2 text-center otp-input"
                                            name="otp1" min="0" maxlength="1" required autofocus>

                                        <input id="otp2" type="text" class="form-control col-2 ml-2 text-center otp-input"
                                            name="otp2" min="0" maxlength="1" required>

                                        <input id="otp3" type="text" class="form-control col-2 ml-2 text-center otp-input"
                                            name="otp3" min="0" maxlength="1" required>

                                        <input id="otp4" type="text" class="form-control col-2 ml-2 text-center otp-input"
                                            name="otp4" min="0" maxlength="1" required>
                                    </div>
                                </div>
                                <button class="btn btn-block btn-primary" type="submit">Validasi Pergantian</button>
                                <div class="login-or">
                                    <span class="or-line"></span>
                                    <span class="span-or">atau</span>
                                </div>
                            </form>
                            <div class="mb-3">
                                <p class="text-center">Tidak menerima OTP atau OTP tidak bekerja ? <br> <span
                                        id="time_data" class="text-primary"></span></p>
                                <form action="{{ route('enduser.profil.aksi', [Auth::user()->id]) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="phone" value="{{ $phone }}">
                                    <input type="hidden" name="address" value="{{ Auth::user()->alamat }}">
                                    <input type="hidden" name="name" value="{{ Auth::user()->name }}">
                                    <button type="submit" id="btn-ulangi"
                                        class="btn facebook  btn-block border-success text-primary">
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
