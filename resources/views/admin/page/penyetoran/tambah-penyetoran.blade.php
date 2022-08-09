@extends('admin.layouts.kasir')
{{-- Meta --}}
@section('meta-name', 'Kasir Penyetoran Sampah, Transaksi')
@section('meta-description', 'Data Kasir Penyetoran Sampah, Transaksi')
@section('meta-keyword', 'Kasir Penyetoran Sampah, Transaksi')
{{-- End Meta --}}
@section('title', 'Kasir Penyetoran Sampah - Transaksi')
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
                url: "{{ route('penyetoran.tambah.sampah') }}",
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

        const removeListTable = (v) => {
            $.ajax({
                url: "{{ route('penyetoran.hapus.sampah') }}",
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
                url: "{{ route('penyetoran.list') }}",
                method: 'POST',
                dataType: 'json',
                data: {
                    _token: "{{ csrf_token() }}"
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
                            $('#total_akhir').text(formatRupiah(0));
                        } else {
                            $('#btn_submit').attr('disabled', false);
                            $.each(resultValue, function(key, value) {
                                total = parseInt(total) + parseInt(value.sub_total);
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
                                                <input type="text" value="` + value.jumlah_sampah + ` Kg" disabled class="form-control">
                                            </div>
                                        </div>
                                        <!-- Field wrapper end -->
                                    </td>
                                    <td>
                                        <!-- Field wrapper start -->
                                        <div class="field-wrapper m-0">
                                            <input type="text" value="` + formatRupiah(value.harga_beli) + `" disabled class="form-control">
                                        </div>
                                        <!-- Field wrapper end -->
                                    </td>
                                    <td>
                                        <!-- Field wrapper start -->
                                        <div class="field-wrapper m-0">
                                            <div class="input-group">
                                                <input type="text" value="` + formatRupiah(value.sub_total) + `" disabled class="form-control">
                                            </div>
                                        </div>
                                        <!-- Field wrapper end -->
                                    </td>
                                    <td>
                                        <div class="table-actions">
                                            <button type="button" class="btn btn-danger" onclick="removeListTable('` +value.id + `')">
                                                <i class="icon-trash-2"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                `;
                                $("#body_table").append(html);
                            });
                            $('#total_akhir').text(formatRupiah(total));
                        }
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
                        <a href="{{ route('penyetoran') }}" class="btn btn-danger mb-3"><i
                                class="icon-arrow-left"></i>Kembali</a>
                    </div>
                </div>
                <!-- Card end -->

                <!-- Card start -->
                <div class="card">
                    <div class="card-header-lg">
                        <h4>Tambah Nabung Sampah Nasabah</h4>
                    </div>
                    <div class="card-body">
                        <div class="card-body">

                            <!-- Row start -->
                            <div class="row justify-content-between">

                                <div class="col-xl-5 col-lg-5 col-md-5 col-sm-5 col-12">

                                    <!-- Row start -->
                                    <div class="row gutters">
                                        @if ($user->role == 4)
                                            @php
                                                $ket = 'Nasabah';
                                            @endphp
                                        @endif
                                        <div class="col-12">
                                            <div class="form-section-header light-bg">Data {{ $ket }}</div>
                                        </div>
                                        <div class="col-12">
                                            <!-- Field wrapper start -->
                                            <div class="field-wrapper">
                                                <input class="form-control" disabled type="text"
                                                    value="{{ $user->no_member . ' -- ' . $user->name }} -- {{ $user->role == 4 ? 'Nasabah' : 'Pelanggan' }}">
                                                <div class="field-placeholder">Nama {{ $ket }}</div>
                                            </div>
                                            <!-- Field wrapper end -->
                                        </div>
                                        <div class="col-12">
                                            <!-- Field wrapper start -->
                                            <div class="field-wrapper">
                                                <div class="input-group">
                                                    <input type="text" disabled
                                                        value="{{ $penyetoran['kode_transaksi'] }}"
                                                        class="form-control">
                                                </div>
                                                <div class="field-placeholder">Nomor Transaksi</div>
                                            </div>
                                            <!-- Field wrapper end -->
                                        </div>

                                        <div class="col-12">
                                            <div class="form-section-header light-bg">Input Sampah</div>
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
                                                                    {{ ucWords($k->jenis_sampah) }}</option>
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
                                                        <div class="field-placeholder">Jumlah Sampah<span
                                                                class="text-danger">*</span></div>
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

                                        {{-- <div class="col-12">
                                                <div class="form-section-header light-bg">Checkout</div>
                                            </div> --}}
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
                                                                <th>Kategori</th>
                                                                <th>Jumlah</th>
                                                                <th>Harga Satuan</th>
                                                                <th>Subtotal</th>
                                                                <th>Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="body_table">


                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <h5 class="mt-2">Total</h5>
                                                                </td>
                                                                <td colspan="3">
                                                                    <h5 class="mt-2" id="total_akhir">Rp.0</h5>
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
                                                        <form id="save-{{ $penyetoran['id'] }}"
                                                            action="{{ route('penyetoran.aksi') }}"
                                                            method="post">
                                                            {{-- Ada sweetalert --}}
                                                            @csrf
                                                            <a href="#" data-redirect="{{ route('penyetoran') }}"
                                                                class="btn btn-danger confirm-cancel">Batalkan</a>
                                                            <button type="submit" class="btn btn-primary ms-1 confirm-save"
                                                                data-formid="{{ $penyetoran['id'] }}"
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
