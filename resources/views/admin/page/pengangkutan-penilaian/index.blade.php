@extends('admin.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Pengangkutan dan Penilaian Harian')
@section('meta-description', 'Data Pengangkutan dan Penilaian Harian')
@section('meta-keyword', 'Pengangkutan dan Penilaian Harian')
{{-- End Meta --}}
@section('title', 'Pengangkutan dan Penilaian Harian')
@section('footer')
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
        const requestDetailOrder = (value) => {
            $.ajax({
                url: "{{ route('pengangkutan-penilaian.detail') }}",
                method: 'POST',
                dataType: 'html',
                data: {
                    id: value,
                    _token: "{{ csrf_token() }}",
                },
                beforeSend: function() {
                    $('#loadedOrder').show();
                },
                complete: function() {
                    $('#loadedOrder').hide();
                },
                success: function(result) {
                    $('#modal-body-order').empty()
                    $('#modal-body-order').append(result);
                },
                error: function(result) {
                    alert(result.responseText);
                }
            });
        }
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
                                url: "{{ route('pengangkutan-penilaian.destroy.all') }}",
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
    </script>
    <script type="text/javascript">
        $(function() {
            var table = $('.yajra-datatables').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('pengangkutan-penilaian.getAll') }}",
                    data: function(d) {
                        d.tanggal_awal = $('input[name="tanggal_awal"]').val(),
                            d.tanggal_akhir = $('input[name="tanggal_akhir"]').val()
                    },
                },
                columns: [{
                        data: 'id',
                        name: 'id',
                        render: function(data) {
                            return `<div class="form-check">
                                        <input type="checkbox" class="form-check-input check-item" value="` + data + `"  name="id_checkbox" id="exampleCheck1">
                                        <label class="form-check-label" for="exampleCheck1"></label>
                                    </div>`;
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: true,
                    },
                    {
                        data: 'tanggal_angkut_penilaian',
                        name: 'tanggal_angkut_penilaian'
                    },
                    {
                        data: 'user',
                        name: 'user'
                    },
                    {
                        data: 'pegawai',
                        name: 'pegawai'
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

                <!-- Card start -->
                <h4 class="mb-3">Pengangkutan dan Penilaian Harian</h4>
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
                <!-- Card end -->
                <div class="card">
                    <div class="card-body py-2">
                        <form class="row" action="" method="GET">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-sm-2 d-flex align-items-center">
                                        <span class="p">Filter Data</span>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group m-0">
                                            <label class="m-0 mb-2" for="tanggal_awal"><small>Tanggal
                                                    Awal</small></label>
                                            <input type="date" id="tanggal_awal" class="form-control " name="tanggal_awal"
                                                value="{{ old('tanggal_awal') ?? request()->tanggal_awal }}">

                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group m-0">
                                            <label class="m-0 mb-2" for="tanggal_akhir"><small>Tanggal
                                                    Akhir</small></label>
                                            <input type="date" id="tanggal_akhir" class="form-control "
                                                name="tanggal_akhir"
                                                value="{{ old('tanggal_akhir') ?? request()->tanggal_akhir }}">

                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group m-0">
                                            <label class="m-0 mb-2" for=""></label>
                                            <div class="d-flex justify-content-end">
                                                <button class="btn btn-sm mt-2 btn-primary rounded px-5">Filter</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header-lg">
                        <div>
                            <h4 class="d-flex align-items-center"><i class="icon-truck icon-large me-2"></i>Jumlah
                                Pengangkutan dan Penilaian Harian ({{ $penilaian }})</h4>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end">
                                    <div class="custom-btn-group">
                                        <a href="{{ route('pengangkutan-penilaian') }}" class="btn btn-primary rounded"
                                            type="button">
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
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                @php
                                    try {
                                        $date = \Carbon\Carbon::parse(request()->tanggal_awal)->format('d F Y') . ' - ' . \Carbon\Carbon::parse(request()->tanggal_akhir)->format('d F Y');
                                    } catch (\Exception $e) {
                                        $date = \Carbon\Carbon::now()->format('d F Y');
                                    }
                                @endphp
                                <h5 class="mt-3">Rekapan Data - <span class="text-success">
                                        Tanggal
                                        {{ !empty(request()->tanggal_awal) && !empty(request()->tanggal_akhir) ? $date : \Carbon\Carbon::now()->format('d F Y') }}</span>
                                </h5>
                            </div>
                            <div class="col-6">
                                <div class="d-flex justify-content-end">
                                    <div class="custom-btn-group">
                                        <a href="{{ route('pengangkutan-penilaian.scan') }}" class="btn btn-info rounded"
                                            type="button">
                                            <span class="icon-camera"></span> Scan Barcode</a>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-light dropdown-toggle"
                                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Fitur Multi Aksi
                                            </button>
                                            <div class="dropdown-menu">
                                                {{-- <div class="dropdown-divider"></div> --}}
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
                                        <th style="width: 15%">Aksi</th>
                                        <th>Tanggal dan Waktu Pengangkutan</th>
                                        <th>Nama Pelanggan atau Nasabah</th>
                                        <th>Diangkut dan Dinilai Oleh</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Card end -->

            </div>
        </div>
        <!-- Row end -->
    </div>
    <div class="modal fade" id="detail" tabindex="-1" aria-labelledby="detailLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="" method="POST">
                <div class="modal-content" id="modal-body-order">
                    <div id="loadedOrder" class="modal-body">Loading Data....</div>
                </div>
            </form>
        </div>
    </div>
@endsection
