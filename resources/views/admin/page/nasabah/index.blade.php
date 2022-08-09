@extends('admin.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Master Data, Data Nasabah')
@section('meta-description', 'Data Master Data, Data Nasabah')
@section('meta-keyword', 'Master Data, Data Nasabah')
{{-- End Meta --}}
@section('title', 'Master - Data Nasabah')
@section('footer')
    <script>
        function switchInput(el) {
            var switchValue = $("#switch-" + el).is(':checked');
            if (switchValue) {
                var setTo = 1;
            } else {
                var setTo = 0;
            }
            $.ajax({
                url: "{{ route('nasabah.ubahStatus') }}",
                type: 'POST',
                data: {
                    ids: el,
                    setTo: setTo,
                    _token: "{{ csrf_token() }}",
                },
                success: function(data) {
                    if (data['success']) {
                        location.reload(true);
                    } else {
                        Swal.fire({
                            position: "top-end",
                            icon: "error",
                            text: "Terjadi Kesalahan, Gagal Mengubah Status Iuran !",
                            showConfirmButton: false
                        })
                    }
                },
                error: function(data) {
                    alert(data.responseText);
                }
            });
        }
    </script>
    <script>
        $('.check-all').on('change', function() {
            if ($(this).is(':checked')) {
                $('.check-item').each(function(i, e) {
                    $(e).prop('checked', true);
                });
            } else {
                $('.check-item').each(function(i, e) {
                    $(e).prop('checked', false);
                });
            }
        });


        $('.check-item').each(function(i, e) {
            $(e).prop('checked', false);
        });

        const publishMultiple = () => {
            var allVals = [];
            $('.check-item').each(function(i, e) {
                if ($(e).is(':checked')) {
                    allVals.push($(e).val());
                }
            });
            if (allVals.length === 0) {
                Swal.fire({
                    position: "top-end",
                    icon: "error",
                    text: 'Checklist minimal satu data yang ingin dihapus',
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                        title: 'Apakah Anda Yakin Ingin Menghapus Yang Dipilih?',
                        text: 'Seluruh Data Terkait Akan Dihapus',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yakin'
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('nasabah.delete.all') }}",
                                method: 'POST',
                                dataType: 'json',
                                data: {
                                    id_user: allVals,
                                    _token: "{{ csrf_token() }}",
                                },
                                success: function(result) {
                                    if (result.success) {
                                        Swal.fire({
                                            position: "top-end",
                                            showConfirmButton: false,
                                            text: result.info,
                                            icon: "success"
                                        }).then(function() {
                                            location.reload();
                                        });
                                    } else {
                                        Swal.fire({
                                            position: "top-end",
                                            showConfirmButton: false,
                                            text: result.info,
                                            icon: "error"
                                        }).then(function() {
                                            location.reload();
                                        });
                                    }
                                },
                                error: function(result) {
                                    alert(result.responseText);
                                }
                            });
                        }
                    });

            }
        }
        const setStatusMultiple = () => {
            var allVal4 = [];
            $('.check-item').each(function(i, e) {
                if ($(e).is(':checked')) {
                    allVal4.push($(e).val());
                }
            });
            if (allVal4.length === 0) {
                Swal.fire({
                    position: "top-end",
                    icon: "error",
                    text: 'Checklist minimal satu data yang ingin diubah status iurannya',
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                        title: 'Apakah Anda Yakin Ingin Mengubah Status Iuran Yang Dipilih?',
                        text: 'Seluruh Data Terkait Akan Diubah Status Iurannya',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yakin'
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('nasabah.ubahStatus.all') }}",
                                method: 'POST',
                                dataType: 'json',
                                data: {
                                    id_user: allVal4,
                                    _token: "{{ csrf_token() }}",
                                },
                                success: function(result) {
                                    if (result.success) {
                                        Swal.fire({
                                            position: "top-end",
                                            showConfirmButton: false,
                                            text: result.info,
                                            icon: "success"
                                        }).then(function() {
                                            location.reload();
                                        });
                                    } else {
                                        Swal.fire({
                                            position: "top-end",
                                            showConfirmButton: false,
                                            text: result.info,
                                            icon: "error"
                                        }).then(function() {
                                            location.reload();
                                        });
                                    }
                                },
                                error: function(result) {
                                    alert(result.responseText);
                                }
                            });
                        }
                    });

            }
        }
        const eksporMultiple = () => {
            var allVals2 = [];
            $('.check-item').each(function(i, e) {
                if ($(e).is(':checked')) {
                    allVals2.push($(e).val());
                }
            });
            if (allVals2.length === 0) {
                Swal.fire({
                    position: "top-end",
                    icon: "error",
                    text: 'Checklist minimal satu data yang ingin diekspor',
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                        title: 'Apakah Anda Yakin Ingin Mengekspor Yang Dipilih?',
                        text: 'Seluruh Data Terkait Akan Diekspor',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yakin'
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            $('#multipleUser').val(JSON.stringify(allVals2));
                            $('#ekspor_select').submit();
                        }
                    });

            }
        }
        const qrCodeMultiple = () => {
            var allVals3 = [];
            $('.check-item').each(function(i, e) {
                if ($(e).is(':checked')) {
                    allVals3.push($(e).val());
                }
            });
            if (allVals3.length === 0) {
                Swal.fire({
                    position: "top-end",
                    icon: "error",
                    text: 'Checklist minimal satu data yang ingin dicetak Kode QRnya',
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                        title: 'Apakah Anda Yakin Ingin Mencetak Kode QR Nasabah Yang Dipilih?',
                        text: 'Seluruh Data Terkait Akan Dicetak Kode QRnya',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yakin'
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            $('#multipleQr').val(JSON.stringify(allVals3));
                            $('#ekspor_qr').submit();
                        }
                    });

            }
        }
    </script>
    <script type="text/javascript">
        $(function() {
            var table = $('.yajra-datatables').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('nasabah.getAll') }}",
                    data: function(d) {
                        d.pembayaran_rutin = "{{ request()->pembayaran_rutin }}",
                            d.status = "{{ request()->status }}"
                    },
                },
                columns: [{
                        data: 'id',
                        name: 'id',
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            return `<div class="form-check">
                                        <input type="checkbox" class="form-check-input check-item" value="` + data + `"  name="id_checkbox" id="exampleCheck1">
                                        <label class="form-check-label" for="exampleCheck1"></label>
                                    </div>`;
                        }
                    },
                    {
                        data: null,
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(o) {
                            var deleteField =
                                "{{ route('nasabah.delete', '') }}" +
                                "/" +
                                o.id;
                            var qrCodeField =
                                "{{ route('nasabah.cetak.qr', '') }}" +
                                "/" +
                                o.id;
                            var exportOneField =
                                "{{ route('nasabah.exportone', '') }}" +
                                "/" +
                                o.id;
                            var editField =
                                "{{ route('nasabah.edit', '') }}" +
                                "/" +
                                o.id;
                            return `
                             <form id="delete-` + o.id + `" action="` + deleteField + `" method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <div class="actions">
                                        <a href="` + editField + `"
                                            data-toggle="tooltip" data-placement="top"
                                            title="Ubah Nasabah" data-original-title="Edit">
                                            <i class="icon-edit1 text-info"></i>
                                        </a>
                                        <a target="_blank" rel="noopener noreferrer" href="` + exportOneField + `"
                                            data-toggle="tooltip" data-placement="top"
                                            title="Export Nasabah" data-original-title="Export">
                                            <i class="icon-print text-warning"></i>
                                        </a>

                                        <a href="` + qrCodeField + `" target="_blank" rel="noopener noreferrer"
                                            data-toggle="tooltip" data-placement="top"
                                            title="Cetak Kode QR" data-original-title="Cetak Kode QR">
                                            <i class="icon-center_focus_strong text-secondary"></i>
                                        </a>
                                        <button type="button" onclick="deleteButton('` + o.name + `','` + o
                                .id + `')"  data-toggle="tooltip"
                                            data-placement="top" title="Hapus Nasabah"
                                            class="btn btn-link text-decoration-none ps-2 pb-2">
                                            <i class="icon-trash text-danger"></i>
                                        </button>
                                    </div>
                            </form>
                            `;
                        }
                    },
                    {
                        data: 'no_member',
                        name: 'no_member'
                    },
                    {
                        data: 'no_rekening',
                        name: 'no_rekening'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'no_telp',
                        name: 'no_telp',
                        render: function(o) {
                            if(o == null){
                                return 'Belum Disetel';
                            }else{
                                return `<a class="text-primary" href="https://api.whatsapp.com/send?phone=62` +
                                    o + `"> 0` + o + `</a>`
                            }
                        }
                    },
                    {
                        data: 'email',
                        name: 'email',
                        render: function(o) {
                            return `<a class="text-primary" href="mailto:` + o +
                                `">` + o + `</a>`;
                        }
                    },
                    {
                        data: 'pembayaran_harian',
                        name: 'pembayaran_harian'
                    },
                    {
                        data: 'role',
                        name: 'role',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: null,
                        name: 'status_iuran',
                        render: function(o) {
                            if (o.status_iuran == 1) {
                                var checked = 'checked';
                            } else {
                                var checked = '';
                            }
                            return `<label class="switch">
                                        <input type="checkbox" ` + checked + `  class="switch-input"
                                            onchange="switchInput('` + o.id + `')" 
                                            id="switch-` + o.id + `" >
                                        <span class="switch-label" data-on="Aktif"
                                            data-off="Nonaktif"></span>
                                        <span class="switch-handle"></span>
                                    </label>`;
                        }
                    },

                ],
                columnDefs: [{
                    targets: [0],
                    orderable: false,
                }]
            });

            $('#filter_button').on("click", function() {
                table.draw();
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

                <h4 class="mb-3">Master - Data Nasabah</h4>
                <!-- Card start -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <p>Kesalahan input terdeteksi:</p>
                        <ul>
                            @foreach ($errors->all() as $index => $item)
                                <li>{{ $index + 1 }}. {{ $item }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="card">
                    <div class="card-header-lg">
                        <h4 class="d-flex align-items-center"><i class="icon-people icon-large me-3"></i>Jumlah Nasabah
                            ({{ $jumlahNasabah->count() }}), Aktif Membayar
                            ({{ $jumlahNasabah->where('status_iuran', 1)->count() }}), Tidak Aktif Membayar
                            ({{ $jumlahNasabah->where('status_iuran', 0)->count() }})</h4>
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end">
                                    <div class="custom-btn-group">
                                        <a href="{{ route('nasabah') }}" class="btn btn-primary rounded" type="button">
                                            <span class="icon-refresh refresh"></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card start -->
                <div class="card">
                    <div class="card-body py-2">
                        <form class="row" action="" method="GET">

                            <div class="col-lg-2 col-md-2 col-sm-12 d-flex align-items-center">
                                <span class="p">Filter Data</span>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group m-0">
                                    <label class="m-0 mb-2" for="pembayaran_rutin"><small>Pembayaran
                                            Rutin</small></label>
                                    <select name="pembayaran_rutin" id="pembayaran_rutin_filter" class="form-control">
                                        <option value="semua"
                                            {{ request()->pembayaran_rutin == 'semua' || old('pembayaran_rutin') == 'semua' ? 'selected' : '' }}>
                                            Semua</option>
                                        @foreach ($dataPembayaranRutin as $pembayaran_rutin)
                                            <option value="{{ $pembayaran_rutin->id }}"
                                                {{ request()->pembayaran_rutin == $pembayaran_rutin->id || old('pembayaran_rutin') == $pembayaran_rutin->id ? 'selected' : '' }}>
                                                {{ $pembayaran_rutin->nama_pembayaran }} -
                                                @currency($pembayaran_rutin->total_biaya)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group m-0">
                                    <label class="m-0 mb-2" for="status"><small>Status Iuran</small></label>
                                    <select name="status" id="status_filter"
                                        class="form-control @error('status') is-invalid @enderror">
                                        <option value="semua"
                                            {{ request()->status == 'semua' || old('status') == 'semua' ? 'selected' : '' }}>
                                            Semua</option>
                                        <option value="aktif"
                                            {{ request()->status == 'aktif' || old('status') == 'aktif' ? 'selected' : '' }}>
                                            Aktif</option>
                                        <option value="non-aktif"
                                            {{ request()->status == 'non-aktif' || old('status') == 'non-aktif' ? 'selected' : '' }}>
                                            Non-Aktif</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-12">
                                <div class="form-group m-0">
                                    <label class="m-0 mb-2" for=""></label>
                                    <div class="d-flex justify-content-end">
                                        <button class="btn btn-sm mt-2 btn-primary rounded px-5">Filter</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Card end -->

                @if ($dataPembayaranRutin->count() <= 0)
                    <!-- Card start -->
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <h5 class="mt-3">Data Nasabah</h5>
                                </div>
                            </div>
                            <div class="table-responsive mt-4">
                                <h6>Mohon mengisi data iuran rutin terlebih dahulu</h6>
                                <a href="{{ route('iuran.master') }}" class="btn btn-primary my-3">Data Iuran Rutin</a>
                            </div>
                        </div>
                    </div>
                    <!-- Card end -->
                @else
                    <!-- Card end -->
                    <!-- Card start -->
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <h5 class="mt-3">Data Nasabah</h5>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex justify-content-end">
                                        <div class="custom-btn-group">
                                            <a href="{{ route('nasabah.tambah') }}" class="btn btn-primary rounded"
                                                type="button">+
                                                Tambah</a>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-light dropdown-toggle"
                                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Fitur Multi Aksi
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="javascript:void(0)"
                                                        data-bs-toggle="modal" data-bs-target="#exampleModal">Import Data
                                                        Excel</a>
                                                    <a class="dropdown-item" href="javascript:void(0)"
                                                        onclick='$("#ekspor_data").submit()'>Ekspor Hasil
                                                        Filter</a>
                                                    <a class="dropdown-item" href="javascript:void(0)"
                                                        onclick="eksporMultiple()">Ekspor Data
                                                        Dipilih</a>
                                                    <a class="dropdown-item" href="javascript:void(0)"
                                                        onclick="qrCodeMultiple()">Cetak QR Nasabah</a>
                                                    <a class="dropdown-item" onclick="setStatusMultiple()"
                                                        href="javascript:void(0)">Ubah Status Iuran</a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item" onclick="publishMultiple()"
                                                        href="javascript:void(0)">Hapus Data Dipilih</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive mt-4">
                                <table id="yajra-datatables" class="table yajra-datatables">
                                    <thead>
                                        <tr>
                                            <th>
                                                <div class="form-check form-check-inline ms-1">
                                                    <input type="checkbox" class="form-check-input check-all"
                                                        id=" exampleCheck1">
                                                    <label class="form-check-label" for="exampleCheck1"></label>
                                                </div>

                                            </th>
                                            <th>Aksi</th>
                                            <th>No. Member</th>
                                            <th>No. Rekening</th>
                                            <th>Nama Nasabah</th>
                                            <th>Kontak</th>
                                            <th>Email</th>
                                            <th>Tagihan Iuran</th>
                                            <th>Role</th>
                                            <th>Status Iuran</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Card end -->
                @endif

            </div>
        </div>
    </div>
    <!-- Row end -->
    <form action="{{ route('nasabah.export', 'filter') }}" id="ekspor_data" method="POST">
        @csrf
        <input type="hidden" value="{{ request()->pembayaran_rutin }}" name="pembayaran_rutin">
        <input type="hidden" value="{{ request()->status }}" name="status">
    </form>

    <!-- Modal start -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('nasabah.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title" id="exampleModalLabel">Import
                            Data
                            Nasabah
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="file" class="form-label">File Data
                                Nasabah</label>
                            <input id="file" type="file" accept=".xlsx" name="file" class="form-control" required>
                            <div class="form-text">Hanya menerima file dengan
                                format
                                .xlsx. Download template disini: <a
                                    href="{{ asset('download/format_file_upload_nasabah.xlsx') }}"
                                    style="text-blue"><u>Download</u></a>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Import Nasabah</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Modal end -->

    <form action="{{ route('nasabah.export', 'select') }}" method="POST" id="ekspor_select">
        @csrf
        <input type="hidden" name="id_user" id="multipleUser">
    </form>

    <form action="{{ route('nasabah.cetak.qr.multiple') }}" target="_blank" rel="noopener noreferrer" method="POST"
        id="ekspor_qr">
        @csrf
        <input type="hidden" name="id_user" id="multipleQr">
    </form>
@endsection
