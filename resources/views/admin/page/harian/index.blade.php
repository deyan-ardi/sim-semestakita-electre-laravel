@extends('admin.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Kasir Rekapan Sampah Harian')
@section('meta-description', 'Data Kasir Rekapan Sampah Harian')
@section('meta-keyword', 'Kasir Rekapan Sampah Harian')
{{-- End Meta --}}
@section('title', 'Kasir Rekapan Sampah Harian')
@section('footer')
    <script>
        $(document).ready(function() {
            window.history.pushState(null, "", window.location.href);
            window.onpopstate = function() {
                window.history.pushState(null, "", window.location.href);
            };
        });
    </script>
@endsection
@section('content')

    <!-- Content wrapper start -->
    <div class="content-wrapper">

        <!-- Row start -->
        <div class="row gutters">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

                <!-- Card start -->
                <div class="card">
                    <div class="card-header-lg">
                        <div class="card-title">
                            <h4>Rekapan Sampah Harian</h4>
                        </div>
                        <div class="graph-day-selection" role="group">
                            <button type="button" class="btn active" data-bs-toggle="modal"
                                data-bs-target="#modalHelp"><i class="icon-help"></i> Bantuan</button>
                        </div>
                    </div>
                    <div class="card-body">

                        <!-- Row start -->
                        <div class="row justify-content-between">

                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

                                <!-- Row start -->
                                <div class="row gutters">

                                    <div class="col-12">
                                        <div class="form-section-header light-bg">Status Rekapan</div>
                                    </div>
                                    <div class="col-12">
                                        <!-- Field wrapper start -->
                                        <div class="text-danger mb-3 d-flex justify-content-end">* Wajib Diisi</div>
                                        <form action="{{ route('harian.tambah') }}" method="POST">
                                            @csrf
                                            <div class="field-wrapper">
                                                <select name="user" id="user" required class="form-control">
                                                    <option value="">-- Pilih Status --</option>
                                                    <option value="Keluar">Rekapan Sampah Keluar</option>
                                                    <option value="Masuk">Rekapan Sampah Masuk</option>
                                                </select>
                                                <div class="field-placeholder">Status Rekapan <span
                                                        class="text-danger">*</span>
                                                </div>
                                            </div>
                                            <div class="field-wrapper">
                                                <input name="date" id="date" value="{{ date('Y-m-d') }}" type="date" max="{{ date('Y-m-d') }}"
                                                    required class="form-control">
                                                <div class="field-placeholder">Tanggal Transaksi <span
                                                        class="text-danger">*</span>
                                                </div>
                                            </div>
                                            <div class="col-12 mb-3">
                                                {{-- <button type="submit" class="btn btn-primary">Tambahkan</button> --}}
                                                <button type="submit" class="btn btn-primary">Tambahkan</button>
                                            </div>
                                        </form>
                                        <!-- Field wrapper end -->
                                    </div>
                                </div>
                                <!-- Row end -->

                            </div>

                        </div>
                        <!-- Row end -->


                    </div>
                    <!-- Card end -->

                </div>
            </div>
            <!-- Row end -->

        </div>
        <!-- Content wrapper end -->
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalHelp" tabindex="-1" aria-labelledby="modalHelpLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title" id="modalHelpLabel">Panduan Pemakaian Fitur Kasir Rekap Sampah Harian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-justify">
                    <p> Untuk memulai menggunakan fitur kasir rekap sampah harian, mohon memperhatikan beberapa hal berikut
                        ini : </p>
                    <ol>
                        <li>
                            <p>Anda dapat memilih status rekapan yang diinputkan apakah rekapan sampah harian masuk atau
                                rekapan sampah harian keluar</p>
                        </li>
                        <li>
                            <p>Anda dapat memilih tanggal transaksi itu berlangsung (rekapan harian masuk atau rekapan
                                harian keluar), untuk tanggal dibatasi maksimal di
                                tanggal hari ini. Tidak bisa menginputkan tanggal yang akan datang</p>
                        </li>
                        <li>
                            <p>Setelah memilih tombol tambahkan, anda akan diarahkan ke halaman kasir. Silahkan masukkan
                                kategori sampah yang masuk ataupun keluar beserta total sampahnya lalu tekan tombol tambah
                                keranjang</p>
                        </li>
                        <li>
                            <p>Jika sampah yang keluar/masuk ada banyak item, anda dapat mengulangi tahap ke empat </p>
                        </li>
                        <li>
                            <p>Jika anda memilih fitur rekapan keluar, total jumlah sampah yang dimasukkan tidak dapat lebih
                                dari total stok sampah</p>
                        </li>
                        <li>
                            <p>Jika data sampah yang ada pada bagian checkout sudah benar, anda dapat menekan tombol simpan
                            </p>
                        </li>
                        <li>
                            <p>Anda dapat mencetak/mendownload invoice setelah anda menekan tombol simpan</p>
                        </li>
                        <li>
                            <p>Jika tipe rekapan adalah rekapan masuk, jumlah sampah yang masuk akan otomatis ditambahkan
                                pada total sampah TPST</p>
                        </li>
                        <li>
                            <p>Jika tipe rekapan adalah rekapan keluar, jumlah sampah yang keluar akan otomatis dikurangkan
                                dari total sampah di TPST</p>
                        </li>
                    </ol>
                    <p>Demikian panduan untuk fitur ini, selamat menggunakan fitur kasir rekapan sampah harian :)</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal end -->
@endsection
