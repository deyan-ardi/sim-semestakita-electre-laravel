@extends('admin.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Master Data, Tambah Nasabah')
@section('meta-description', 'Data Master Data, Tambah Nasabah')
@section('meta-keyword', 'Master Data, Tambah Nasabah')
{{-- End Meta --}}
@section('title', 'Master - Tambah Nasabah')
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
                            Tambah Data Nasabah
                        </div>
                    </div>
                    <div class="card-body">
                        {{-- Form Start --}}
                        <div class="text-danger mb-3 d-flex justify-content-end">* Wajib Diisi</div>
                        <form action="{{ route('nasabah.store') }}" method="POST">
                            @csrf
                            <div class="row gutters mb-3">
                                <label class="col-sm-2 col-form-label">No. Member <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input name="no_member" value="{{ old('no_member') }}" id="noMember"
                                        style="text-transform: uppercase" type="text" class="form-control " required>

                                    <div class="checkbox-inline mt-2">
                                        <input id="checkbox" onclick="check()" type="checkbox" name="generate"
                                            value="generate" value="" {{ old('generate') == 'generate' ? 'checked' : '' }}>
                                        <label for="checkbox" class="ms-1"> Tekan
                                            checkbox jika ingin
                                            generate No. Member
                                            otomatis</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row gutters mb-3">
                                <label class="col-sm-2 col-form-label">Nama <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input name="name" maxlength="100" value="{{ old('name') }}"
                                        style="text-transform: capitalize" type="text" class="form-control " required>
                                </div>
                            </div>
                            <div class="row gutters mb-3">
                                <label class="col-sm-2 col-form-label">No. Telepon <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input name="no_telp" value="{{ old('no_telp') }}" type="number" minlength="8"
                                        maxlength="15" onKeyPress="if(this.value.length==15) return false;"
                                        class="form-control" required>

                                </div>
                            </div>
                            <div class="row gutters mb-3">
                                <label class="col-sm-2 col-form-label">No. Rekening <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input name="no_rekening" value="{{ old('no_rekening') }}" minlength="10"
                                        maxlength="15" type="number" onKeyPress="if(this.value.length==25) return false;"
                                        class="form-control" required>

                                </div>
                            </div>
                            <div class="row gutters mb-3">
                                <label class="col-sm-2 col-form-label">Nama Bank <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <select name="nama_bank" class="form-control" required>
                                        <option value="">Silahkan Pilih Bank</option>
                                        <option value="BRI" {{ old('nama_bank') == 'BRI' ? 'selected' : '' }}>BRI</option>
                                        <option value="BCA" {{ old('nama_bank') == 'BCA' ? 'selected' : '' }}>BCA</option>
                                        <option value="BNI" {{ old('nama_bank') == 'BNI' ? 'selected' : '' }}>BNI
                                        </option>
                                        <option value="Mandiri" {{ old('nama_bank') == 'Mandiri' ? 'selected' : '' }}>
                                            Mandiri
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="row gutters mb-3">
                                <label class="col-sm-2 col-form-label">Email <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input name="email" value="{{ old('email') }}" style="text-transform: lowercase"
                                        type="email" class="form-control" required>
                                </div>
                            </div>
                            <div class="row gutters mb-3">
                                <label class="col-sm-2 col-form-label">Alamat <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input name="alamat" value="{{ old('alamat') }}" style="text-transform: capitalize"
                                        type="text" class="form-control" required>
                                </div>
                            </div>
                            <div class="row gutters mb-3">
                                <label class="col-sm-2 col-form-label">Pembayaran Rutin <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <select name="pembayaran_rutin" class="select-single js-states form-control "
                                        data-live-search="true" required>
                                        @foreach ($dataPembayaranRutin as $pembayaranRutin)
                                            <option value="{{ $pembayaranRutin->id }}"
                                                {{ old('pembayaran_rutin') == $pembayaranRutin->id ? 'selected' : '' }}>
                                                {{ $pembayaranRutin->nama_pembayaran }} -
                                                @currency($pembayaranRutin->total_biaya)</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row gutters mb-3">
                                <label class="col-sm-2 col-form-label">Password <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input name="password" minlength="8" type="password" class="form-control"
                                        autocomplete="new-password" required>
                                    <small>Minimal 8 karakter, karakter spesial, angka, huruf diperbolehkan</small>
                                </div>
                            </div>
                            <div class="row gutters mb-3">
                                <label class="col-sm-2 col-form-label">Re-Password <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input name="re-password" minlength="8" type="password" class="form-control"
                                        autocomplete="new-password" required>
                                    <small>Minimal 8 karakter, karakter spesial, angka, huruf diperbolehkan</small>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-10 offset-sm-2">
                                    <div class="form-actions-footer">
                                        <a href="{{ route('nasabah') }}" class="btn btn-danger">Kembali</a>
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

@section('footer')
    <script type="text/javascript">
        function check() {
            var noMember = document.getElementById("noMember");

            if (document.getElementById("checkbox").checked == true) {
                noMember.disabled = true;
                noMember.value = '';
            } else {
                noMember.disabled = false;
            }
        }
    </script>
@endsection
