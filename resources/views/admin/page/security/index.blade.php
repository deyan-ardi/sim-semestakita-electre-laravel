@extends('admin.layouts.kasir')
{{-- Meta --}}
@section('meta-name', 'Ganti Keamanan Akun')
@section('meta-description', 'Data Ganti Keamanan Akun')
@section('meta-keyword', 'Ganti Keamanan Akun')
{{-- End Meta --}}
@section('title', 'Ganti Keamanan Akun')
@section('content')

    <!-- Content wrapper start -->
    <div class="content-wrapper">

        <!-- Row start -->
        <div class="row gutters">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('admin') }}" class="btn btn-danger mb-3"><i
                                class="icon-arrow-left"></i>Kembali</a>
                    </div>
                </div>
                <!-- Card start -->
                <div class="card">
                    <div class="card-header-lg">
                        <h4>Informasi Keamanan Akun</h4>
                    </div>
                    <div class="card-body">
                        <div class="text-danger mb-3 d-flex justify-content-end">* Wajib Diisi</div>
                        <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                @if (!empty(Auth::user()->re_email))
                                    <div class="alert alert-success">Permintaan Pergantian Email, Kami Mengirimkan Kode
                                        Aktivasi
                                        Ke Alamat Email
                                        <u>{{ Auth::user()->re_email }}</u>
                                    </div>
                                @endif
                                <br>
                                <form action="{{ route('ganti.keamanan.aksi', [Auth::user()->id]) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('patch')
                                    <div class="row gutters">
                                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                            <!-- Field wrapper start -->
                                            <div class="field-wrapper">
                                                <input type="email" name="email" required
                                                    class="form-control @error('email') is-invalid @enderror"
                                                    placeholder="Winter"
                                                    value="{{ old('email') ?? Auth::user()->email }}">
                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                                <div class="field-placeholder">Email Akun <span
                                                        class="text-danger">*</span>
                                                </div>
                                            </div>
                                            <!-- Field wrapper end -->
                                        </div>
                                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                            <!-- Field wrapper start -->
                                            <div class="field-wrapper">
                                                <input type="password" minlength="8" name="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    autocomplete="new-password" placeholder="********">
                                                <small>Minimal 8 karakter, karakter spesial, angka, huruf
                                                    diperbolehkan</small>
                                                @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                                <div class="field-placeholder">Ganti Kata Sandi (Jika Ingin Diganti)</div>
                                            </div>
                                            <!-- Field wrapper end -->
                                        </div>

                                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                            <!-- Field wrapper start -->
                                            <div class="field-wrapper">
                                                <input type="password" minlength="8" name="re-password"
                                                    placeholder="********"
                                                    class="form-control @error('re-password') is-invalid @enderror"
                                                    autocomplete="new-password">
                                                <small>Minimal 8 karakter, karakter spesial, angka, huruf
                                                    diperbolehkan</small>
                                                @error('re-password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                                <div class="field-placeholder">Ulangi Kata Sandi Baru (Jika Ingin Diganti)
                                                </div>
                                            </div>
                                            <!-- Field wrapper end -->
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                        <button type="submit" class="btn btn-primary mb-3">Simpan Profil</button>
                                    </div>
                                </form>
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
