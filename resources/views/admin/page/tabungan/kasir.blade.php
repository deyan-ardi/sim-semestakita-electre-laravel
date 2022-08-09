@extends('admin.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Kasir Penarikan Tabungan')
@section('meta-description', 'Data Kasir Penarikan Tabungan')
@section('meta-keyword', 'Kasir Penarikan Tabungan')
{{-- End Meta --}}
@section('title', 'Kasir Penarikan Tabungan')
@section('footer')
    <script>
        $(document).ready(function() {
            $('#user').selectize({
                sortField: 'text'
            });
        });
    </script>
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
                            <h4>Penarikan Tabungan</h4>
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
                                        <div class="form-section-header light-bg">Cari Nasabah</div>
                                    </div>
                                    <div class="col-12">
                                        <!-- Field wrapper start -->
                                        <form action="{{ route('tabungan.tambah') }}" method="POST">
                                            @csrf
                                            <div class="field-wrapper">
                                                <select name="user" required id="user"
                                                    {{ $user->count() <= 0 ? 'disabled' : '' }} class="form-control">
                                                    @if ($user->count() <= 0)
                                                        <option value="">-- Belum Ada Data Nasabah --
                                                        </option>
                                                    @else
                                                        <option value="">-- Pilih Nasabah --</option>
                                                        @foreach ($user as $u)
                                                            @if ($u->role == 4)
                                                                <option value="{{ $u->id }}">{{ $u->no_member }} --
                                                                    {{ $u->name }}
                                                                    --
                                                                    Nasabah
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <div class="field-placeholder">Nama Nasabah <span
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
                    <h5 class="modal-title" id="modalHelpLabel">Panduan Pemakaian Fitur Kasir Tarik Tabungan Nasabah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-justify">
                    <p> Untuk memulai menggunakan fitur kasir tarik tabungan nasabah, mohon memperhatikan beberapa hal
                        berikut
                        ini : </p>
                    <ol>
                        <li>
                            <p>Nasabah adalah anggota/member TPST yang ikut dalam kegiatan transaksi (Menabung Sampah,
                                Menarik
                                Tabungan) yang ada di Bank Sampah</p>
                        </li>
                        <li>
                            <p>Nasabah juga merupakan pelanggan TPST, hanya saja seorang Nasabah memiliki keunggulan yakni
                                bisa
                                menabung dan menarik tabungan</p>
                        </li>
                        <li>
                            <p>Nasabah juga dapat membayar tagihan iuran jika seorang nasabah ikut dalam layanan
                                penjemputan sampah harian di TPST</p>
                        </li>
                        <li>
                            <p>Total saldo tabungan nasabah yang dipilih didapatkan dari hasil penyetoran sampah nasabah
                                tersebut
                            </p>
                        </li>
                        <li>
                            <p>Untuk menambahkan penarikan tabungan baru, silahkan pilih nasabah yang ingin melakukan
                                penarikan tabungan, lalu pilih tambahkan, maka akan diarahkan ke menu kasir</p>
                        </li>
                        <li>
                            <p>Silahkan masukkan jumlah penarikan tabungan, jumlah penarikan tidak dapat lebih dari total
                                saldo tabungan</p>
                        </li>
                        <li>
                            <p>Jika sudah benar silahkan pilih tombol simpan, anda dapat mencetak/mendownload invoice
                                setelah transaksi sukses</p>
                        </li>
                        <li>
                            <p>Total saldo tabungan nasabah yang dipilih akan berkurang setelah transaksi sukses</p>
                        </li>
                    </ol>
                    <p>Demikian panduan untuk fitur ini, selamat menggunakan fitur kasir tarik tabungan nasabah :)</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal end -->
@endsection
