@extends('admin.layouts.kasir')
{{-- Meta --}}
@section('meta-name', 'Informasi Sistem')
@section('meta-description', 'Data Informasi Sistem')
@section('meta-keyword', 'Informasi Sistem')
{{-- End Meta --}}
@section('title', 'Bantuan dan Informasi Sistem')
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
                        <h4></h4>
                    </div>
                    <div class="card-body">
                        <div class="row gutters">
                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
                                <div class="row gutters">
                                    <img src="{{ asset('assets/enduser/img/logo-small.png') }}"
                                        class="img-fluid img-preview"  alt="Image">
                                </div>
                            </div>
                            <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12">
                                <h5>Tentang Aplikasi</h5>
                                <p class="mt-4" style="text-align:justify; text-indent: 0.5in;">Sistem Pengelolaan
                                    Sampah
                                    Terpadu yang kemudian disebut dengan SEMESTA KITA adalah sebuah
                                    sistem informasi manajemen
                                    yang membantu program bank sampah untuk dapat melakukan kegiatan administrasi
                                    pengelolaan sampah menjadi lebih efektif
                                    dan efisien. SEMESTA KITA dapat melakukan manajemen data anggota, pembukuan, data
                                    keluar-masuk, manajemen data sampah,
                                    promosi produk, serta manajemen iuran, sehingga pengelola serta anggota bank sampah
                                    dapat melakukan aktivitas manajemen
                                    dengan lebih mudah.</p>

                                <p class="mt-2" style="text-align:justify; text-indent: 0.5in;">Tujuan dari
                                    pengembangan
                                    SEMESTA KITA adalah untuk membangun sebuah sistem informasi
                                    manajemen dan administrasi guna
                                    mendukung pengolahan data pada Bank Sampah di TPST</p>

                                <p class="mt-2" style="text-align:justify; text-indent: 0.5in;">Dari rancangan
                                    sistem yang
                                    dibuat, sistem terdiri dari dua pengguna yaitu admin dari sisi
                                    pengelola TPST dan juga end
                                    user dari sisi masyarakat yang terdaftar sebagai anggota atau nasabah. SEMESTA KITA
                                    dapat melakukan beberapa fungsi
                                    dasar manajemen data seperti menambah data pengguna, mengubah data pengguna, melihat
                                    data pengguna, menghapus data
                                    pengguna, menambah data tabungan sampah, mengubah data tabungan sampah, melihat data
                                    tabungan sampah, menghapus data
                                    tabungan sampah, transaksi (keluar-masuk saldo), melihat data saldo tabungan, membuat
                                    laporan harian dan bulanan, update
                                    informasi terkait dengan bank sampah, serta informasi harga pupuk dan sampah. Untuk
                                    keamanan, sistem informasi ini
                                    dilengkapi dengan autentifikasi pengguna berupa login yang terdiri dari username dan
                                    password.</p>
                                <h5 class="mt-4">Informasi Versi Aplikasi</h5>
                                <div class="accordion mt-4" id="faqAccordion">
                                    @if ($all->count() > 0)

                                        @foreach ($all as $k => $c)
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="heading-{{ $k }}">
                                                    <button class="accordion-button {{ $k == 0 ? '' : 'collapsed' }}"
                                                        type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#collapse-{{ $k }}"
                                                        aria-expanded="{{ $k == 0 ? 'true' : 'false' }}"
                                                        aria-controls="collapse-{{ $k }}">
                                                        {{ $c->kode_versi }}-{{ $c->nama_versi }} Dilaunching Pada
                                                        Tanggal
                                                        {{ \Carbon\Carbon::parse($c->tanggal_rilis)->format('d F Y') }}
                                                        {{ $k == 0 ? '(Versi Terbaru)' : '' }}
                                                    </button>
                                                </h2>
                                                <div id="collapse-{{ $k }}"
                                                    class="accordion-collapse collapse {{ $k == 0 ? 'show' : '' }}"
                                                    aria-labelledby="heading-{{ $k }}"
                                                    data-bs-parent="#faqAccordion">
                                                    <div class="accordion-body">
                                                        {!! $c->konten !!}
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <p>Belum Ada Informasi Versi Aplikasi</p>
                                    @endif
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
