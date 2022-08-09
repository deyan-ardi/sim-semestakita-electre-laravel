@extends('admin.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Master Data, Kriteria Penilaian')
@section('meta-description', 'Data Master Data, Kriteria Penilaian')
@section('meta-keyword', 'Master Data, Kriteria Penilaian')
{{-- End Meta --}}
@section('title', 'Master - Kriteria Penilaian')
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

        const deleteValidation = () => {

            Swal.fire({
                    title: 'Apakah Anda Yakin Ingin Mengosongkan Kriteria Penilaian?',
                    text: 'Seluruh Data Terkait Akan Dihapus',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yakin'
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        $('#delete-form').submit();
                    }
                });

        }

        const publishValidation = () => {

            Swal.fire({
                    title: 'Apakah Anda Yakin Ingin Mempublish?',
                    text: 'Tindakan Ini Tidak Dapat Dibatalkan, Silahkan Pastikan Data Yang Akan Dipublish Benar',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yakin'
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        $('#publish-form').submit();
                    }
                });

        }

        const unpublishValidation = () => {

            Swal.fire({
                    title: 'Apakah Anda Yakin Ingin Membatalkan Publish Data?',
                    text: 'Anda masih dapat melakukan perubahan data selama data belum dipublish',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yakin'
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        $('#unpublish-form').submit();
                    }
                });

        }
        const checkBobot = () => {
            var input = document.getElementsByName('bobot[]');
            var tot = 0;
            for (var i = 0; i < input.length; i++) {
                var a = input[i];
                tot = parseInt(tot) + parseInt(a.value);
            }

            if (tot < 100) {
                var sisa = 100 - parseInt(tot);
                var html =
                    '<div class="alert alert-danger" role="alert"> Bobot Belum Terpenuhi, Tersisa Bobot Untuk Digunakan Sejumlah: ' +
                    sisa +
                    '% </div>';
                $('#alert-bobot').html(html);
                $('#perbaharui-bobot').attr('disabled', true);
            } else if (tot == 100) {
                var sisa = 100 - parseInt(tot);
                var html =
                    '<div class="alert alert-success" role="alert"> Bobot Terpenuhi 100%, Silahkan Disimpan </div>';
                $('#alert-bobot').html(html);
                $('#perbaharui-bobot').removeAttr('disabled');
            } else {
                var sisa = parseInt(tot) - 100;
                var html =
                    '<div class="alert alert-danger" role="alert"> Total Bobot Melebihi 100%, Kurangi Bobot Sebanyak : ' +
                    sisa +
                    '%</div>';
                $('#alert-bobot').html(html);
                $('#perbaharui-bobot').attr('disabled', true);
            }
        }
        checkBobot();
    </script>

    <script type="text/javascript">
        $(function() {
            var table = $('.yajra-datatables').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('kriteria-penilaian.getAll') }}",
                    data: function(d) {
                        d.bulan = "{{ request()->bulan }}",
                            d.tahun = "{{ request()->tahun }}"
                    },
                },
                columns: [{
                        data: 'urutan',
                        name: 'urutan',
                        render: function(data) {
                            if (data != null) {
                                return 'Tampil Ke-' + data;
                            } else {
                                return "Belum Disetel"
                            }
                        }
                    },
                    {
                        data: 'nama_kriteria',
                        name: 'nama_kriteria'
                    },
                    {
                        data: 'bobot',
                        name: 'bobot',
                        render: function(data) {
                            return data + '%';
                        }
                    },

                ],
                columnDefs: [{
                    targets: [0],
                    orderable: false,
                }]
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
                <h4 class="mb-3">Master - Kriteria Penilaian</h4>
                <!-- Card start -->
                @if ($count->count() <= 0)
                    <div class="alert alert-info">
                        Kriteria Penilaian Pemilah Aktif Bulanan Untuk Periode
                        {{ !empty(request()->bulan) && !empty(request()->tahun) ? ucWords(strtolower(request()->bulan)) . ' ' . request()->tahun : \Carbon\Carbon::now()->format('F Y') }}
                        Belum Ditambahkan, Lakukan Import Kriteria Terlebih Dahulu
                    </div>
                @endif
                @if ($count->where('publish', 0)->count() > 0 && $total_bobot != 100)
                    <div class="alert alert-danger">
                        Total Nilai Bobot Kriteria Belum Mencapai 100%, Data Rekomendasi Tidak Akan Diolah. Silahkan
                        Tambahkan Kriteria Baru atau Atur Nilai Bobot Kriteria Agar 100%
                    </div>
                @endif
                @if ($count->where('publish', 0)->count() > 0 && $total_bobot == 100)
                    <div class="alert alert-info">
                        Berhasil Melakukan Import Kriteria, Silahkan Lakukan Publish Kriteria Jika Sudah Sesuai
                    </div>
                @endif
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
                        <div>
                            <h4 class="d-flex align-items-center"><i class="icon-list icon-large me-2"></i>Jumlah Kriteria
                                Penilaian ({{ $count->count() }})</h4>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end">
                                    <div class="custom-btn-group">
                                        <a href="{{ route('kriteria-penilaian') }}" class="btn btn-primary rounded"
                                            type="button">
                                            <span class="icon-refresh refresh"></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


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
                <!-- Card end -->

                <!-- Card start -->
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <h5 class="mt-3">Data Kriteria Penilaian - <span class="text-success">
                                        Periode
                                        {{ !empty(request()->bulan) && !empty(request()->tahun) ? ucWords(strtolower(request()->bulan)) . ' ' . request()->tahun : \Carbon\Carbon::now()->format('F Y') }}</span>
                                </h5>
                            </div>
                            <div class="col-6">
                                <div class="d-flex justify-content-end">
                                    <div class="custom-btn-group">
                                        @if ($count->count() > 0)
                                            @if ($count->where('publish', '0')->count() > 0)
                                                <a href="javascript:void(0)" data-bs-toggle="modal"
                                                    data-bs-target="#exampleModal2" class="btn btn-warning rounded"
                                                    type="button">
                                                    <i class="icon-edit1"></i>
                                                    Ubah Bobot</a>
                                                <form action="{{ route('kriteria-penilaian.publish.all') }}"
                                                    method="post" id="publish-form">
                                                    @csrf
                                                    <input type="hidden" name="bulan"
                                                        value="{{ !empty(request()->bulan) ? request()->bulan : \Carbon\Carbon::now()->format('F') }}">
                                                    <input type="hidden" name="tahun"
                                                        value="{{ !empty(request()->tahun) ? request()->tahun : \Carbon\Carbon::now()->format('Y') }}">
                                                    <button type="button" onclick="publishValidation()"
                                                        class="btn btn-secondary">
                                                        <i class="icon-upload"></i>
                                                        Publish Kriteria
                                                    </button>
                                                </form>
                                                <form action="{{ route('kriteria-penilaian.destroy.all') }}"
                                                    id="delete-form" method="post">
                                                    @csrf
                                                    <input type="hidden" name="bulan"
                                                        value="{{ !empty(request()->bulan) ? request()->bulan : \Carbon\Carbon::now()->format('F') }}">
                                                    <input type="hidden" name="tahun"
                                                        value="{{ !empty(request()->tahun) ? request()->tahun : \Carbon\Carbon::now()->format('Y') }}">
                                                    <button type="button" onclick="deleteValidation()"
                                                        class="btn btn-danger">
                                                        <i class="icon-trash"></i>
                                                        Kosongkan Kriteria
                                                    </button>
                                                </form>
                                            @elseif($count->where('publish', '1')->count() > 0)
                                                @if ($config->status == 'active')
                                                    @if (\Carbon\Carbon::parse($count->where('publish', '1')->first()->updated_at)->addMinute($config->value)->format('Y-m-d H:i:s') >= \Carbon\Carbon::now()->format('Y-m-d H:i:s'))
                                                        <form action="{{ route('kriteria-penilaian.unpublish.all') }}"
                                                            method="post" id="unpublish-form">
                                                            @csrf
                                                            <input type="hidden" name="bulan"
                                                                value="{{ !empty(request()->bulan) ? request()->bulan : \Carbon\Carbon::now()->format('F') }}">
                                                            <input type="hidden" name="tahun"
                                                                value="{{ !empty(request()->tahun) ? request()->tahun : \Carbon\Carbon::now()->format('Y') }}">
                                                            <button type="button" onclick="unpublishValidation()"
                                                                class="btn btn-secondary">
                                                                <i class="icon-cancel"></i>
                                                                Batalkan Publish
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endif
                                            @endif
                                        @else
                                            <a href="javascript:void(0)" data-bs-toggle="modal"
                                                data-bs-target="#exampleModal" class="btn btn-primary rounded"
                                                type="button">
                                                + Import Kriteria</a>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="table-responsive mt-4">
                            <table id="yajra-datatables" class="table yajra-datatables">
                                <thead>
                                    <tr>
                                        <th>Urutan Pertanyaan Kriteria</th>
                                        <th>Nama Kriteria</th>
                                        <th>Nilai Bobot Kriteria (Maks 100%)</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="2">Total Nilai Bobot</th>
                                        <th>{{ $total_bobot }} %</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Card end -->

            </div>
        </div>
        <!-- Row end -->
    </div>
    <!-- Modal start -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-lg">
            <form action="{{ route('kriteria-penilaian.store') }}" method="POST">
                @csrf
                <input type="hidden" name="bulan"
                    value="{{ !empty(request()->bulan) ? request()->bulan : \Carbon\Carbon::now()->format('F') }}">
                <input type="hidden" name="tahun"
                    value="{{ !empty(request()->tahun) ? request()->tahun : \Carbon\Carbon::now()->format('Y') }}">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title" id="exampleModalLabel">Import
                            Kriteria Penilaian -
                            Periode
                            {{ !empty(request()->bulan) && !empty(request()->tahun) ? ucWords(strtolower(request()->bulan)) . ' ' . request()->tahun : \Carbon\Carbon::now()->format('F Y') }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive mt-4">
                            <table class="table custom-table">
                                <thead class="table-success">
                                    <tr>
                                        <th>
                                            <div class="form-check form-check-inline ms-1">
                                                <input type="checkbox" class="form-check-input check-all"
                                                    id=" exampleCheck1">
                                                <label class="form-check-label" for="exampleCheck1"></label>
                                            </div>

                                        </th>
                                        <th>Nama Kriteria</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($all_kriteria as $item)
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input check-item"
                                                        value="{{ $item->id }}" multiple name="id_checkbox[]"
                                                        id="exampleCheck1">
                                                    <label class="form-check-label" for="exampleCheck1"></label>
                                                </div>
                                            </td>
                                            <td>
                                                {{ $item->nama_kriteria }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Import Kriteria</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Modal end -->

    <!-- Modal start -->
    <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModal2Label" aria-hidden="true">
        <div class="modal-dialog  modal-lg">
            <form action="{{ route('kriteria-penilaian.update') }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title" id="exampleModalLabel">
                            Kriteria Penilaian -
                            Periode
                            {{ !empty(request()->bulan) && !empty(request()->tahun) ? ucWords(strtolower(request()->bulan)) . ' ' . request()->tahun : \Carbon\Carbon::now()->format('F Y') }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-danger mb-3 d-flex justify-content-end">* Wajib Diisi</div>
                        <div id="alert-bobot"></div>
                        <div class="table-responsive mt-4">
                            <table class="table custom-table">
                                <thead class="table-success">
                                    <tr>
                                        <th>Nama Kriteria</th>
                                        <th>Nilai Bobot (%)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($count as $index => $item)
                                        <tr>
                                            <td>
                                                <input type="hidden" name="id[]" value="{{ $item->id }}"
                                                    multiple required>
                                                {{ $item->nama_kriteria }}<span class="text-danger">*</span>
                                            </td>
                                            <td>
                                                <input
                                                    onkeyup="this.value = +this.value.replace(/[^0-9]/g, ''); checkBobot();"
                                                    type="number" min="0"
                                                    value="{{ old('bobot')[$index] ?? $item->bobot }}" max="100"
                                                    required name="bobot[]" multiple style="width: 100%">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="perbaharui-bobot">Perbaharui</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Modal end -->
@endsection
