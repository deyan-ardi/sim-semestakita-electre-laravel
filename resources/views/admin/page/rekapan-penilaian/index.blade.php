@extends('admin.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Rekapan Penilaian')
@section('meta-description', 'Data Rekapan Penilaian')
@section('meta-keyword', 'Rekapan Penilaian')
{{-- End Meta --}}
@section('title', 'Rekapan Penilaian')
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
                url: "{{ route('rekapan-penilaian.detail') }}",
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
        const notifRekomendasi = () => {
            if ("{{ $configKey->status }}" == "active") {
                var text =
                    'Hanya pelanggan/nasabah yang status iurannya aktif dan jumlah penilaian lebih dari/sama dengan  ' +
                    "{{ $configKey->value }}" + ' kali yang akan diproses';
            } else {
                var text = 'Hanya pelanggan/nasabah yang status iurannya aktif yang akan diproses';
            }
            Swal.fire({
                    title: 'Informasi Penting',
                    text: text,
                    icon: 'info',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Lanjutkan >>>'
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        $('#rekomendasi_pemilah').submit();
                    }
                });
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
    </script>
    <script type="text/javascript">
        $(function() {
            var table = $('.yajra-datatables').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('rekapan-penilaian.getAll') }}",
                    data: function(d) {
                        d.bulan = $('#bulan').val(),
                            d.tahun = $('#tahun').val()
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
                        data: null,
                        name: 'action',
                        orderable: false,
                        searchable: true,
                        render: function(o) {
                            var exportField =
                                "{{ route('rekapan-penilaian.export.single', '') }}" +
                                "/" +
                                o.id;
                            @if (Auth::user()->role == 3 || Auth::user()->role == 6)
                                return `
                                <form action="` + exportField + `" method="POST">
                                    @csrf
                                    <div class="actions">
                                        <a href="javascript:void(0)"
                                            onclick="requestDetailOrder('` + o.id +
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        `')"
                                            data-bs-toggle="modal" data-bs-target="#detail" data-toggle="tooltip" data-placement="top"
                                            title="Lihat Detail Penilaian" data-original-title="Lihat Detail Penilaian">
                                            <i class="icon-eye text-info"></i>
                                        </a>
                                        <button type="submit" data-toggle="tooltip" data-placement="top" title="Cetak Data"
                                            data-original-title="Cetak Data" class="btn btn-link text-decoration-none ps-2 pb-2">
                                            <i class="icon-print text-success"></i>
                                        </button>
                                    </div>
                                </form>
                                `;
                            @else
                                return `
                                <form action="` + exportField + `" method="POST">
                                    @csrf
                                    <div class="actions">
                                        <a href="javascript:void(0)"
                                            onclick="requestDetailOrder('` + o.id +
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        `')"
                                            data-bs-toggle="modal" data-bs-target="#detail" data-toggle="tooltip" data-placement="top"
                                            title="Lihat Detail Penilaian" data-original-title="Lihat Detail Penilaian">
                                            <i class="icon-eye text-info"></i>
                                        </a>
                                        <button type="submit" data-toggle="tooltip" data-placement="top" title="Cetak Data"
                                            data-original-title="Cetak Data" class="btn btn-link text-decoration-none ps-2 pb-2">
                                            <i class="icon-print text-success"></i>
                                        </button>
                                    </div>
                                </form>
                                `;
                            @endif
                        }
                    },
                    {
                        data: 'no_member',
                        name: 'no_member'
                    },
                    {
                        data: 'user',
                        name: 'user'
                    },
                    {
                        data: 'jumlah_penilaian',
                        name: 'jumlah_penilaian',
                        render: function(o) {
                            return o + ' Kali';
                        }
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(o) {
                            if (o == 1) {
                                return '<span class="badge bg-success">Aktif Membayar</span>';
                            } else {
                                return '<span class="badge bg-danger">Tidak Aktif Membayar</span>';

                            }
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

                <!-- Card start -->
                <h4 class="mb-3">Rekapan Penilaian</h4>
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
                                            <label class="m-0 mb-2" for="bulan"><small>Periode Bulan</small></label>
                                            <select id="bulan" class="form-control " name="bulan"
                                                value="{{ request()->bulan }}">
                                                <option value="">-- Pilih Periode Bulan --</option>
                                                <option value="January"
                                                    {{ request()->bulan == 'January' || old('bulan') == 'January' ? 'selected' : '' }}>
                                                    Januari
                                                </option>
                                                <option value="February"
                                                    {{ request()->bulan == 'February' || old('bulan') == 'February' ? 'selected' : '' }}>
                                                    Febuari
                                                </option>
                                                <option value="March"
                                                    {{ request()->bulan == 'March' || old('bulan') == 'March' ? 'selected' : '' }}>
                                                    Maret</option>
                                                <option value="April"
                                                    {{ request()->bulan == 'April' || old('bulan') == 'April' ? 'selected' : '' }}>
                                                    April</option>
                                                <option value="May"
                                                    {{ request()->bulan == 'May' || old('bulan') == 'May' ? 'selected' : '' }}>
                                                    Mei
                                                </option>
                                                <option value="June"
                                                    {{ request()->bulan == 'June' || old('bulan') == 'June' ? 'selected' : '' }}>
                                                    Juni</option>
                                                <option value="July"
                                                    {{ request()->bulan == 'July' || old('bulan') == 'July' ? 'selected' : '' }}>
                                                    Juli</option>
                                                <option value="August"
                                                    {{ request()->bulan == 'August' || old('bulan') == 'August' ? 'selected' : '' }}>
                                                    Agustus
                                                </option>
                                                <option value="September"
                                                    {{ request()->bulan == 'September' || old('bulan') == 'September' ? 'selected' : '' }}>
                                                    September
                                                </option>
                                                <option value="October"
                                                    {{ request()->bulan == 'October' || old('bulan') == 'October' ? 'selected' : '' }}>
                                                    Oktober
                                                </option>
                                                <option value="November"
                                                    {{ request()->bulan == 'November' || old('bulan') == 'November' ? 'selected' : '' }}>
                                                    November
                                                </option>
                                                <option value="December"
                                                    {{ request()->bulan == 'December' || old('bulan') == 'December' ? 'selected' : '' }}>
                                                    Desember
                                                </option>
                                            </select>

                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group m-0">
                                            <label class="m-0 mb-2" for="tahun"><small>Periode Tahun</small></label>
                                            @php
                                                $year_now = \Carbon\Carbon::now()->format('Y');
                                                $year_start = 2020;
                                                $selisih = $year_now - $year_start;
                                            @endphp
                                            <select id="tahun" class="form-control " name="tahun">
                                                <option value="">-- Pilih Periode Tahun --</option>
                                                @for ($i = 0; $i <= $selisih; $i++)
                                                    <option value="{{ $year_start + $i }}"
                                                        {{ request()->tahun == $year_start + $i || old('tahun') == $year_start + $i ? 'selected' : '' }}>
                                                        {{ $year_start + $i }}
                                                    </option>
                                                @endfor
                                            </select>

                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group m-0">
                                            <label class="m-0 mb-2" for=""></label>
                                            <div class="d-flex justify-content-end" oncl>
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
                            <h4 class="d-flex align-items-center"><i class="icon-list icon-large me-2"></i>Jumlah
                                Rekapan Penilaian ({{ $rekapan }})</h4>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end">
                                    <div class="custom-btn-group">
                                        <a href="{{ route('rekapan-penilaian') }}" class="btn btn-primary rounded"
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
                                <h5 class="mt-3">Rekapan Penilaian - <span class="text-success">
                                        Periode
                                        {{ !empty(request()->bulan) && !empty(request()->tahun) ? ucWords(strtolower(request()->bulan)) . ' ' . request()->tahun : \Carbon\Carbon::now()->format('F Y') }}</span>
                                </h5>
                            </div>
                            <div class="col-6">
                                <div class="d-flex justify-content-end">
                                    <div class="custom-btn-group">
                                        <form action="{{ route('rekapan-penilaian.rekomendasi') }}"
                                            id="rekomendasi_pemilah" method="GET">
                                            <input type="hidden" name="bulan" required
                                                value="{{ !empty(request()->bulan) ? request()->bulan : \Carbon\Carbon::now()->format('F') }}">
                                            <input type="hidden" name="tahun" required
                                                value="{{ !empty(request()->tahun) ? request()->tahun : \Carbon\Carbon::now()->format('Y') }}">
                                            <button type="button" onclick="notifRekomendasi()"
                                                class="btn btn-primary rounded" type="button">
                                                <span class="icon-award"></span> Rekomendasi Pemilah Aktif</button>
                                            <div class="btn-group">
                                        </form>
                                        <button type="button" class="btn btn-light dropdown-toggle"
                                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Fitur Multi Aksi
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="javascript:void(0)"
                                                onclick="$('#ekspor_filter').submit()">Ekspor Hasil Filter</a>
                                            <a class="dropdown-item" href="javascript:void(0)"
                                                onclick="eksporMultiple()">Ekspor Data Dipilih</a>

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
                                            <input type="checkbox" class="form-check-input check-all" id=" exampleCheck1">
                                            <label class="form-check-label" for="exampleCheck1"></label>
                                        </div>
                                    </th>
                                    <th style="width: 15%">Aksi</th>
                                    <th>No Member</th>
                                    <th>Nama Pelanggan atau Nasabah</th>
                                    <th>Jumlah Penilaian Dari Pegawai</th>
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

    <form action="{{ route('rekapan-penilaian.export', 'filter') }}" method="POST" id="ekspor_filter">
        @csrf
        <input type="hidden" name="bulan"
            value="{{ !empty(request()->bulan) ? request()->bulan : \Carbon\Carbon::now()->format('F') }}">
        <input type="hidden" name="tahun"
            value="{{ !empty(request()->tahun) ? request()->tahun : \Carbon\Carbon::now()->format('Y') }}">
    </form>

    <form action="{{ route('rekapan-penilaian.export', 'select') }}" method="POST" id="ekspor_select">
        @csrf
        <input type="hidden" name="id_user" id="multipleUser">
    </form>
@endsection
