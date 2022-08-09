@extends('admin.layouts.kasir')
{{-- Meta --}}
@section('meta-name', 'Kasir Penarikan Tabungan, Transaksi')
@section('meta-description', 'Data Kasir Penarikan Tabungan, Transaksi')
@section('meta-keyword', 'Kasir Penarikan Tabungan, Transaksi')
{{-- End Meta --}}
@section('title', 'Kasir Penarikan Tabungan - Transaksi')
@section('footer')
    <script>
        $('#input_jumlah').val('0');
        $('#submit_btn').attr('disabled', true);


        const liveJumlah = (e) => {
            var status_min_penarikan = "{{ $min_penarikan->status }}";
            if (status_min_penarikan == "active") {
                var sald_awal = "{{ $tabungan->saldo }}";
                var min_penarikan = "{{ $min_penarikan->value }}";
                var awal = parseInt(sald_awal) - parseInt(min_penarikan);
            } else {
                var awal = "{{ $tabungan->saldo }}";
            }
            var sisa = parseInt(awal) - parseInt(e);
            if (sisa < 0 || isNaN(sisa) || e == 0) {
                $('.alert').show();
                $('#submit_btn').attr('disabled', true);
            } else {
                $('.alert').hide();
                $('#submit_btn').attr('disabled', false);
            }

            $('#total_penarikan').empty();
            $('#sisa_saldo').empty();

            if (e == "") {
                $('#total_penarikan').text("Rp. ~");
            } else {
                $('#input_form').val(e);
                $('#total_penarikan').text(formatRupiah(e) + ",00");
            }

            if (isNaN(sisa) || sisa < 0 || e == 0) {
                $('#sisa_saldo').text("Rp. ~");
            } else {
                $('#sisa_saldo').text(formatRupiah(sisa) + ",00");
            }

        }

        $('#input_jumlah').bind('keyup paste', function() {
            this.value = +this.value.replace(/[^0-9]/g, '');
        });
    </script>
@endsection
@section('content')

    <!-- Content wrapper start -->
    <div class="content-wrapper">

        <!-- Row start -->
        <div class="row gutters">
            <div class="col-xl-12">
                <!-- Card start -->
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('tabungan') }}" class="btn btn-danger mb-3"><i
                                class="icon-arrow-left"></i>Kembali</a>
                    </div>
                </div>
                <!-- Card end -->

                <!-- Card start -->
                <div class="card">
                    <div class="card-header-lg">
                        <h4>Tambah Penarikan Tabungan</h4>
                    </div>
                    <div class="card-body">
                        <div class="card-body">

                            <!-- Row start -->
                            <div class="row justify-content-between">

                                <div class="col-xl-5 col-lg-5 col-md-5 col-sm-5 col-12">

                                    <!-- Row start -->
                                    <div class="row gutters">

                                        <div class="col-12">
                                            <div class="form-section-header light-bg">Data Pelanggan</div>
                                        </div>
                                        <div class="col-12">
                                            <!-- Field wrapper start -->
                                            <div class="field-wrapper">
                                                <input class="form-control" disabled type="text"
                                                    value="{{ $user->no_member . ' -- ' . $user->name }} -- {{ $user->role == 4 ? 'Nasabah' : '' }}">
                                                <div class="field-placeholder">Nama Pelanggan</div>
                                            </div>
                                            <!-- Field wrapper end -->
                                        </div>
                                        <div class="col-12">
                                            <!-- Field wrapper start -->
                                            <div class="field-wrapper">
                                                <div class="input-group">
                                                    <input type="text" disabled
                                                        value="{{ $penarikan['no_penarikan'] }}" class="form-control">
                                                </div>
                                                <div class="field-placeholder">Nomor Transaksi</div>
                                            </div>
                                            <!-- Field wrapper end -->
                                        </div>
                                        <div class="col-12">
                                            <!-- Field wrapper start -->
                                            <div class="field-wrapper">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" value="@currency($tabungan->saldo)"
                                                        disabled>
                                                </div>
                                                <div class="field-placeholder">Saldo</div>
                                            </div>
                                            <!-- Field wrapper end -->
                                        </div>
                                        <div class="col-12">
                                            <!-- Field wrapper start -->
                                            <div class="field-wrapper">
                                                <div class="input-group">
                                                    <input type="number" pattern="[1-9]{1}[0-9]{9}" min=" 0"
                                                        autofocus onkeyup="liveJumlah(this.value)"
                                                        onKeyPress="if(this.value.length==10) return false;"
                                                        id="input_jumlah" value="0" class="form-control">
                                                </div>
                                                <div class="field-placeholder">Jumlah Penarikan</div>
                                            </div>
                                            <!-- Field wrapper end -->
                                        </div>
                                    </div>
                                    <!-- Row end -->

                                </div>
                                <div class="col-xl-7 col-lg-7 col-md-7 col-sm-7 col-12">

                                    <!-- Row start -->
                                    <div class="row gutters">

                                        <!-- Row start -->
                                        <div class="row gutters">
                                            <div class="col-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th colspan="5" class="pt-3 pb-3">Checkout</th>
                                                            </tr>
                                                            <div class="mb-3 alert alert-danger" style="display: none">
                                                                Saldo Awal Tidak Mencukupi
                                                            </div>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <h5 class="mt-2">Saldo Awal</h5>
                                                                </td>
                                                                <td colspan="3">
                                                                    <h5 class="mt-2">@currency($tabungan->saldo)
                                                                    </h5>
                                                                </td>
                                                            </tr>
                                                            @if ($min_penarikan->status == 'active')
                                                                <tr>
                                                                    <td colspan="2">
                                                                        <h5 class="mt-2">Maksimal Saldo Bisa
                                                                            Ditarik</h5>
                                                                    </td>
                                                                    <td colspan="3">
                                                                        <h5 class="mt-2">
                                                                            @currency($tabungan->saldo - $min_penarikan->value < 0 ? 0 : $tabungan->saldo - $min_penarikan->value)
                                                                        </h5>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                            <tr>
                                                                <td colspan="2">
                                                                    <h5 class="mt-2">Total Penarikan</h5>
                                                                </td>
                                                                <td colspan="3">
                                                                    <h5 class="mt-2" id="total_penarikan">Rp. 0,00
                                                                    </h5>
                                                                </td>
                                                            </tr>

                                                            @if ($min_penarikan->status == 'active')
                                                                <tr>
                                                                    <td colspan="2">
                                                                        <h5 class="mt-2">Sisa Saldo Dapat Ditarik</h5>
                                                                    </td>
                                                                    <td colspan="3">
                                                                        <h5 class="mt-2" id="sisa_saldo">Rp. 0,00</h5>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="2">
                                                                        <h5 class="mt-2">Saldo Mengendap (Tidak Dapat
                                                                            Ditarik)</h5>
                                                                    </td>
                                                                    <td colspan="3">
                                                                        <h5 class="mt-2">@currency($min_penarikan->value)
                                                                        </h5>
                                                                    </td>
                                                                </tr>
                                                            @else
                                                                <tr>
                                                                    <td colspan="2">
                                                                        <h5 class="mt-2">Sisa Saldo</h5>
                                                                    </td>
                                                                    <td colspan="3">
                                                                        <h5 class="mt-2" id="sisa_saldo">Rp. 0,00</h5>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>
                                        </div>
                                        <!-- Row end -->

                                        <!-- Row start -->
                                        <div class="row gutters">

                                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                                <div class="form-actions-footer">
                                                    <div class="text-end">
                                                        <form id="save-{{ $penarikan['id'] }}"
                                                            action="{{ route('tabungan.aksi') }}" method="post">
                                                            @csrf
                                                            <a href="#" data-redirect="{{ route('tabungan') }}"
                                                                class="btn btn-danger confirm-cancel">Batalkan</a>
                                                            <input type="hidden" value="0" id="input_form"
                                                                name="tarik">
                                                            <button type="submit" id="submit_btn" disabled
                                                                class="btn btn-primary ms-1 confirm-save"
                                                                data-formid="{{ $penarikan['id'] }}">Simpan</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <!-- Row end -->

                                    </div>
                                    <!-- Row end -->

                                </div>

                            </div>
                            <!-- Row end -->

                        </div>
                    </div>
                </div>
                <!-- Card end -->
            </div>
        </div>
        <!-- Row end -->
    </div>
@endsection
