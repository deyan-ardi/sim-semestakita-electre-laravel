@extends('admin.layouts.kasir')
{{-- Meta --}}
@section('meta-name', 'Pengangkutan dan Penilaian Harian, Hasil Scan')
@section('meta-description', 'Data Pengangkutan dan Penilaian Harian, Hasil Scan')
@section('meta-keyword', 'Pengangkutan dan Penilaian Harian, Hasil Scan')
{{-- End Meta --}}
@section('title', 'Pengangkutan dan Penilaian Harian - Hasil Scan ~ ' . $find->no_member)

@section('content')

    <!-- Content wrapper start -->
    <div class="content-wrapper">

        <!-- Row start -->
        <div class="row gutters">
            <div class="col-xl-12">
                <!-- Card start -->
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('pengangkutan-penilaian') }}" class="btn btn-danger mb-3"><i
                                class="icon-arrow-left"></i>Kembali</a>
                    </div>
                </div>
                <!-- Card end -->

                <!-- Card start -->
                <div class="card">
                    <div class="card-header-lg">
                        <h4>Scan Kode QR Penangkutan dan Penilaian Harian</h4>
                    </div>
                    <div class="card-body">
                        <div class="card-body">

                            <!-- Row start -->
                            <div class="row justify-content-between">

                                <div class="col-xl-5 col-lg-5 col-md-5 col-sm-5 col-12">
                                    <div class="row justify-content-center gutters mt-2">
                                        <div class=" col-lg-7">
                                            <img src="{{ asset($find->foto) }}" class="img-fluid" alt="">
                                            <!-- Row start -->
                                        </div>
                                        <div class="col-12 mb-4 mt-2">
                                            <a href="{{ route('pengangkutan-penilaian.scan') }}"><button
                                                    class="btn-success col-12 btn">Pindai Ulang</button></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-7 col-lg-7 col-md-7 col-sm-7 col-12">
                                    <div class="row gutters">
                                        <div class="col-12">
                                            <div class="form-section-header light-bg">Data Hasil Scan Kode QR</div>
                                        </div>
                                        <div class="col-12">
                                            <!-- Field wrapper start -->
                                            <div class="field-wrapper">
                                                <input class="form-control" disabled type="text"
                                                    value="{{ $find->name }}">
                                                <div class="field-placeholder">Nama Pelanggan</div>
                                            </div>
                                            <!-- Field wrapper end -->
                                        </div>
                                        <div class="col-12">
                                            <!-- Field wrapper start -->
                                            <div class="field-wrapper">
                                                <div class="input-group">
                                                    <input type="text" disabled value="{{ $find->no_member }}"
                                                        class="form-control">
                                                </div>
                                                <div class="field-placeholder">Nomor Member</div>
                                            </div>
                                            <!-- Field wrapper end -->
                                        </div>
                                        <div class="col-12">
                                            <!-- Field wrapper start -->
                                            <div class="field-wrapper">
                                                <div class="input-group">
                                                    <input type="text" class="form-control"
                                                        value="+62{{ !empty($find->no_telp) ? $find->no_telp : 'Belum Disetel' }}"
                                                        disabled>
                                                </div>
                                                <div class="field-placeholder">Nomor Telepon</div>
                                            </div>
                                            <!-- Field wrapper end -->
                                        </div>
                                        <div class="col-12">
                                            <!-- Field wrapper start -->
                                            <div class="field-wrapper">
                                                <div class="input-group">
                                                    <input type="text" class="form-control"
                                                        value="{{ !empty($find->alamat) ? $find->alamat : 'Belum Disetel' }}"
                                                        disabled>
                                                </div>
                                                <div class="field-placeholder">Alamat</div>
                                            </div>
                                            <!-- Field wrapper end -->
                                        </div>
                                        <div class="col-12">
                                            <!-- Field wrapper start -->
                                            <div class="field-wrapper">
                                                <div class="input-group">
                                                    <input type="text" class="form-control"
                                                        value="{{ \Carbon\Carbon::now()->format('d F Y H:i') }} WITA"
                                                        disabled>
                                                </div>
                                                <div class="field-placeholder">Waktu dan Tanggal Pengangkutan</div>
                                            </div>
                                            <!-- Field wrapper end -->
                                        </div>
                                    </div>

                                </div>

                            </div>
                            <!-- Row end -->
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <p>Kesalahan input terdeteksi:</p>
                                    <p>Terdapat kriteria penilaian yang belum diisi, silahkan cek kembali form input anda
                                    </p>
                                </div>
                            @endif
                            <form id="save-{{ $find->id }}"
                                action="{{ route('pengangkutan-penilaian.scan.result.process', $find->id) }}"
                                method="POST">
                                @csrf
                                <div class="row justify-content-between mt-3">
                                    <div class="row gutters">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th class="pt-3 pb-3">Kriteria Penilaian
                                                                Harian - Periode
                                                                {{ \Carbon\Carbon::now()->format('F Y') }}</th>
                                                            <th class="pt-3 pb-3">Opsi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if ($get_all->count() > 0)
                                                            @foreach ($get_all as $index => $item)
                                                                <tr>
                                                                    <td>
                                                                        <h6 class="mt-2">
                                                                            {{ $item->urutan }}.
                                                                            {{ $item->nama_kriteria }}
                                                                        </h6>
                                                                        <input type="hidden" name="id_kriteria[]" multiple
                                                                            value="{{ $item->id }}">
                                                                    </td>
                                                                    <td>
                                                                        <div class="d-flex">
                                                                            <div class="form-check">
                                                                                <input multiple class="form-check-input"
                                                                                    type="radio" required
                                                                                    name="nilai_kriteria[][{{ $item->id }}]"
                                                                                    id="opsi-iya-{{ $item->id }}"
                                                                                    value="iya"
                                                                                    {{ !empty(old('nilai_kriteria')[$index][$item->id]) && old('nilai_kriteria')[$index][$item->id] == 'iya' ? 'checked' : '' }}>
                                                                                <label class="form-check-label"
                                                                                    for="opsi-iya-{{ $item->id }}">
                                                                                    Iya, Melakukan
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <input multiple class="form-check-input"
                                                                                    type="radio" required
                                                                                    name="nilai_kriteria[][{{ $item->id }}]"
                                                                                    id="opsi-tidak-{{ $item->id }}"
                                                                                    value="tidak"
                                                                                    {{ !empty(old('nilai_kriteria')[$index][$item->id]) && old('nilai_kriteria')[$index][$item->id] == 'tidak' ? 'checked' : '' }}>
                                                                                <label class="form-check-label"
                                                                                    for="opsi-tidak-{{ $item->id }}">
                                                                                    Tidak Dilakukan
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @else
                                                            <tr>
                                                                <td>
                                                                    <div class="alert alert-danger">Belum Ada Data Kriteria
                                                                        Penilaian
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    </tbody>
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
                                                    <a href="#"
                                                        data-redirect="{{ route('pengangkutan-penilaian.scan.result', $find->id) }}"
                                                        class="btn btn-danger mt-2 confirm-cancel">Kosongkan Form</a>
                                                    <button type="submit" id="submit_btn"
                                                        class="btn btn-primary mt-2 ms-1 confirm-save"
                                                        data-formid="{{ $find->id }}">Simpan</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Card end -->
            </div>
        </div>
        <!-- Row end -->
    </div>
@endsection
