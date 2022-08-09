@extends('admin.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Master Data, Konfigurasi Sistem')
@section('meta-description', 'Data Master Data, Konfigurasi Sistem')
@section('meta-keyword', 'Master Data, Konfigurasi Sistem')
{{-- End Meta --}}
@section('title', 'Master - Konfigurasi Sistem')
@section('footer')
    <script type="text/javascript">
        $(function() {
            var table = $('.yajra-datatables').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('konfigurasi.kriteria.getAll') }}"
                },
                columns: [{
                        data: null,
                        name: 'action',
                        orderable: false,
                        searchable: true,
                        render: function(o) {
                            var deleteField = "{{ route('konfigurasi.kriteria.destroy', '') }}" +
                                "/" +
                                o.id;
                            var updateField = "{{ route('konfigurasi.kriteria.ubah', '') }}" +
                                "/" +
                                o.id;
                            return `
                            <form id="delete-` + o.id + `" action="` + deleteField + `" method="POST">
                                    @method('DELETE')
                                    @csrf
                                <div class="actions">
                                    <a href="` + updateField + `" data-toggle="tooltip" data-placement="top" title="Ubah Kriteria Penilaian" 
                                        data-original-title="Edit">
                                            <i class="icon-edit1 text-info"></i>
                                    </a>
                                    <button type="button" onclick="deleteButton('` + o.nama_kriteria + `','` + o.id + `')"  data-toggle="tooltip"
                                        data-placement="top" title="Hapus Kriteria Penilaian "
                                        class="btn btn-link text-decoration-none ps-2 pb-2">
                                        <i class="icon-trash text-danger"></i>
                                    </button>
                                </div>
                            </form>
                            `;
                        }
                    },
                    {
                        data: 'nama_kriteria',
                        name: 'nama_kriteria'
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

                <h4 class="mb-3">Master - Konfigurasi Dasar</h4>
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
                <!-- Card start -->
                <div class="card">
                    <div class="card-header-lg">
                        <h4 class="d-flex align-items-center"><i class="icon-settings1 icon-large me-3"></i>Total Konfigurasi
                            Sistem Aktif
                            ({{ $all_config->where('status', 'active')->count() }}), Kriteria Penilaian
                            ({{ $kriteria->count() }})</h4>
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end">
                                    <div class="custom-btn-group">
                                        <a href="{{ route('konfigurasi') }}" class="btn btn-primary rounded"
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
                                <h5 class="mt-3">Konfigurasi System</h5>
                            </div>
                        </div>
                        <div class="table-responsive mt-4">
                            <table id="highlightRowColumn" class="table custom-table">
                                <thead>
                                    <tr>
                                        <th>Aksi</th>
                                        <th>Nama Konfigurasi</th>
                                        <th>Value</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($all_config as $c)
                                        <tr>
                                            <td>
                                                <div class="actions">
                                                    <a href="#" data-toggle="tooltip" data-placement="top"
                                                        title="Ubah Konfigurasi" data-bs-toggle="modal"
                                                        data-bs-target="#exampleModal-{{ $c->id }}"
                                                        data-original-title="Edit">
                                                        <i class="icon-edit1 text-info"></i>
                                                    </a>

                                                </div>
                                            </td>
                                            <td>
                                                {{ $c->name }}
                                            </td>
                                            <td>
                                                @if ($c->key == 'hari-penilaian')
                                                    {{ $c->value }} Hari
                                                @elseif($c->key == 'minimal-penilaian')
                                                    {{ $c->value }} Kali
                                                @elseif($c->key == 'unpublish-time')
                                                    {{ $c->value }} Menit
                                                @else
                                                    @currency($c->value)
                                                @endif
                                            </td>
                                            <td>
                                                @if ($c->status == 'active')
                                                    <span class="text-success">Aktif</span>
                                                @else
                                                    <span class="text-danger">Tidak Aktif</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Card end -->

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <h5 class="mt-3">Konfigurasi Kriteria Penilaian</h5>
                            </div>
                            <div class="col-6">
                                <div class="d-flex justify-content-end">
                                    <div class="custom-btn-group">
                                        <a href="{{ route('konfigurasi.kriteria.tambah') }}"
                                            class="btn btn-primary rounded" type="button">
                                            + Tambah</a>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="table-responsive mt-4">
                            <table id="yajra-datatables" class="table yajra-datatables">
                                <thead>
                                    <tr>
                                        <th style="width: 15%">Aksi</th>
                                        <th>Nama Konfigurasi Kriteria</th>
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
    <!-- Row end -->

    @foreach ($all_config as $c)
        <!-- Modal start -->
        <div class="modal fade" id="exampleModal-{{ $c->id }}" tabindex="-1"
            aria-labelledby="exampleModal-{{ $c->id }}Label" aria-hidden="true">
            <div class="modal-dialog">

                <form action="{{ route('konfigurasi.update', [$c->id]) }}" method="POST">
                    @csrf
                    @method('patch')
                    <div class="modal-content">

                        <div class="modal-header bg-success">
                            <h5 class="modal-title" id="exampleModal-{{ $c->id }}Label">Ubah
                                Konfigurasi
                                {{ $c->name }}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="text-danger mb-3 d-flex justify-content-end">* Wajib Diisi</div>

                            <div class="mb-3">
                                <label for="value" class="form-label">Setel
                                    Value
                                    {{ $c->name }} {{ $c->key == 'unpublish-time' ? '(Dalam Menit)' : '' }}<span
                                        class="text-danger">*</span></label>
                                <input type="number" min="0" required
                                    onKeyPress="if(this.value.length==10) return false;" name="value" id="value"
                                    class="form-control" value="{{ $c->value }}">
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Setel
                                    Status
                                    {{ $c->name }}<span class="text-danger">*</span></label>
                                <select name="status" required id="status" class="form-control">
                                    <option value="active" {{ $c->status == 'active' ? 'selected' : '' }}>
                                        Aktif</option>
                                    <option value="deactive" {{ $c->status == 'deactive' ? 'selected' : '' }}>
                                        Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Perbaharui</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Modal end -->
    @endforeach
@endsection
