@extends('admin.layouts.kasir')
{{-- Meta --}}
@section('meta-name', 'Ganti Profil Akun, Verifikasi OTP')
@section('meta-description', 'Data Ganti Profil Akun, Verifikasi OTP')
@section('meta-keyword', 'Ganti Profil Akun, Verifikasi OTP')
{{-- End Meta --}}
@section('title', 'Ganti Profil Akun - Verifikasi OTP')
@section('footer')
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

    <!-- Content wrapper start -->
    <div class="content-wrapper">

        <!-- Row start -->
        <div class="row gutters">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('ganti.profil') }}" class="btn btn-danger mb-3"><i
                                class="icon-arrow-left"></i>Kembali</a>
                    </div>
                </div>
                <!-- Card start -->
                <div class="card">
                    <div class="card-header-lg">
                        <h4>Validasi Pergantian Nomor WhatsApp</h4>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <p>Kode OTP Wajib Diisi</p>
                            </div>
                        @endif
                        <div class="row gutters">
                            <div class="col-12">
                                <p class="text-center">Permintaan pergantian nomor WhatsApp telah diproses. Silahkan
                                    masukkan kode
                                    OTP yang telah dikirimkan melalui
                                    nomor
                                    WhatsApp Baru Anda (<span class="text-primary">0{{ $phone }}</span>) untuk
                                    memvalidasi
                                </p>
                                <form method="POST" class="text-center"
                                    action="{{ route('ganti.profil.validasi.aksi') }}">
                                    @csrf
                                    <div class="row justify-content-center" id="otp-screen">
                                        <input id="otp1" type="text"
                                            class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-2 m-2 p-2  text-center otp-input"
                                            name="otp1" min="0" maxlength="1" required autofocus>
                                        <input id="otp2" type="text"
                                            class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-2 m-2 p-2  text-center otp-input"
                                            name="otp2" min="0" maxlength="1" required>
                                        <input id="otp3" type="text"
                                            class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-2 m-2 p-2 text-center otp-input"
                                            name="otp3" min="0" maxlength="1" required>
                                        <input id="otp4" type="text"
                                            class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-2 m-2 p-2 text-center otp-input"
                                            name="otp4" min="0" maxlength="1" required>
                                        <input type="hidden" name="no_telp" value="{{ $user->no_telp }}">
                                        <input type="hidden" name="id_login" value="{{ Crypt::encrypt('1') }}">
                                    </div>
                                    <button class="btn btn-block btn-primary mt-3" type="submit">Validasi
                                        Pergantian</button>
                                    <div class="mt-4">
                                        Atau
                                    </div>
                                </form>
                                <div class="mb-3">
                                    <p class="text-center mb-4">Tidak menerima OTP atau OTP tidak bekerja ? <br> <span
                                            id="time_data" class="text-success"></span></p>
                                    <form action="{{ route('ganti.profil.aksi', [Auth::user()->id]) }}"
                                        class="text-center" method="POST">
                                        @csrf
                                        @method('patch')
                                        <input type="hidden" name="phone" value="{{ $phone }}">
                                        <input type="hidden" name="address" value="{{ Auth::user()->alamat }}">
                                        <input type="hidden" name="name" value="{{ Auth::user()->name }}">
                                        <button type="submit" id="btn-ulangi"
                                            class="btn facebook btn-block border-success text-primary">
                                            Kirim Ulang
                                            OTP </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Card end -->

            </div>
        </div>
        <!-- Row end -->

    </div>
    <!-- Content wrapper end -->


@endsection
