@extends('admin.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Master Data, Ubah Pelanggan')
@section('meta-description', 'Data Master Data, Ubah Pelanggan')
@section('meta-keyword', 'Master Data, Ubah Pelanggan')
{{-- End Meta --}}
@section('title', 'Master - Ubah Pelanggan')
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
                            Edit Data Pelanggan
                        </div>
                    </div>
                    <div class="card-body">

                        {{-- Form Start --}}
                        <div class="text-danger mb-3 d-flex justify-content-end">* Wajib Diisi</div>
                        <form action="{{ route('pelanggan.update', [$pelanggan->id]) }}" method="POST">
                            @method('PUT')
                            @csrf
                            <div class="row gutters mb-3">
                                <label class="col-sm-2 col-form-label">No. Member <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input name="no_member" style="text-transform: uppercase" type="text"
                                        class="form-control" value="{{ old('no_member') ?? $pelanggan->no_member }}"
                                        required>
                                </div>
                            </div>
                            <div class="row gutters mb-3">
                                <label class="col-sm-2 col-form-label">Nama <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input name="name" maxlength="100" style="text-transform: capitalize" type="text"
                                        class="form-control" value="{{ old('name') ?? $pelanggan->name }}" required>
                                </div>
                            </div>
                            <div class="row gutters mb-3">
                                <label class="col-sm-2 col-form-label">No. Telepon <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input name="no_telp" type="number" onKeyPress="if(this.value.length==15) return false;"
                                        class="form-control" minlength="8" maxlength="15"
                                        value="{{ old('no_telp') ?? $pelanggan->no_telp }}" required>
                                </div>
                            </div>
                            <div class="row gutters mb-3">
                                <label class="col-sm-2 col-form-label">Email <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input name="email" style="text-transform: lowercase" type="email"
                                        class="form-control" value="{{ old('email') ?? $pelanggan->email }}" required>
                                </div>
                            </div>
                            <div class="row gutters mb-3">
                                <label class="col-sm-2 col-form-label">Role <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <select name="role" class="select-single js-states form-control "
                                        title="Select Product Category" data-live-search="true" required>
                                        <option selected value="5">Pelanggan
                                        </option>
                                        <option value="4">Nasabah
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="row gutters mb-3">
                                <label class="col-sm-2 col-form-label">Alamat <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input name="alamat" style="text-transform: capitalize" type="text"
                                        class="form-control" value="{{ old('alamat') ?? $pelanggan->alamat }}" required>
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
                                                {{ $pembayaranRutin->id == $pelanggan->pembayaran_rutin_id || old('pembayaran_rutin') == $nasabah->pembayaran_rutin_id ? 'selected' : '' }}
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
                                        class="form-control" autocomplete="new-password">
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
                                        <a href="{{ route('pelanggan') }}" class="btn btn-danger">Kembali</a>
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
