@extends('enduser.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Rekapan Penilaian dan Pemilah Aktif Terpilih')
@section('meta-description', 'Data Rekapan Penilaian dan Pemilah Aktif Terpilih')
@section('meta-keyword', 'Rekapan Penilaian dan Pemilah Aktif Terpilih')
{{-- End Meta --}}
@section('title', 'Rekapan Penilaian dan Pemilah Aktif Terpilih')
@section('custom-script')
    <script type="text/javascript">
        $(function() {
            var table1 = $('.yajra-datatables-1').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('enduser.rekapan-penilaian.getAll.riwayat') }}",
                    data: function(d) {
                        d.bulan = $('#bulan').val(),
                            d.tahun = $('#tahun').val()
                    },
                },
                columns: [{
                        data: 'tanggal_angkut_penilaian',
                        name: 'tanggal_angkut_penilaian'
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


            var table2 = $('.yajra-datatables-2').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('enduser.rekapan-penilaian.getAll.rekomendasi') }}",
                    data: function(d) {
                        d.bulan = $('#bulan').val(),
                            d.tahun = $('#tahun').val()
                    },
                },
                columns: [{
                        data: 'ranking',
                        name: 'ranking'
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
                        data: 'hasil_electre',
                        name: 'hasil_electre',
                        render: function(o) {
                            return o + " Point";
                        }
                    },
                    {
                        data: 'alasan',
                        name: 'alasan'
                    },


                ],
                columnDefs: [{
                    targets: [0],
                    orderable: false,
                }]
            });

            $('#filter_button').on("click", function() {
                table1.draw();
                table2.draw();
            });
        });
    </script>
@endsection
@section('content')
    <div class="content container-fluid balance-container">
        <div class="row">
            <div class="col-12 m-b-100">

                {{-- Riwayat Penarikan --}}
                <div class="page-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="section-title">Rekapan Penilaian, Pengangkutan, dan Pemilah Aktif Terpilih</h3>
                            <div class="line"></div>
                        </div>
                        <div class="col-auto">
                            <a class="btn btn-primary filter-btn" href="javascript:void(0);" id="filter_search"> Filter
                                <i class="fas fa-filter"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div id="filter_inputs" class="card filter-card pb-4">
                    <div class="card-body pb-0">
                        <div class="row">
                            <div class="col-12">
                                <form class="row" action="" method="GET">
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-sm-2 d-flex align-items-center">
                                                <span class="p">Filter Data</span>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group m-0">
                                                    <label class="m-0 mb-2" for="bulan"><small>Bulan</small></label>
                                                    <select id="bulan"
                                                        class="form-control @error('bulan') is-invalid @enderror"
                                                        name="bulan" value="{{ request()->bulan }}">
                                                        <option value="">-- Pilih Periode Bulan --</option>
                                                        <option value="January"
                                                            {{ request()->bulan == 'January' ? 'selected' : '' }}>Januari
                                                        </option>
                                                        <option value="February"
                                                            {{ request()->bulan == 'February' ? 'selected' : '' }}>Febuari
                                                        </option>
                                                        <option value="March"
                                                            {{ request()->bulan == 'March' ? 'selected' : '' }}>
                                                            Maret</option>
                                                        <option value="April"
                                                            {{ request()->bulan == 'April' ? 'selected' : '' }}>
                                                            April</option>
                                                        <option value="May"
                                                            {{ request()->bulan == 'May' ? 'selected' : '' }}>Mei
                                                        </option>
                                                        <option value="June"
                                                            {{ request()->bulan == 'June' ? 'selected' : '' }}>
                                                            Juni</option>
                                                        <option value="July"
                                                            {{ request()->bulan == 'July' ? 'selected' : '' }}>
                                                            Juli</option>
                                                        <option value="August"
                                                            {{ request()->bulan == 'August' ? 'selected' : '' }}>Agustus
                                                        </option>
                                                        <option value="September"
                                                            {{ request()->bulan == 'September' ? 'selected' : '' }}>
                                                            September
                                                        </option>
                                                        <option value="October"
                                                            {{ request()->bulan == 'October' ? 'selected' : '' }}>Oktober
                                                        </option>
                                                        <option value="November"
                                                            {{ request()->bulan == 'November' ? 'selected' : '' }}>
                                                            November
                                                        </option>
                                                        <option value="December"
                                                            {{ request()->bulan == 'December' ? 'selected' : '' }}>
                                                            Desember
                                                        </option>
                                                    </select>
                                                    @error('bulan')
                                                        <span class=" invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group m-0">
                                                    <label class="m-0 mb-2" for="tahun"><small>Tanggal
                                                            Akhir</small></label>
                                                    @php
                                                        $year_now = \Carbon\Carbon::now()->format('Y');
                                                        $year_start = 2020;
                                                        $selisih = $year_now - $year_start;
                                                    @endphp
                                                    <select id="tahun"
                                                        class="form-control @error('tahun') is-invalid @enderror"
                                                        name="tahun" value="{{ request()->tahun }}">
                                                        <option value="">-- Pilih Periode Tahun --</option>
                                                        @for ($i = 0; $i <= $selisih; $i++)
                                                            <option value="{{ $year_start + $i }}"
                                                                {{ request()->tahun == $year_start + $i ? 'selected' : '' }}>
                                                                {{ $year_start + $i }}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                    @error('tahun')
                                                        <span class=" invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="form-group m-0">
                                                    <label class="m-0 mb-2" for=""></label>
                                                    <div class="d-flex justify-content-end">
                                                        <button
                                                            class="btn btn-sm mt-2 btn-primary rounded px-5">Filter</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="balance-info">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="dash-widget-header">
                                        <span class="dash-widget-icon bg-8">
                                            <i class="fas fa-award"></i>
                                        </span>
                                        <div class="dash-count">
                                            <div class="dash-title">Total Terpilih Sebagai Pemilah Aktif Bulanan</div>
                                            <div class="dash-counts">
                                                <h4 class="text-primary">{{ $total }} Kali</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- End of Informasi Rekapan Penilaian dan Rekomendasi Pemilah Aktif --}}




                {{-- Penarikan --}}
                <div class="balance-history">
                    <div class="row">
                        <div class="col-sm-12">
                            <h5 class="section-title">Riwayat Penilaian & Pengangkutan Anda - <span class="text-success">
                                    Periode
                                    {{ !empty(request()->bulan) && !empty(request()->tahun) ? ucWords(strtolower(request()->bulan)) . ' ' . request()->tahun : \Carbon\Carbon::now()->isoFormat('MMMM Y') }}</span>
                            </h5>
                            <div class="card card-table">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-stripped nowrap table-hover yajra-datatables-1"
                                            id="yajra-datatables-1">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Tanggal Dinilai/Diangkut</th>
                                                    <th>Dinilai & Diangkut Oleh</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pemasukan --}}
                <div class="row">
                    <div class="col-sm-12">
                        <h5 class="section-title mt-3">Pemilah Aktif Terpilih -<span class="text-success">
                                Periode
                                {{ !empty(request()->bulan) && !empty(request()->tahun) ? ucWords(strtolower(request()->bulan)) . ' ' . request()->tahun : \Carbon\Carbon::now()->isoFormat('MMMM Y') }}</span>
                        </h5>
                        <div class="card card-table">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-center table-hover  yajra-datatables-2"
                                        id="yajra-datatables-2">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Peringkat Rekomendasi</th>
                                                <th>No Member</th>
                                                <th>Nama Pelanggan atau Nasabah</th>
                                                <th>Total Point</th>
                                                <th>Alasan Terpilih</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- End of Riwayat Transaksi --}}
            </div>
        </div>
    </div>
@endsection
@section('custom-script')
    <script>
        $(document).ready(function() {
            var table = $('#balance-table').DataTable({
                responsive: true,
                paging: true
            });
        });
    </script>
@endsection
