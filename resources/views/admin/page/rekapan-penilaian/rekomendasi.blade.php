@extends('admin.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Rekomendasi Pemilah Aktif')
@section('meta-description', 'Data Rekomendasi Pemilah Aktif')
@section('meta-keyword', 'Rekomendasi Pemilah Aktif')
{{-- End Meta --}}
@section('title', 'Rekomendasi Pemilah Aktif')
@section('header')
    <style>
        #exampleModal {
            /* create z-index 9999 */
            z-index: 999999999;
        }
    </style>
@endsection
@section('footer')
    <script type="text/javascript">
        const deleteValidation = () => {

            Swal.fire({
                    title: 'Apakah Anda Yakin Ingin Mengosongkan Pemilah Aktif?',
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
        const requestImportData = (bulan, tahun) => {
            $.ajax({
                url: "{{ route('rekapan-penilaian.rekomendasi.pemenang.list.rekomendasi') }}",
                method: 'POST',
                dataType: 'html',
                data: {
                    bulan: bulan,
                    tahun: tahun,
                    _token: "{{ csrf_token() }}",
                },
                beforeSend: function() {
                    $('#loadedimport').show();
                },
                complete: function() {
                    $('#loadedimport').hide();
                },
                success: function(result) {
                    $('#modal-body-import').empty()
                    $('#modal-body-import').append(result);
                },
                error: function(result) {
                    alert(result.responseText);
                }
            });
        }
        $(function() {
            var table = $('.yajra-datatables').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('rekapan-penilaian.rekomendasi.getAll') }}",
                    data: function(d) {
                        d.bulan = "{{ request()->bulan }}",
                            d.tahun = "{{ request()->tahun }}"
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
                    },

                ],
                columnDefs: [{
                    targets: [0],
                    orderable: false,
                }]
            });
        });

        $(function() {
            var table = $('.yajra-datatables2').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('rekapan-penilaian.rekomendasi.pemenang.getAll') }}",
                    data: function(d) {
                        d.bulan = "{{ request()->bulan }}",
                            d.tahun = "{{ request()->tahun }}"
                    },

                },
                columns: [{
                        data: 'no_member',
                        name: 'no_member'
                    },
                    {
                        data: 'user',
                        name: 'user'
                    },
                    {
                        data: 'ranking',
                        name: 'ranking'
                    },
                    {
                        data: 'hasil_electre',
                        name: 'hasil_electre',
                        render: function(o){
                            return o + " Point";
                        }
                    },
                    {
                        data: 'alasan',
                        name: 'alasan',
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
                <h4 class="mb-3">Pemilihan Pemilah Aktif - <span class="text-success">Periode
                        {{ \Carbon\Carbon::parse(request()->bulan . ' ' . request()->tahun)->format('F Y') }}</span></h4>
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
                        <div>
                            <h4 class="d-flex align-items-center"><i class="icon-award icon-large me-2"></i>Jumlah
                                Rekomendasi ({{ $rekomendasi }}), Jumlah Terpilih ({{ $pemilah_aktif->count()  }})</h4>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end">
                                    <div class="custom-btn-group">
                                        <form action="{{ route('rekapan-penilaian.rekomendasi') }}" method="GET">
                                            <input type="hidden" name="bulan" required
                                                value="{{ !empty(request()->bulan) ? request()->bulan : \Carbon\Carbon::now()->format('F') }}">
                                            <input type="hidden" name="tahun" required
                                                value="{{ !empty(request()->tahun) ? request()->tahun : \Carbon\Carbon::now()->format('Y') }}">
                                            <button type="submit" class="btn btn-primary rounded" type="button">
                                                <span class="icon-refresh refresh"></span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Card start -->
                <div class="alert alert-info">Peringkat rekomendasi menandakan bahwa dari sekian alternatif yang
                    ditawarkan,
                    peringkat tertinggi merupakan yang paling direkomendasikan menjadi pemilah aktif pada
                    Periode
                    {{ \Carbon\Carbon::parse(request()->bulan . ' ' . request()->tahun)->format('F Y') }}</div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <h5 class="mt-3">Daftar Rekomendasi</h5>
                            </div>
                            <div class="col-6">
                                <div class="d-flex justify-content-end">
                                    <div class="custom-btn-group">
                                        <a class="btn btn-success rounded" href="javascript:void(0)" data-bs-toggle="modal"
                                            data-bs-target="#exampleModal2"><span class="icon-list"></span> Kriteria
                                            Penilaian</a>
                                        @if (Auth::user()->role == 1)
                                            <a class="btn btn-warning rounded" href="javascript:void(0)"
                                                data-bs-toggle="modal" data-bs-target="#exampleModal"><span
                                                    class="icon-database"></span> Proses
                                                Perhitungan</a>
                                        @endif

                                        <form action="{{ route('rekapan-penilaian.rekomendasi.export') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="bulan" required
                                                value="{{ !empty(request()->bulan) ? request()->bulan : \Carbon\Carbon::now()->format('F') }}">
                                            <input type="hidden" name="tahun" required
                                                value="{{ !empty(request()->tahun) ? request()->tahun : \Carbon\Carbon::now()->format('Y') }}">
                                            <button type="submit" class="btn btn-info rounded" type="button">
                                                <span class="icon-print"></span> Export</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive mt-4">
                            <table id="yajra-datatables" class="table yajra-datatables">
                                <thead>
                                    <tr>
                                        <th style="width: 15%">Peringkat Rekomendasi</th>
                                        <th>No Member</th>
                                        <th>Nama Pelanggan/Nasabah</th>
                                        <th>Hasil Electre</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Card end -->
                <div class="alert alert-info">Berdasarkan rekomendasi yang diberikan, silahkan pilih pemilah aktif yang
                    menurut anda cocok untuk
                    {{ \Carbon\Carbon::parse(request()->bulan . ' ' . request()->tahun)->format('F Y') }}</div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <h5 class="mt-3">Pemilah Aktif Terpilih</h5>
                            </div>
                            <div class="col-6">
                                <div class="d-flex justify-content-end">
                                    <div class="custom-btn-group">
                                        @if (Auth::user()->role != 3 && Auth::user()->role != 6)
                                            @if ($pemilah_aktif->count() > 0)
                                                @if ($pemilah_aktif->where('publish', '0')->count() > 0)
                                                    <form
                                                        action="{{ route('rekapan-penilaian.rekomendasi.pemenang.publish') }}"
                                                        method="post" id="publish-form">
                                                        @csrf
                                                        <input type="hidden" name="bulan"
                                                            value="{{ !empty(request()->bulan) ? request()->bulan : \Carbon\Carbon::now()->format('F') }}">
                                                        <input type="hidden" name="tahun"
                                                            value="{{ !empty(request()->tahun) ? request()->tahun : \Carbon\Carbon::now()->format('Y') }}">
                                                        <button type="button" onclick="publishValidation()"
                                                            class="btn btn-secondary">
                                                            <i class="icon-upload"></i>
                                                            Publish Pemenang
                                                        </button>
                                                    </form>
                                                    <form
                                                        action="{{ route('rekapan-penilaian.rekomendasi.pemenang.destroy') }}"
                                                        id="delete-form" method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="bulan"
                                                            value="{{ !empty(request()->bulan) ? request()->bulan : \Carbon\Carbon::now()->format('F') }}">
                                                        <input type="hidden" name="tahun"
                                                            value="{{ !empty(request()->tahun) ? request()->tahun : \Carbon\Carbon::now()->format('Y') }}">
                                                        <button type="button" onclick="deleteValidation()"
                                                            class="btn btn-danger">
                                                            <i class="icon-trash"></i>
                                                            Kosongkan Pemenang
                                                        </button>
                                                    </form>
                                                    <form
                                                        action="{{ route('rekapan-penilaian.rekomendasi.pemenang.export') }}"
                                                        method="post">
                                                        @csrf
                                                        <input type="hidden" name="bulan" required
                                                            value="{{ !empty(request()->bulan) ? request()->bulan : \Carbon\Carbon::now()->format('F') }}">
                                                        <input type="hidden" name="tahun" required
                                                            value="{{ !empty(request()->tahun) ? request()->tahun : \Carbon\Carbon::now()->format('Y') }}">
                                                        <button type="submit" class="btn btn-info rounded" type="button">
                                                            <span class="icon-print"></span> Export</button>
                                                    </form>
                                                @elseif ($pemilah_aktif->where('publish', '1')->count() > 0)
                                                    @if ($configUnpublish->status == 'active')
                                                        @if (\Carbon\Carbon::parse($pemilah_aktif->where('publish', '1')->first()->updated_at)->addMinute($configUnpublish->value)->format('Y-m-d H:i:s') >= \Carbon\Carbon::now()->format('Y-m-d H:i:s'))
                                                            <form
                                                                action="{{ route('rekapan-penilaian.rekomendasi.pemenang.unpublish') }}"
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

                                                    <form
                                                        action="{{ route('rekapan-penilaian.rekomendasi.pemenang.export') }}"
                                                        method="post">
                                                        @csrf
                                                        <input type="hidden" name="bulan" required
                                                            value="{{ !empty(request()->bulan) ? request()->bulan : \Carbon\Carbon::now()->format('F') }}">
                                                        <input type="hidden" name="tahun" required
                                                            value="{{ !empty(request()->tahun) ? request()->tahun : \Carbon\Carbon::now()->format('Y') }}">
                                                        <button type="submit" class="btn btn-info rounded" type="button">
                                                            <span class="icon-print"></span> Export</button>
                                                    </form>

                                                @endif
                                            @else
                                                <a class="btn btn-success rounded" href="javascript:void(0)"
                                                    onclick="requestImportData('{{ request()->bulan }}','{{ request()->tahun }}')"
                                                    data-bs-toggle="modal" data-bs-target="#exampleModal3">+ Import
                                                    Pemenang</a>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive mt-4">
                            <table id="yajra-datatables2" class="table yajra-datatables2">
                                <thead>
                                    <tr>
                                        <th>No Member</th>
                                        <th>Nama Pelanggan atau Nasabah</th>
                                        <th style="width: 15%">Peringkat Rekomendasi</th>
                                        <th>Total Point</th>
                                        <th>Alasan Dipilih</th>
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
    </div>


    </div>
    <!-- Row end -->
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
                        <div class="table-responsive mt-4">
                            <table class="table custom-table">
                                <thead class="table-success">
                                    <tr>
                                        <th>Kode Kriteria</th>
                                        <th>Nama Kriteria</th>
                                        <th>Nilai Bobot (%)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($querykriteria as $index => $item)
                                        <tr>
                                            <td>K{{ $index + 1 }}</td>
                                            <td>
                                                {{ $item->nama_kriteria }}
                                            </td>
                                            <td>{{ $item->bobot }}%</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Modal start -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" style="min-width:100%">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title" id="exampleModalLabel">Proses Perhitungan Rekomendasi Dengan ELECTRE - <span
                            class="text-white">Periode
                            {{ \Carbon\Carbon::parse(request()->bulan . ' ' . request()->tahun)->format('F Y') }}</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="overflow-y: auto;">
                    <div class="row justify-content-center">
                        <div class="table-responsive mt-4">
                            <div class="col-lg-12 mb-4 text-center">
                                <div class="alert alert-success">1. Normalisasi Bobot Kriteria</div>
                                <table class="table">
                                    <tbody>
                                        @foreach ($querykriteria as $index => $kriteria)
                                            <td>K{{ $index + 1 }} ({{ $kriteria->bobot }} %)</td>
                                        @endforeach
                                    </tbody>
                                </table>
                                <table class="table">
                                    <tbody>
                                        @foreach ($querykriteria as $index => $kriteria)
                                            <td>{{ $kriteria->bobot / 100 }}</td>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="table-responsive mt-4">
                            <div class="col-lg-12 mt-4 text-center">
                                <div class="alert alert-success">2. Data Alternatif Kriteria</div>
                                <table class="table">
                                    <tbody>
                                        @for ($i = 0; $i < count($alternatifkriteria); $i++)
                                            <tr>
                                                @for ($j = 0; $j < count($alternatifkriteria[$i]); $j++)
                                                    <td> {{ $alternatifkriteria[$i][$j] }}</td>
                                                @endfor
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="table-responsive mt-4">
                            <div class="col-lg-12 mt-4 text-center">
                                <div class="alert alert-success">3. Pembagi</div>
                                <table class="table">

                                    <tbody>
                                        <tr>
                                            @for ($j = 0; $j < count($pembagi); $j++)
                                                <td> {{ $pembagi[$j] }}</td>
                                            @endfor
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="table-responsive mt-4">
                            <div class="col-lg-12 mt-4 text-center">
                                <div class="alert alert-success">4. Matriks Normalisasi (R)</div>
                                <table class="table">
                                    <tbody>
                                        @for ($i = 0; $i < count($normalisasi); $i++)
                                            <tr>
                                                @for ($j = 0; $j < count($normalisasi[$i]); $j++)
                                                    <td> {{ $normalisasi[$i][$j] }}</td>
                                                @endfor
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="table-responsive mt-4">
                            <div class="col-lg-12 mt-4 text-center">
                                <div class="alert alert-success">5. Matriks Hasil Pembobotan (V)</div>
                                <table class="table">
                                    <tbody>
                                        @for ($i = 0; $i < count($V); $i++)
                                            <tr>
                                                @for ($j = 0; $j < count($V[$i]); $j++)
                                                    <td> {{ $V[$i][$j] }}</td>
                                                @endfor
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="table-responsive mt-4">
                            <div class="col-lg-12 mt-4 text-center">
                                <div class="alert alert-success">6. Himpunan Concordance Index</div>
                                <table class="table">
                                    <tbody>
                                        @for ($i = 0; $i < count($concordance); $i++)
                                            <tr>
                                                @for ($j = 0; $j < count($concordance[$i]); $j++)
                                                    <td> {{ $concordance[$i][$j] }}</td>
                                                @endfor
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="table-responsive mt-4">
                            <div class="col-lg-12 mt-4 text-center">
                                <div class="alert alert-success">7. Himpunan Discordance Index</div>
                                <table class="table">
                                    <tbody>
                                        @for ($i = 0; $i < count($discordance); $i++)
                                            <tr>
                                                @for ($j = 0; $j < count($discordance[$i]); $j++)
                                                    <td> {{ $discordance[$i][$j] }}</td>
                                                @endfor
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="table-responsive mt-4">

                            <div class="col-lg-12 mt-4 text-center">
                                <div class="alert alert-success">8. Matriks Concordance</div>
                                <table class="table">
                                    <tbody>
                                        @for ($i = 0; $i < count($matriks_concordance); $i++)
                                            <tr>
                                                @for ($j = 0; $j < count($matriks_concordance[$i]); $j++)
                                                    <td> {{ $matriks_concordance[$i][$j] }}</td>
                                                @endfor
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="table-responsive mt-4">
                            <div class="col-lg-12 mt-4 text-center">
                                <div class="alert alert-success">9. Matriks Discordance</div>
                                <table class="table">
                                    <tbody>
                                        @for ($i = 0; $i < count($matriks_discordance); $i++)
                                            <tr>
                                                @for ($j = 0; $j < count($matriks_discordance[$i]); $j++)
                                                    <td> {{ $matriks_discordance[$i][$j] }}</td>
                                                @endfor
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-lg-12 mt-4 text-center">
                            <div class="alert alert-success">10. Nilai Treshold Matriks Concordance</div>
                            <p>{{ $treshold_matriks_concordance }}</p>
                        </div>
                        <div class="col-lg-12 mt-4 text-center">
                            <div class="alert alert-success">11. Nilai Treshold Matriks Discordance</div>
                            <p>{{ $treshold_matriks_discordance }}</p>
                        </div>

                        <div class="table-responsive mt-4">
                            <div class="col-lg-12 mt-4 text-center">
                                <div class="alert alert-success">12. Matriks Domain Concordance (F)</div>
                                <table class="table">
                                    <tbody>
                                        @for ($i = 0; $i < count($matriks_dominan_concordance); $i++)
                                            <tr>
                                                @for ($j = 0; $j < count($matriks_dominan_concordance[$i]); $j++)
                                                    <td> {{ $matriks_dominan_concordance[$i][$j] }}</td>
                                                @endfor
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="table-responsive mt-4">
                            <div class="col-lg-12 mt-4 text-center">
                                <div class="alert alert-success">13. Matriks Domain Discordance (G)</div>
                                <table class="table">
                                    <tbody>
                                        @for ($i = 0; $i < count($matriks_dominan_discordance); $i++)
                                            <tr>
                                                @for ($j = 0; $j < count($matriks_dominan_discordance[$i]); $j++)
                                                    <td> {{ $matriks_dominan_discordance[$i][$j] }}</td>
                                                @endfor
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="table-responsive mt-4">
                            <div class="col-lg-12 mt-4 text-center">
                                <div class="alert alert-success">14. Aggregate Dominance Matriks (E)</div>
                                <table class="table">
                                    <tbody>
                                        @for ($i = 0; $i < count($agregate_dominance_matrix); $i++)
                                            <tr>
                                                @for ($j = 0; $j < count($agregate_dominance_matrix[$i]); $j++)
                                                    <td> {{ $agregate_dominance_matrix[$i][$j] }}</td>
                                                @endfor
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-12 mt-4 text-center">
                            <div class="alert alert-success">15. Kesimpulan</div>
                            <p>Matriks E memberikan urutan pilihan dari setiap
                                alternatif, yaitu bila Ekl = 1 alternatif Ak merupakan alternatif yang lebih baik
                                dari alternatif lainnya. Oleh sebab itu, baris dalam matriks E yang memiliki jumlah
                                Ekl= 1 paling sedikit dapat dieliminasi. Berdasarkan hal tersebut rekomendasi
                                pemilah aktif dapat dilihat pada Tabel Daftar Rekomendasi</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal end -->
    <!-- Modal start -->
    @if (Auth::user()->role != 3 && Auth::user()->role != 6)
        <div class="modal fade" id="exampleModal3" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <form action="{{ route('rekapan-penilaian.rekomendasi.pemenang.proses') }}" method="POST">
                    @csrf
                    <div class="modal-content" id="modal-body-import">
                        <div id="loadedimport" class="modal-body">Loading Data....</div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Modal end -->
    @endif
@endsection
