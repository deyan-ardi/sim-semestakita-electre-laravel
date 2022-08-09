@extends('admin.layouts.kasir')
{{-- Meta --}}
@section('meta-name', 'Kasir Rekapan Sampah Harian')
@section('meta-description', 'Data Kasir Rekapan Sampah Harian')
@section('meta-keyword', 'Kasir Rekapan Sampah Harian, Transaksi')
{{-- End Meta --}}
@section('title', 'Kasir Rekapan Sampah Harian - Transaksi')
@section('footer')
    <script>
        $(document).ready(function() {
            $('#kategori').selectize({
                sortField: 'text'
            });
        });
    </script>
    <script>
        $('#form-ajax').on('submit', function(e) {
            e.preventDefault();
            let formDetail = $('#form-ajax').serializeArray();
            let kategori = formDetail[0].value;
            let total = formDetail[1].value;
            $.ajax({
                url: "{{ route('harian.tambah.sampah') }}",
                method: 'POST',
                dataType: 'json',
                data: {
                    kategori: kategori,
                    total: total,
                    _token: "{{ csrf_token() }}",
                },
                success: function(result) {
                    if (result.success) {
                        $('#kategori').prop('selectedIndex', 0);;
                        $('#total').val(null);
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
            $('#pemasukan').val(e);
        }

        const ubahJumlah = (e) => {
            if (isNaN(e) || e < 0 || e == 0) {
                $('#total_uang').text("Rp. 0,00 (Silahkan Input Pemasukan)");
            } else {
                $('#total_uang').text(formatRupiah(e) + ",00");

            }
        }
        ubahJumlah(0);

        const removeListTable = (v) => {
            $.ajax({
                url: "{{ route('harian.hapus.sampah') }}",
                method: 'DELETE',
                dataType: 'json',
                data: {
                    id: v,
                    _token: "{{ csrf_token() }}",
                },
                success: function(result) {
                    if (result.success) {
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
                url: "{{ route('harian.list') }}",
                method: 'POST',
                dataType: 'json',
                data: {
                    _token: "{{ csrf_token() }}",
                },
                success: function(result) {
                    if (result.success) {
                        $('#body_table').empty();
                        $('#total_akhir').empty();
                        var resultValue = JSON.parse(JSON.stringify(result.data));
                        var total = 0;
                        if (resultValue.length <= 0) {
                            $('#btn_submit').attr('disabled', true);
                            var html = `
                            <tr>
                                <td colspan="5" class="text-center">Kosong</td>
                            </tr>
                            `
                            $("#body_table").append(html);
                            $('#total_akhir').text('0 KG');
                        } else {
                            $('#btn_submit').attr('disabled', false);
                            $.each(resultValue, function(key, value) {
                                total = parseInt(total) + parseInt(value.jumlah_sampah);
                                var html = `
                                <tr>
                                    <td>
                                        <!-- Field wrapper start -->
                                        <div class="field-wrapper m-0">
                                            <div class="input-group">
                                                <input type="text" value="` + value.nama_kategori + `" disabled class="form-control">
                                            </div>
                                        </div>
                                        <!-- Field wrapper end -->
                                    </td>
                                    <td>
                                        <!-- Field wrapper start -->
                                        <div class="field-wrapper m-0">
                                            <div class="input-group">
                                                <input type="text" style="text-transform:capitalize" value="` + value
                                    .jenis_sampah + `" disabled class="form-control">
                                            </div>
                                        </div>
                                        <!-- Field wrapper end -->
                                    </td>
                                    <td>
                                        <!-- Field wrapper start -->
                                        <div class="field-wrapper m-0">
                                            <input type="text" value="` + value.jumlah_sampah + ` Kg" disabled class="form-control">
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
                            $('#total_akhir').text(total + ' KG');
                        }
                    }
                },
                error: function(result) {
                    alert(result.responseText);
                }
            })
        }
        $('#input_jumlah').bind('keyup paste', function() {
            this.value = +this.value.replace(/[^0-9]/g, '');
        });
        $('#input_jumlah').val('0');
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
                        <a href="{{ route('harian') }}" class="btn btn-danger mb-3"><i
                                class="icon-arrow-left"></i>Kembali</a>
                    </div>
                </div>
                <!-- Card end -->

                <!-- Card start -->
                <div class="card">
                    <div class="card-header-lg">
                        <h4>Tambah Rekapan Sampah Harian {{ $rekapan['status'] }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="card-body">

                            <!-- Row start -->
                            <div class="row justify-content-between">

                                <div class="col-xl-5 col-lg-5 col-md-5 col-sm-5 col-12">

                                    <!-- Row start -->
                                    <div class="row gutters">
                                        <div class="col-12">
                                            <div class="form-section-header light-bg">Data Rekapan</div>
                                        </div>
                                        <div class="col-12">
                                            <!-- Field wrapper start -->
                                            <div class="field-wrapper">
                                                <input class="form-control" disabled type="text"
                                                    value="{{ 'Kasir Rekapan Harian ' . $rekapan['status'] }}">
                                                <div class="field-placeholder">Status Kasir</div>
                                            </div>
                                            <!-- Field wrapper end -->
                                        </div>
                                        <div class="col-12">
                                            <!-- Field wrapper start -->
                                            <div class="field-wrapper">
                                                <div class="input-group">
                                                    <input type="text" disabled value="{{ $rekapan['kode_transaksi'] }}"
                                                        class="form-control">
                                                </div>
                                                <div class="field-placeholder">Nomor Transaksi</div>
                                            </div>
                                            <!-- Field wrapper end -->
                                        </div>
                                        <div class="col-12">
                                            <!-- Field wrapper start -->
                                            <div class="field-wrapper">
                                                <div class="input-group">
                                                    <input type="text" disabled value="{{ $rekapan['tanggal'] }}"
                                                        class="form-control">
                                                </div>
                                                <div class="field-placeholder">Tanggal Transaksi</div>
                                            </div>
                                            <!-- Field wrapper end -->
                                        </div>
                                        <div class="col-12">
                                            <div class="form-section-header light-bg">Input Sampah
                                                {{ $rekapan['status'] }}</div>
                                        </div>
                                        <form id="form-ajax">
                                            <div class="row gutters">
                                                <div class="col-6">

                                                    <div class="field-wrapper">
                                                        <select name="kategori" id="kategori" required
                                                            class="select-single js-states" title="Pilih Kategori Sampah"
                                                            data-live-search="true">
                                                            <option value="">-- Pilih Kategori Sampah --</option>
                                                            @foreach ($kategori as $k)
                                                                <option value="{{ $k->id }}">
                                                                    {{ $k->nama_kategori }} --
                                                                    {{ ucWords($k->jenis_sampah) }} --
                                                                    {{ 'Stok ' . $k->total_sampah . ' Kg' }}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="field-placeholder">Kategori Sampah<span
                                                                class="text-danger">*</span></div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <!-- Field wrapper start -->
                                                    <div class="field-wrapper">
                                                        <div class="input-group">
                                                            <input type="number" autofocus id="total" required name="total"
                                                                min="0" step="0.01"
                                                                class="form-control datepicker-opens-left">
                                                            <span class="input-group-text">
                                                                KG
                                                            </span>
                                                        </div>
                                                        <div class="field-placeholder">Jumlah Sampah
                                                            {{ $rekapan['status'] }}<span class="text-danger">*</span>
                                                        </div>
                                                    </div>
                                                    <!-- Field wrapper end -->
                                                </div>
                                                <div class="col-12 mb-3">
                                                    <button type="submit" class="btn btn-primary" id="btn-tambah">Tambah
                                                        Keranjang</button>
                                                </div>
                                            </div>
                                        </form>
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
                                                            <tr>
                                                                <th>Item</th>
                                                                <th>Kategori</th>
                                                                <th>Jumlah Sampah</th>
                                                                <th>Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="body_table">


                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <p class="mt-2">Tipe Rekapan</p>
                                                                </td>
                                                                <td colspan="2">
                                                                    <p class="mt-2">Rekapan Harian Sampah
                                                                        {{ ucWords($rekapan['status']) }}</p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <p class="mt-2">Diinput Oleh</p>
                                                                </td>
                                                                <td colspan="2">
                                                                    <p class="mt-2">{{ Auth::user()->name }}
                                                                    </p>
                                                                </td>
                                                            </tr>
                                                            @if ($rekapan['status'] == 'Keluar')
                                                                <tr>
                                                                    <td colspan="2">
                                                                        <h5 class="mt-2">Input Pemasukan</h5>
                                                                    </td>
                                                                    <td colspan="4">
                                                                        <input type="text"
                                                                            onKeyPress="if(this.value.length==10) return false;"
                                                                            pattern="[1-9]{1}[0-9]{9}"
                                                                            onkeyup="liveJumlah(this.value); ubahJumlah(this.value)"
                                                                            min="0" autofocus id="input_jumlah" value="0"
                                                                            class="form-control" required>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="2">
                                                                        <h5 class="mt-2">Total Uang Pemasukan</h5>
                                                                    </td>
                                                                    <td colspan="4">
                                                                        <h5 class="mt-2" id="total_uang"></h5>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                            <tr>
                                                                <td colspan="2">
                                                                    <h5 class="mt-2">Total Sampah
                                                                        {{ $rekapan['status'] }}
                                                                    </h5>
                                                                </td>
                                                                <td colspan="2">
                                                                    <h5 class="mt-2" id="total_akhir">0 KG</h5>
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
                                                        <form id="save-{{ $rekapan['id'] }}"
                                                            action="{{ route('harian.aksi') }}" method="post">
                                                            {{-- Ada sweetalert --}}
                                                            @csrf
                                                            <input type="hidden" id="pemasukan" name="pemasukan">
                                                            <a href="#" data-redirect="{{ route('harian') }}"
                                                                class="btn btn-danger confirm-cancel">Batalkan</a>
                                                            <button type="submit" class="btn btn-primary ms-1 confirm-save"
                                                                data-formid="{{ $rekapan['id'] }}"
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
