@extends('enduser.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Tentang Aplikasi')
@section('meta-description', 'Data Tentang Aplikasi')
@section('meta-keyword', 'Tentang Aplikasi')
{{-- End Meta --}}
@section('title', 'Tentang Aplikasi')
@section('content')
    <div class="content container-fluid about-section">
        <div class="row">
            <div class="col-12 m-b-100">

                {{-- Section Tentang Aplikasi --}}
                <div class="section-header">
                    <h3 class="section-title">Tentang Aplikasi</h3>
                    <div class="line"></div>
                </div>

                <div class="card about-app-card">
                    <div class="card-body">
                        <img src="{{ asset('assets/enduser/img/logo-small.png') }}" alt="Logo SEMESTA-KITA"
                            class="about-img">
                        <div class="about-description text-justify">
                            <p>Sistem Pengelolaan Sampah Terpadu yang kemudian disebut dengan SEMESTA KITA adalah sebuah
                                sistem informasi manajemen yang membantu program bank sampah untuk dapat melakukan kegiatan
                                administrasi pengelolaan sampah menjadi lebih efektif dan efisien. SEMESTA KITA dapat
                                melakukan manajemen data anggota, pembukuan, data keluar-masuk,
                                manajemen data sampah, promosi produk, serta manajemen iuran, sehingga pengelola serta
                                anggota bank sampah dapat melakukan aktivitas manajemen dengan lebih mudah.</p>
                            <p>Tujuan dari pengembangan SEMESTA KITA adalah untuk membangun sebuah sistem informasi
                                manajemen dan administrasi guna mendukung pengolahan data pada Bank Sampah di TPST</p>
                            <p>Dari rancangan sistem yang dibuat, sistem terdiri dari dua pengguna yaitu admin dari sisi
                                pengelola TPST dan juga end user dari sisi masyarakat yang terdaftar sebagai anggota atau
                                nasabah. SEMESTA KITA dapat melakukan beberapa fungsi dasar manajemen data seperti menambah
                                data pengguna, mengubah data pengguna, melihat data pengguna, menghapus data pengguna,
                                menambah data tabungan sampah, mengubah data tabungan sampah, melihat data tabungan sampah,
                                menghapus data tabungan sampah, transaksi (keluar-masuk saldo), melihat data saldo tabungan,
                                membuat laporan harian dan bulanan, update informasi terkait dengan bank sampah, serta
                                informasi harga pupuk dan sampah. Untuk keamanan, sistem informasi ini dilengkapi dengan
                                autentifikasi pengguna berupa login yang terdiri dari username dan password.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Section Fitur Aplikasi --}}
                <div class="section-header">
                    <h3 class="section-title">Fitur Aplikasi</h3>
                    <div class="line"></div>
                </div>

                <div class="fitur-app-card">
                    <div class="row">
                        <div class="col-xl-4 col-sm-6">
                            <div class="card">
                                <div class="card-body text-center">
                                    <div class="fitur-icon pd">
                                        <i class="fas fa-trash-restore"></i>
                                    </div>
                                    <h5>Penyetoran Sampah</h5>
                                    <p>Fitur penyetoran sampah berfungsi untuk memberikan informasi riwayat penyetoran
                                        sampah yang dilakukan pelanggan kepada TPST.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-sm-6">
                            <div class="card">
                                <div class="card-body text-center">
                                    <div class="fitur-icon pd">
                                        <i class="fas fa-file-invoice-dollar"></i>
                                    </div>
                                    <h5>Iuran Sampah</h5>
                                    <p>Iuran sampah merupakan fitur yang memuat informasi informasi iuran sampah yang harus
                                        dibayarkan oleh anggota atau nasabah TPST setiap bulannya.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-sm-6">
                            <div class="card">
                                <div class="card-body text-center">
                                    <div class="fitur-icon">
                                        <i class="fas fa-wallet"></i>
                                    </div>
                                    <h5>Tabungan Sampah</h5>
                                    <p>Tabungan sampah adalah fitur penabungan sampah oleh nasabah TPST. Pada fitur ini akan
                                        ditampilkan informasi terkait saldo tabungan hingga riwayat transaksi tabungan. </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-sm-6">
                            <div class="card">
                                <div class="card-body text-center">
                                    <div class="fitur-icon">
                                        <i class="fas fa-chart-pie"></i>
                                    </div>
                                    <h5>Statistik Sampah</h5>
                                    <p>Statistik sampah merupakan fitur yang berfungsi untuk memberikan informasi statistik
                                        harga sampah menurut jenisnya masing-masing.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-sm-6">
                            <div class="card">
                                <div class="card-body text-center">
                                    <div class="fitur-icon">
                                        <i class="fas fa-newspaper"></i>
                                    </div>
                                    <h5>Artikel</h5>
                                    <p>Artikel merupakan fitur yang berisikan informasi atau berita bermanfaat terkait
                                        kegiatan yang berkaitan dengan TPST sehingga pengguna dapat lebih teredukasi.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-sm-6">
                            <div class="card">
                                <div class="card-body text-center">
                                    <div class="fitur-icon">
                                        <i class="fas fa-seedling"></i>
                                    </div>
                                    <h5>Bibit dan Hasil Produksi</h5>
                                    <p>Bibit dan hasil produksi merupakan fitur yang memuat informasi mengenai bibit dan
                                        hasil produksi TPST.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section Tim Pengembang --}}
                <div class="section-header">
                    <h3 class="section-title">Tim Pengembang</h3>
                    <div class="line"></div>
                </div>

                <div class="about-team">
                    <div class="row">
                        <div class="col-md-4 col-6 col-lg-3">
                            <div class="card">
                                <img class="team-img"
                                    src="{{ asset('assets/enduser/img/team/ardwi-pradnyana.jpg') }}" alt="">
                                <div class="card-body">
                                    <div class="team-id">
                                        <h5>Ardwi Pradnyana</h5>
                                        <span>Dosen Sistem Informasi UNDIKSHA</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-6 col-lg-3">
                            <div class="card">
                                <img class="team-img" src="{{ asset('assets/enduser/img/team/deyan.jpg') }}" alt="">
                                <div class="card-body">
                                    <div class="team-id">
                                        <h5>Gede Riyan </h5>
                                        <span>Mahasiswa Sistem Informasi UNDIKSHA</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-6 col-lg-3">
                            <div class="card">
                                <img class="team-img" src="{{ asset('assets/enduser/img/team/triarta.jpg') }}"
                                    alt="">
                                <div class="card-body">
                                    <div class="team-id">
                                        <h5>Nyoman Triarta </h5>
                                        <span>Mahasiswa Sistem Informasi UNDIKSHA</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-6 col-lg-3">
                            <div class="card">
                                <img class="team-img" src="{{ asset('assets/enduser/img/team/mang-pram.jpg') }}"
                                    alt="">
                                <div class="card-body">
                                    <div class="team-id">
                                        <h5>Pramayasa </h5>
                                        <span>Mahasiswa Sistem Informasi UNDIKSHA</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-6 col-lg-3">
                            <div class="card">
                                <img class="team-img" src="{{ asset('assets/enduser/img/team/arpin.jpg') }}" alt="">
                                <div class="card-body">
                                    <div class="team-id">
                                        <h5>Anggie Arpin </h5>
                                        <span>Mahasiswa Sistem Informasi UNDIKSHA</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-6 col-lg-3">
                            <div class="card">
                                <img class="team-img" src="{{ asset('assets/enduser/img/team/dhanu.png') }}" alt="">
                                <div class="card-body">
                                    <div class="team-id">
                                        <h5>Dhanu Driya </h5>
                                        <span>Mahasiswa Sistem Informasi UNDIKSHA</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-6 col-lg-3">
                            <div class="card">
                                <img class="team-img" src="{{ asset('assets/enduser/img/team/yudi.jpg') }}" alt="">
                                <div class="card-body">
                                    <div class="team-id">
                                        <h5>Yudi Utama</h5>
                                        <span>Mahasiswa Sistem Informasi UNDIKSHA</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-6 col-lg-3">
                            <div class="card">
                                <img class="team-img" src="{{ asset('assets/enduser/img/team/arya-budi.jpg') }}"
                                    alt="">
                                <div class="card-body">
                                    <div class="team-id">
                                        <h5>Arya Budi</h5>
                                        <span>Mahasiswa Sistem Informasi UNDIKSHA</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
