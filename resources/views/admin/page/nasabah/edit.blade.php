@extends('admin.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Master Data, Ubah Nasabah')
@section('meta-description', 'Data Master Data, Ubah Nasabah')
@section('meta-keyword', 'Master Data, Ubah Nasabah')
{{-- End Meta --}}
@section('title', 'Master - Ubah Nasabah')
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
                            Edit Data Nasabah
                        </div>
                    </div>
                    <div class="card-body">
                        {{-- Form Start --}}
                        <div class="text-danger mb-3 d-flex justify-content-end">* Wajib Diisi</div>
                        <form action="{{ route('nasabah.update', [$nasabah->id]) }}" method="POST">
                            @method('PUT')
                            @csrf
                            <div class="row gutters mb-3">
                                <label class="col-sm-2 col-form-label">No. Member <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input name="no_member" style="text-transform: uppercase" type="text"
                                        class="form-control " value="{{ old('no_member') ?? $nasabah->no_member }}"
                                        required>

                                </div>
                            </div>
                            <div class="row gutters mb-3">
                                <label class="col-sm-2 col-form-label">Nama <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input name="name" maxlength="100" style="text-transform: capitalize" type="text"
                                        class="form-control " value="{{ old('name') ?? $nasabah->name }}" required>

                                </div>
                            </div>
                            <div class="row gutters mb-3">
                                <label class="col-sm-2 col-form-label">No. Telepon <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input name="no_telp" minlength="8" maxlength="15" type="number"
                                        onKeyPress="if(this.value.length==15) return false;" class="form-control"
                                        value="{{ old('no_telp') ?? $nasabah->no_telp }}" required>

                                </div>
                            </div>
                            <div class="row gutters mb-3">
                                <label class="col-sm-2 col-form-label">No. Rekening <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input name="no_rekening" type="number" minlength="10" maxlength="15"
                                        onKeyPress="if(this.value.length==25) return false;" class="form-control "
                                        value="{{ old('no_rekening') ? old('no_rekening') : (!empty($nasabah->no_rekening) ? $nasabah->no_rekening : '') }}"
                                        required>
                                </div>
                            </div>
                            <div class="row gutters mb-3">
                                <label class="col-sm-2 col-form-label">Nama Bank <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <select name="nama_bank" class="form-control " required>
                                        <option value="">Silahkan Pilih Bank</option>
                                        <option value="BRI"
                                            {{ $nasabah->nama_bank == 'BRI' || old('nama_bank') == 'BRI' ? 'selected' : '' }}>
                                            BRI
                                        </option>
                                        <option value="BCA"
                                            {{ $nasabah->nama_bank == 'BCA' || old('nama_bank') == 'BCA' ? 'selected' : '' }}>
                                            BCA
                                        </option>
                                        <option value="BNI"
                                            {{ $nasabah->nama_bank == 'BNI' || old('nama_bank') == 'BNI' ? 'selected' : '' }}>
                                            BNI
                                        </option>
                                        <option value="Mandiri"
                                            {{ $nasabah->nama_bank == 'Mandiri' || old('nama_bank') == 'Mandiri' ? 'selected' : '' }}>
                                            Mandiri</option>
                                    </select>

                                </div>
                            </div>
                            <div class="row gutters mb-3">
                                <label class="col-sm-2 col-form-label">Email <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input name="email" style="text-transform: lowercase" type="email"
                                        class="form-control" value="{{ old('email') ?? $nasabah->email }}" required>
                                </div>
                            </div>
                            <div class="row gutters mb-3">
                                <label class="col-sm-2 col-form-label">Role <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <select name="role" class="select-single js-states form-control "
                                        title="Select Product Category" data-live-search="true" required>
                                        <option selected value="4" {{ old('role') == 4 ? 'selected' : '' }}>Nasabah
                                        </option>
                                        <option value="5" {{ old('role') == 5 ? 'selected' : '' }}>Pelanggan
                                        </option>

                                    </select>
                                </div>
                            </div>
                            <div class="row gutters mb-3">
                                <label class="col-sm-2 col-form-label">Alamat <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input name="alamat" style="text-transform: capitalize" type="text"
                                        class="form-control" value="{{ old('alamat') ?? $nasabah->alamat }}" required>

                                </div>
                            </div>
                            <div class="row gutters mb-3">
                                <label class="col-sm-2 col-form-label">Pembayaran Rutin <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <select name="pembayaran_rutin" class="select-single js-states form-control "
                                        data-live-search="true" required>
                                        @foreach ($dataPembayaranRutin as $pembayaranRutin)
                                            <option
                                                {{ $pembayaranRutin->id == $nasabah->pembayaran_rutin_id || old('pembayaran_rutin') == $nasabah->pembayaran_rutin_id ? 'selected' : '' }}
                                                value="{{ $pembayaranRutin->id }}">
                                                {{ $pembayaranRutin->nama_pembayaran }} -
                                                @currency($pembayaranRutin->total_biaya)</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row gutters mb-3">
                                <label class="col-sm-2 col-form-label">Password</label>
                                <div class="col-sm-10">
                                    <input name="password" type="password" placeholder="Isi Hanya Jika Ingin Diganti"
                                        class="form-control " autocomplete="new-password">
                                    <small>Minimal 8 karakter, karakter spesial, angka, huruf diperbolehkan</small>

                                </div>
                            </div>
                            <div class="row gutters mb-3">
                                <label class="col-sm-2 col-form-label">Re-Password</label>
                                <div class="col-sm-10">
                                    <input name="re-password" type="password" placeholder="Isi Hanya Jika Ingin Diganti"
                                        class="form-control" autocomplete="new-password">
                                    <small>Minimal 8 karakter, karakter spesial, angka, huruf diperbolehkan</small>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-10 offset-sm-2">
                                    <div class="form-actions-footer">
                                        <a href="{{ route('nasabah') }}" class="btn btn-danger">Kembali</a>
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
