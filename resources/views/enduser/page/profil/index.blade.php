@extends('enduser.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Profil Pengguna')
@section('meta-description', 'Data Profil Pengguna')
@section('meta-keyword', 'Profil Pengguna')
{{-- End Meta --}}
@section('title', 'Profil Pengguna')
@section('footer')
    <script>
        const previewImg = () => {
            const file = document.getElementById('edit_img');
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
    <div class="content container-fluid">
        @include('enduser.widgets.profile_menu')
        <div class="row">
            <div class="col-12 m-b-100">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Informasi Akun</h5>
                    </div>
                    <div class="card-body">

                        <form method="POST" action="{{ route('enduser.profil.aksi', [Auth::user()->id]) }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row form-group">
                                <label for="name" class="content-center col-xl-2 col-form-label input-label">Foto
                                    Profil</label>
                                <div class="col-xl-10">
                                    <div class="d-flex align-items-center">
                                        <label class="avatar avatar-xxl profile-cover-avatar m-0" for="edit_img">
                                            <img id="avatarImg" class="avatar-img img-preview"
                                                src="{{ Auth::user()->foto }}" alt="Profile Image">
                                            <input type="file" accept=".jpg,.png,.jpeg" id="edit_img"
                                                class="@error('profil') is-invalid @enderror" onchange="previewImg()"
                                                name="profil">

                                            @error('profil')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <span class="avatar-edit">
                                                <i data-feather="edit-2" class="avatar-uploader-icon shadow-soft"></i>
                                            </span>
                                        </label>
                                    </div>
                                    <small>Hanya menerima file bertipe .jpg,.jpeg, atau .png dengan maksimal 1 mb</small>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label for="name" class="col-xl-2 col-form-label input-label">Nama Lengkap</label>
                                <div class="col-xl-10">
                                    <input required type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" placeholder="Nama Lengkap Anda" name="name"
                                        value="{{ Auth::user()->name }}">
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row form-group">
                                <label for="phone" class="col-xl-2 col-form-label input-label">No. WhatsApp</label>
                                <div class="col-xl-10">
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            +62
                                        </span>
                                        <input required type="text"
                                            class="form-control @error('phone') is-invalid @enderror" id="input_phone"
                                            name="phone" placeholder="08xxxxxxxxxxx" value="{{ Auth::user()->no_telp }}">
                                        @error('phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label for="location" class="col-xl-2 col-form-label input-label">Alamat</label>
                                <div class="col-xl-10">
                                    <div class="mb-3">
                                        <textarea required class="form-control @error('address') is-invalid @enderror" name="address" id="location" rows="3"
                                            placeholder="Alamat Lengkap Anda">{{ Auth::user()->alamat }}</textarea>
                                        @error('address')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
