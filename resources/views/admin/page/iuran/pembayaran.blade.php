@extends('admin.layouts.kasir')
{{-- Meta --}}
@section('meta-name', 'Kasir Pembayaran Iuran Rutin, Transaksi')
@section('meta-description', 'Data Kasir Pembayaran Iuran Rutin, Transaksi')
@section('meta-keyword', 'Kasir Pembayaran Iuran Rutin, Transaksi')
{{-- End Meta --}}
@section('title', 'Kasir Pembayaran Iuran Rutin - Transaksi')
@section('header')
    <style>
        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
@endsection
@section('footer')
    <script>
        $(document).ready(function() {
            $('#pembayaran').selectize({
                sortField: 'text'
            });
        });
    </script>
    <script>
        $('#form-ajax-2').on('submit', function(e) {
            e.preventDefault();
            let formDetail = $('#form-ajax-2').serializeArray();
            let tagihan = formDetail[1].value;
            $.ajax({
                url: "{{ route('iuran.kasir.tambah.keranjang') }}",
                method: 'POST',
                dataType: 'json',
                data: {
                    tagihan: tagihan,
                    _token: "{{ csrf_token() }}",
                },
                success: function(result) {
                    if (result.success) {
                        $('#total').val(null);
                        liveJumlah($('#input_jumlah').val());
                        liveTable();
                    } else {
                        Swal.fire({
                            position: "top-end",
                            icon: "error",
                            text: "Opss.." + result.message,
                            showConfirmButton: false
                        });
                    }
                },
                error: function(result) {
                    alert(result.responseText);
                }
            })
        });
        const liveJumlah = (e) => {
            var awal = $('#total_akhir_hidden').val();
            var sisa = parseInt(e) - parseInt(awal);
            if (sisa < 0 || isNaN(sisa) || e == 0 || $('.row-form').attr("data-status") == "kosong") {
                $('.alert').show();
                $('#btn_submit').attr('disabled', true);
            } else {
                $('.alert').hide();
                $('#btn_submit').attr('disabled', false);
            }

            $('#sisa_saldo').empty();
            $('#jumlah_bayar').val(e);

            if (isNaN(sisa) || sisa < 0 || e == 0 || $('.row-form').attr("data-status") == "kosong") {
                $('#sisa_saldo').text("Rp. ~");
            } else {
                $('#sisa_saldo').text(formatRupiah(sisa) + ",00");
            }

        }
        $('#input_jumlah').bind('keyup paste', function() {
            this.value = +this.value.replace(/[^0-9]/g, '');
        });
        $('#input_jumlah').val('0');
        const removeListTable = (v) => {
            $.ajax({
                url: "{{ route('iuran.kasir.hapus.sampah') }}",
                method: 'DELETE',
                dataType: 'json',
                data: {
                    id: v,
                    _token: "{{ csrf_token() }}",
                },
                success: function(result) {
                    if (result.success) {
                        liveJumlah($('#input_jumlah').val());
                        liveTable();
                    }
                },
                error: function(result) {
                    alert(result.responseText);
                }
            })
        }
        const liveTable = () => {
            $.ajax({
                url: "{{ route('iuran.kasir.table.list') }}",
                method: 'POST',
                dataType: 'json',
                data: {
                    _token: "{{ csrf_token() }}",
                },
                success: function(result) {
                    if (result.success) {
                        $('#body_table').empty();
                        $('#total_akhir').empty();
                        $('#denda').empty();
                        $('#sub_total_hitung').empty();
                        var resultValue = JSON.parse(JSON.stringify(result.data));
                        var total = 0;
                        var denda = 0;
                        var sub_total = 0;
                        var denda_status = "{{ $denda->status }}";
                        if (resultValue.length <= 0 || resultValue == null) {
                            $('#btn_submit').attr('disabled', true);
                            var html = `
                            <tr class="row-form" data-status="kosong">
                                <td colspan="5" class="text-center">Kosong</td>
                            </tr>
                            `
                            $("#body_table").append(html);
                            $('#denda').text(formatRupiah(0));
                            $('#denda_hidden').val(0);
                            $('#total_akhir').text(formatRupiah(0));
                            $('#total_akhir_hidden').val(0);
                            $('#sub_total_hitung').text(formatRupiah(0));
                        } else {
                            $('#btn_submit').attr('disabled', false);
                            $.each(resultValue, function(key, value) {
                                const months = ["Januari", "Februari", "Maret", "April", "Mei",
                                    "Juni", "Juli", "Agustus", "September", "Oktober",
                                    "November", "Desember"
                                ];
                                let current_datetime = new Date(value.tanggal);
                                let formatted_date = months[current_datetime.getMonth()] + " " +
                                    current_datetime.getFullYear()
                                if (value.status == 'OVERDUE' && denda_status ==
                                    'active') {
                                    denda = parseInt(value.sub_total_denda) +
                                        parseInt(denda);
                                    total = parseInt(total) + parseInt(value.total_tagihan);
                                    // total = parseInt(total) + parseInt(denda);
                                } else {
                                    total = parseInt(total) + parseInt(value.sub_total);
                                }
                                var html = `
                                <tr class="row-form" data-status="ada">
                                    <td>
                                        <!-- Field wrapper start -->
                                        <div class="field-wrapper m-0">
                                            <div class="input-group">
                                                 <input type="text" disabled class="form-control"
                                                                            value="` + value.no_tagihan + `">
                                            </div>
                                        </div>
                                        <!-- Field wrapper end -->
                                    </td>
                                    <td>
                                        <!-- Field wrapper start -->
                                        <div class="field-wrapper m-0">
                                            <div class="input-group">
                                                <input type="text" disabled class="form-control"
                                                    value="` + formatted_date + `">
                                            </div>
                                        </div>
                                        <!-- Field wrapper end -->
                                    </td>

                                    <td>
                                        <!-- Field wrapper start -->
                                        <div class="field-wrapper m-0">
                                            <div class="input-group">
                                                <input type="text" class="form-control" disabled
                                                    value="` + formatRupiah(value.sub_total) + `">
                                            </div>
                                        </div>
                                        <!-- Field wrapper end -->
                                    </td>
                                    <td>
                                        <!-- Field wrapper start -->
                                        <div class="field-wrapper m-0">
                                            <div class="input-group">
                                                <input type="text" class="form-control" disabled
                                                    value="` + value.status + `">
                                            </div>
                                        </div>
                                        <!-- Field wrapper end -->
                                    </td>
                                    <td>
                                        <div class="table-actions">
                                            <button type="button" class="btn btn-danger" onclick="removeListTable('` +
                                    value.id + `')">
                                                <i class="icon-trash-2"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                `;
                                $("#body_table").append(html);
                            });
                            sub_total = parseInt(total) - parseInt(denda);
                            $('#sub_total_hitung').text(formatRupiah(sub_total));
                            $('#denda').text(formatRupiah(denda));
                            $('#denda_hidden').val(denda);
                            $('#total_akhir').text(formatRupiah(total));
                            $('#total_akhir_hidden').val(total);
                        }
                        liveJumlah($('#input_jumlah').val());
                    }
                },
                error: function(result) {
                    alert(result.responseText);
                }
            })
        }

        liveTable();
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
                        <a href="{{ route('iuran.kasir') }}" class="btn btn-danger mb-3"><i
                                class="icon-arrow-left"></i>Kembali</a>
                    </div>
                </div>
                <!-- Card end -->

                <!-- Card start -->
                <div class="card">
                    <div class="card-header-lg">
                        <h4>Tambah Pembayaran Tagihan Iuran</h4>
                    </div>
                    <div class="card-body">
                        <div class="card-body">

                            <!-- Row start -->
                            <div class="row justify-content-between">

                                <div class="col-xl-5 col-lg-5 col-md-5 col-sm-5 col-12">

                                    <!-- Row start -->
                                    <div class="row gutters">

                                        <div class="col-12">
                                            <div class="form-section-header light-bg">Data Nasabah Iuran</div>
                                        </div>
                                        <div class="col-12">
                                            <!-- Field wrapper start -->
                                            <div class="field-wrapper">
                                                <input class="form-control" type="text" disabled
                                                    value="{{ $user->no_member }} -- {{ $user->name }} -- {{ $user->role == 4 ? 'Nasabah' : 'Pelanggan' }}">
                                                <div class="field-placeholder">Nama Nasabah Iuran <span
                                                        class="text-danger">*</span></div>
                                            </div>
                                            <!-- Field wrapper end -->
                                        </div>
                                        <div class="col-12 mt-2">
                                            <div class="form-section-header light-bg">Data Tunggakan</div>
                                        </div>
                                        <div class="col-12">
                                            <!-- Field wrapper start -->
                                            <div class="field-wrapper">
                                                {{-- {{ dd($tagihan) }} --}}
                                                <form id="form-ajax-2">
                                                    @csrf
                                                    <div class="input-group">
                                                        <select required name="pembayaran" id="pembayaran"
                                                            class="form-control">
                                                            @if ($tagihan->count() <= 0)
                                                                <option value=""> -- Tidak Ada
                                                                    Tunggakan
                                                                    Tagihan -- </option>
                                                            @else
                                                                <option value="">-- Pilih Tunggakan Yang Ingin Dibayar
                                                                    --
                                                                </option>
                                                                @foreach ($tagihan as $t)
                                                                    <option value="{{ $t->id }}">
                                                                        {{ $t->no_tagihan }} --
                                                                        {{ $t->user->pembayaran_rutin->nama_pembayaran . ' Bulan ' . \Carbon\Carbon::parse($t->tanggal)->format('F Y') }}
                                                                        --
                                                                        @currency($t->sub_total)</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                    <div class="field-placeholder">Pilih Tunggakan <span
                                                            class="text-danger">*</span></div>
                                                    <button type="submit" id="btn-tambah"
                                                        class="btn btn-primary mt-3">Tambah
                                                        Keranjang</button>
                                                </form>
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
                                                                <th colspan="7" class="pt-3 pb-3">Checkout</th>

                                                            </tr>
                                                            <div class="mb-3 alert alert-danger" style="display: none">
                                                                Uang Yang Dibayarkan Kurang, Atau Nominal Bayar Belum
                                                                Diinputkan
                                                            </div>
                                                            <tr>
                                                                <th>No Tagihan</th>
                                                                <th>Bulan</th>
                                                                <th>Jumlah Tagihan </th>
                                                                <th>Status Tagihan</th>
                                                                <th>Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="body_table">



                                                        </tbody>
                                                        <tfoot>
                                                            @if ($denda->status == 'active')
                                                                <tr>
                                                                    <td colspan="3">
                                                                        <h5 class="mt-2">Sub Total Tagihan</h5>
                                                                    </td>
                                                                    <td colspan="4">
                                                                        <h5 class="mt-2" id="sub_total_hitung">Rp. 0</h5>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="3">
                                                                        <h5 class="mt-2">Sub Total Denda</h5>
                                                                    </td>
                                                                    <td colspan="4">
                                                                        <h5 class="mt-2" id="denda">Rp. 0</h5>
                                                                        <input type="hidden" id="denda_hidden"
                                                                            value="0">
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                            <tr>
                                                                <td colspan="3">
                                                                    <h5 class="mt-2">Total Wajib Bayar</h5>
                                                                </td>
                                                                <td colspan="4">
                                                                    <h5 class="mt-2" id="total_akhir">Rp. 0</h5>
                                                                    <input type="hidden" id="total_akhir_hidden"
                                                                        value="0">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="3">
                                                                    <h5 class="mt-2">Uang Dibayarkan<span
                                                                            class="text-danger">*</span></h5>
                                                                </td>
                                                                <td colspan="4">
                                                                    <input type="number" pattern="[1-9]{1}[0-9]{9}"
                                                                        min="0" autofocus
                                                                        onkeyup="liveJumlah(this.value)"
                                                                        onKeyPress="if(this.value.length==10) return false;"
                                                                        id="input_jumlah" value="0"
                                                                        class="form-control" required>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="3">
                                                                    <h5 class="mt-2">Uang Kembali</h5>
                                                                </td>
                                                                <td colspan="4">
                                                                    <h5 class="mt-2" id="sisa_saldo">Rp. 0</h5>
                                                                </td>
                                                            </tr>
                                                        </tfoot>
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
                                                        <form id="save-{{ $pembayaran['id'] }}"
                                                            action="{{ route('iuran.kasir.bayar.aksi') }}"
                                                            method="post">
                                                            {{-- Ada sweetalert --}}
                                                            @csrf
                                                            <a href="#" data-redirect="{{ route('iuran.kasir') }}"
                                                                class="btn btn-danger confirm-cancel">Batalkan</a>
                                                            <input type="hidden" value="0" id="jumlah_bayar"
                                                                name="bayar">
                                                            <button type="submit"
                                                                class="btn btn-primary ms-1 confirm-save"
                                                                data-formid="{{ $pembayaran['id'] }}"
                                                                id="btn_submit">Simpan</button>
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
