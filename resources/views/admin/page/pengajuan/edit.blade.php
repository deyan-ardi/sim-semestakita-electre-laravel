@extends('admin.layouts.app')

{{-- Meta --}}
@section('meta-name', 'Daftar Permintaan Pengangkutan Sampah')
@section('meta-description', 'Data Daftar Permintaan Pengangkutan Sampah')
@section('meta-keyword', 'Daftar Permintaan Pengangkutan Sampah')
{{-- End Meta --}}
@section('title', 'Daftar Permintaan Pengangkutan Sampah - Ubah Data')
@section('content')

    <!-- Content wrapper start -->
    <div class="content-wrapper">

        <!-- Row start -->
        <div class="row gutters">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
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
                    <div class="card-header">
                        <div class="card-title mb-3">
                            Ubah Data Penjemputan Sampah
                        </div>
                    </div>
                    <div class="card-body">
                        {{-- Form Start --}}
                        <div class="text-danger mb-3 d-flex justify-content-end">* Wajib Diisi</div>
                        <form action="{{ route('penjemputan.update', [$edit->id]) }}" method="POST">
                            @csrf
                            @method('patch')
                            <div class="row gutters mb-3">
                                <label class="col-sm-2 col-form-label">Tanggal Penjemputan <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input name="date" type="date" value="{{ old('date') ?? $edit->tanggal }}"
                                        class="form-control " required>

                                </div>
                            </div>
                            <div class="row gutters mb-3">
                                <label class="col-sm-2 col-form-label">Nama Pelanggan <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input name="name" style="text-transform: capitalize"
                                        value="{{ old('name') ?? $edit->nama_pelanggan }}" type="text"
                                        class="form-control " required>

                                </div>
                            </div>
                            <div class="row gutters mb-3">
                                <label class="col-sm-2 col-form-label">No. Telepon <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input name="no_telp" type="number" onKeyPress="if(this.value.length==15) return false;"
                                        minlength="9" maxlength="15" class="form-control "
                                        value="0{{ old('no_telp') ?? $edit->kontak_pelanggan }}" required>

                                </div>
                            </div>
                            <div class="row gutters mb-3">
                                <label class="col-sm-2 col-form-label">Alamat Asal <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input name="alamat" style="text-transform: capitalize" type="text"
                                        class="form-control " value="{{ old('alamat') ?? $edit->alamat_pelanggan }}"
                                        required>

                                </div>
                            </div>
                            <div class="row gutters mb-3">
                                <label class="col-sm-2 col-form-label">Lokasi Ambil Sampah <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input name="ambil" style="text-transform: capitalize" type="text"
                                        class="form-control " value="{{ old('ambil') ?? $edit->lokasi_ambil }}" required>

                                </div>
                            </div>
                            <div class="row gutters mb-3">
                                <label class="col-sm-2 col-form-label">Jarak <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <input name="jarak" type="number" step="0.01" class="form-control "
                                            value="{{ old('jarak') ?? $edit->jarak }}" required>

                                        <span class="input-group-text">
                                            Km
                                        </span>
                                    </div>
                                    @if ($config->status == 'active')
                                        <small>Biaya layanan yang dikenakan untuk setiap 1 Km adalah
                                            @currency($config->value). Untuk mengatur biaya layanan,
                                            kunjungi fitur master data</small>
                                    @endif
                                </div>
                            </div>
                            @if ($config->status == 'deactive')
                                <div class="row gutters mb-3">
                                    <label class="col-sm-2 col-form-label">Biaya Jasa <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                Rp
                                            </span>
                                            <input name="biaya" type="number"
                                                onKeyPress="if(this.value.length==10) return false;" class="form-control "
                                                value="{{ old('biaya') ?? $edit->biaya }}" required>

                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-sm-10 offset-sm-2">
                                    <div class="form-actions-footer">
                                        <a href="{{ route('penjemputan') }}" class="btn btn-danger">Kembali</a>
                                        <button type="submit" class="btn btn-primary">Perbaharui</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        {{-- Form end --}}
                    </div>
                </div>
                <!-- Card end -->

            </div>
        </div>
        <!-- Row end -->
    </div>
@endsection
