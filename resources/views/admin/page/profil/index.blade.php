@extends('admin.layouts.kasir')
{{-- Meta --}}
@section('meta-name', 'Ganti Profil Akun')
@section('meta-description', 'Data Ganti Profil Akun')
@section('meta-keyword', 'Ganti Profil Akun')
{{-- End Meta --}}
@section('title', 'Ganti Profil Akun')
@section('footer')
    <script>
        const previewImg = () => {
            const file = document.getElementById('file');
            const imgPreview = document.querySelector('.img-preview');

            const fileSampul = new FileReader();
            fileSampul.readAsDataURL(file.files[0]);
            fileSampul.onload = function(e) {
                imgPreview.src = e.target.result;
            }
        }
        $('#input_phone').bind('keyup paste', function() {
            this.value = +this.value.replace(/[^0-9]/g, '');
        });
    </script>
@endsection
@section('content')

    <!-- Content wrapper start -->
    <div class="content-wrapper">

        <!-- Row start -->
        <div class="row gutters">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('admin') }}" class="btn btn-danger mb-3"><i
                                class="icon-arrow-left"></i>Kembali</a>
                    </div>
                </div>
                <!-- Card start -->
                <div class="card">
                    <div class="card-header-lg">
                        <h4>Informasi Profil Akun</h4>
                    </div>
                    <div class="card-body">
                        <div class="text-danger mb-3 d-flex justify-content-end">* Wajib Diisi</div>

                        <div class="row gutters">
                            <div class="col-12">
                                <form action="{{ route('ganti.profil.aksi', [Auth::user()->id]) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('patch')
                                    <div class="row gutters">
                                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                            <!-- Field wrapper start -->
                                            <div class="field-wrapper">
                                                <label class="avatar avatar-xxl profile-cover-avatar m-0" for="file">
                                                    <img src="{{ $file }}" class="avatar-img img-preview mt-2"
                                                        alt="User Avatar">

                                                    <input type="file" id="file" accept=".jpg,.png,.jpeg" name="profil"
                                                        class="@error('profil') is-invalid @enderror"
                                                        onchange="previewImg()">
                                                    <span class="avatar-edit">
                                                        <i class="avatar-uploader-icon icon-pencil shadow-soft"></i>
                                                    </span>
                                                    @error('profil')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </label>
                                                <div class="field-placeholder">Foto Profil</div>
                                            </div>
                                            <small>Hanya menerima file bertipe .jpg,.jpeg,
                                                atau
                                                .png dengan maksimal
                                                1 mb</small>
                                            <!-- Field wrapper end -->
                                        </div>
                                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mt-3">
                                            <!-- Field wrapper start -->
                                            <div class="field-wrapper">
                                                <input type="text" style="text-transform: capitalize" name="name" required
                                                    class="form-control @error('name') is-invalid @enderror"
                                                    placeholder="Winter" value="{{ old('name') ?? Auth::user()->name }}">
                                                @error('name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                                <div class="field-placeholder">Nama Pengguna<span
                                                        class="text-danger">*</span></div>
                                            </div>
                                            <!-- Field wrapper end -->
                                        </div>
                                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                            <!-- Field wrapper start -->
                                            <div class="field-wrapper">
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        +62
                                                    </span>
                                                    <input type="number" minlength="8" required maxlength="15" min="0"
                                                        name="phone" id="input_phone"
                                                        onKeyPress="if(this.value.length==15) return false;"
                                                        class="form-control @error('phone') is-invalid @enderror"
                                                        placeholder="+62"
                                                        value="{{ old('phone') ?? Auth::user()->no_telp }}">
                                                    @error('phone')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>

                                                <div class="field-placeholder">No WhatsApp<span
                                                        class="text-danger">*</span></div>
                                            </div>
                                            <!-- Field wrapper end -->
                                        </div>

                                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                            <!-- Field wrapper start -->
                                            <div class="field-wrapper">
                                                <input type="text" name="address" style="text-transform: capitalize"
                                                    value="{{ old('address') ?? Auth::user()->alamat }}"
                                                    class="form-control @error('address') is-invalid @enderror"
                                                    placeholder="Alamat Rumah">
                                                @error('address')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                                <div class="field-placeholder">Alamat</div>
                                            </div>
                                            <!-- Field wrapper end -->
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                        <button type="submit" class="btn btn-primary mb-3">Simpan Profil</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- Card end -->

            </div>
        </div>
        <!-- Row end -->

    </div>
    <!-- Content wrapper end -->


@endsection
@section('footer')
    <script>
        const previewImg = () => {
            const file = document.getElementById('file');
            const imgPreview = document.querySelector('.img-preview');

            const fileSampul = new FileReader();
            fileSampul.readAsDataURL(file.files[0]);
            fileSampul.onload = function(e) {
                imgPreview.src = e.target.result;
            }
        }
    </script>
@endsection
