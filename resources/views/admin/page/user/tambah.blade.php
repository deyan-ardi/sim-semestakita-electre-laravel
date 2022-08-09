@extends('admin.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Master Data, Tambah User Sistem')
@section('meta-description', 'Data Master Data, Tambah User Sistem')
@section('meta-keyword', 'Master Data, Tambah User Sistem')
{{-- End Meta --}}
@section('title', 'Master - Tambah User Sistem')
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
                            Tambah Data User
                        </div>
                    </div>
                    <div class="card-body">
                        {{-- Form Start --}}
                        <div class="text-danger mb-3 d-flex justify-content-end">* Wajib Diisi</div>
                        <form action="{{ route('user.store') }}" method="POST">
                            @csrf
                            <div class="row gutters mb-3">
                                <label class="col-sm-2 col-form-label">Nama <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input name="name" value="{{ old('name') }}" style="text-transform: capitalize;"
                                        type="text" class="form-control" required>

                                </div>
                            </div>
                            <div class="row gutters mb-3">
                                <label class="col-sm-2 col-form-label">No. Telepon <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input name="no_telp" value="{{ old('no_telp') }}" type="number"
                                        class="form-control" required>
                                </div>
                            </div>
                            <div class="row gutters mb-3">
                                <label class="col-sm-2 col-form-label">Email <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input name="email" value="{{ old('email') }}" style="text-transform: lowercase;"
                                        type="email" class="form-control " required>

                                </div>
                            </div>
                            <div class="row gutters mb-3">
                                <label class="col-sm-2 col-form-label">Role <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <select name="role" class="select-single js-states form-control "
                                        title="Select Product Category" data-live-search="true" required>
                                        <option value="1" {{ old('role') == 1 ? 'selected' : '' }}>Super Admin</option>
                                        <option value="2" {{ old('role') == 2 ? 'selected' : '' }}>Pengelola</option>
                                        <option value="3" {{ old('role') == 3 ? 'selected' : '' }}>Pegawai</option>
                                        <option value="6" {{ old('role') == 6 ? 'selected' : '' }}>Pihak Lain/Guest
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="row gutters mb-3">
                                <label class="col-sm-2 col-form-label">Password <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input name="password" type="password" class="form-control"
                                        autocomplete="new-password" required>

                                </div>
                            </div>
                            <div class="row gutters mb-3">
                                <label class="col-sm-2 col-form-label">Re-Password <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input name="re-password" type="password" class="form-control"
                                        autocomplete="new-password" required>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-10 offset-sm-2">
                                    <div class="form-actions-footer">
                                        <a href="{{ route('user') }}" class="btn btn-danger">Kembali</a>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
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
