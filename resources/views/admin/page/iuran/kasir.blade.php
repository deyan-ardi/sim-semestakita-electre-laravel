@extends('admin.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Kasir Pembayaran Iuran Rutin')
@section('meta-description', 'Data Kasir Pembayaran Iuran Rutin')
@section('meta-keyword', 'Kasir Pembayaran Iuran Rutin')
{{-- End Meta --}}
@section('title', 'Kasir Pembayaran Iuran Rutin')
@section('footer')
    <script>
        $(document).ready(function() {
            window.history.pushState(null, "", window.location.href);
            window.onpopstate = function() {
                window.history.pushState(null, "", window.location.href);
            };
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#user').selectize({
                sortField: 'text'
            });
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
                            <h4>Pembayaran Iuran Rutin</h4>
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
                                        <div class="form-section-header light-bg">Cari Nasabah Iuran</div>
                                    </div>
                                    <div class="col-12">
                                        <!-- Field wrapper start -->
                                        <form action="{{ route('iuran.kasir.tambah') }}" method="POST">
                                            @csrf
                                            <div class="field-wrapper">
                                                <select name="user" id="user" {{ $user->count() <= 0 ? 'disabled' : '' }}
                                                    class="form-control">
                                                    @if ($user->count() <= 0)
                                                        <option value="">-- Data Nasabah Tidak Ditemukan --</option>
                                                    @else
                                                        <option value="">-- Pilih Nasabah Iuran --</option>
                                                        @foreach ($user as $u)
                                                            @if ($u->role == 4)
                                                                <option value="{{ $u->id }}"
                                                                    {{ old('user') == $u->id ? 'selected' : '' }}>
                                                                    {{ $u->no_member }} --
                                                                    {{ $u->name }} --
                                                                    Nasabah
                                                                </option>
                                                            @else
                                                                <option value="{{ $u->id }}"
                                                                    {{ old('user') == $u->id ? 'selected' : '' }}>
                                                                    {{ $u->no_member }} --
                                                                    {{ $u->name }} --
                                                                    Pelanggan
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <div class="field-placeholder">Nama Nasabah Iuran <span
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
                    <h5 class="modal-title" id="modalHelpLabel">Panduan Pemakaian Fitur Kasir Pembayaran Iuran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-justify">
                    <p> Untuk memulai menggunakan fitur kasir pembayaran iuran, mohon memperhatikan beberapa hal berikut
                        ini : </p>
                    <ol>
                        <li>
                            <p>Nasabah iuran adalah anggota/member TPST baik sebagai nasabah/pelanggan yang status
                                pembayaran iurannya aktif</p>
                        </li>
                        <li>
                            <p>Status keaktfian pembayaran iuran dapat disetel di bagian master data nasabah/pelanggan yang
                                dapat diakses oleh hak akses pengelola</p>
                        </li>
                        <li>
                            <p>Tagihan iuran akan digenerate berdasarkan tanggal generate yang telah disetel pada bagian
                                master data iuran rutin</p>
                        </li>
                        <li>
                            <p>Data nasabah/pelanggan baru yang ditambahkan setelah tagihan bulan ini digenerate, maka
                                tagihan untuk
                                nasabah baru tersebut akan digenerate di bulan depan</p>
                        </li>
                        <li>
                            <p>Tagihan iuran yang dapat dipilih dan dibayar adalah tagihan iuran yang bersifat Unpaid dan
                                Overdue</p>
                        </li>
                        <li>
                            <p>Untuk tagihan yang bersifat Overdue, tidak dikenakan denda</p>
                        </li>
                        <li>
                            <p>Untuk melakukan pembayaran iuran, silahkan pilih nasabah iuran yang ingin membayar, lalu
                                tekan tombol Tambahkan, maka anda akan diarahkan ke menu kasir</p>
                        </li>
                        <li>
                            <p>Pada menu kasir, silahkan pilih tagihan iuran dari nasabah iuran yang belum terbayar, lalu
                                tambahkan ke checkout</p>
                        </li>
                        <li>
                            <p>Jika tagihan iuran yang ingin dibayar lebih dari 1, silahkan ulangi langkah ke-9</p>
                        </li>
                        <li>
                            <p>Jika sudah sesuai, masukkan total uang yang dibayar, lalu pilih simpan</p>
                        </li>
                        <li>
                            <p>Anda dapat mencetak/mendownload invoice setelah menyimpan data</p>
                        </li>
                        <li>
                            <p>Data tagihan iuran akan otomatis berubah menjadi Paid ketika sukses dibayar</p>
                        </li>
                        <li>
                            <p>Dana yang dibayarkan oleh nasabah iuran akan otomatis masuk ke kas TPST</p>
                        </li>
                    </ol>
                    <p>Demikian panduan untuk fitur ini, selamat menggunakan fitur kasir pembayaran iuran :)</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal end -->
@endsection
