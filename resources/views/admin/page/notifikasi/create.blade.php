@extends('admin.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Notifikasi, Tambah Notifikasi')
@section('meta-description', 'Data Notifikasi, Tambah Notifikasi')
@section('meta-keyword', 'Notifikasi, Tambah Notifikasi')
{{-- End Meta --}}
@section('title', 'Notifikasi - Tambah Notifikasi')
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
                    <div class="card-header-lg">
                        <h4>Buat Notifikasi Baru</h4>
                    </div>
                    <div class="card-body">
                        <!-- Row start -->
                        <div class="row justify-content-between">
                            <div class="text-danger mb-3 d-flex justify-content-end">* Wajib Diisi</div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">

                                <!-- Row start -->
                                <div class="widget widget-map">
                                    <div class="widget-map-area">
                                        <img class="img-profile img-thumbnail border-primary img-preview"
                                            src="{{ asset('assets/admin/img/default-artikel.png') }}">
                                    </div>
                                </div>

                                <!-- Row end -->

                            </div>
                            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-12">

                                <!-- Row start -->
                                <div class="row gutters">

                                    <!-- Row start -->
                                    <div class="row gutters">

                                        <form action="{{ route('notifikasi.store') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="pilih" class="form-label">Kirim Notifikasi Ke <span
                                                        class="text-danger">*</span></label>
                                                <select name="pilih" id="pilih" onclick="pilihOpsi(this.value)"
                                                    class="select-single  js-states form-control"
                                                    title="Select Artikel Category" data-live-search="true" required>
                                                    <option value=""> -- Pilih Mau Notifikasi Kemana --</option>
                                                    <option value="semua">Kesemua User (Nasabah dan Pelanggan)</option>
                                                    <option value="nasabah">Hanya Ke Semua Nasabah</option>
                                                    <option value="pelanggan">Hanya Ke Semua Pelanggan</option>
                                                    <option value="custom">Pilih Satu User</option>
                                                </select>

                                            </div>
                                            <div class="mb-3" id="user_form" style="display: none">
                                                <label for="user" class="form-label">Pilih User Yang Dipilih <span
                                                        class="text-danger">*</span></label>
                                                <select name="user" id="user" class="select-single js-states form-control"
                                                    title="Select Artikel Category" data-live-search="true">
                                                    <option value=""> -- Kirim Ke User Yang Mana --</option>
                                                    @foreach ($user as $u)
                                                        <option value="{{ $u->id }}"
                                                            {{ old('user') == $u->id ? 'selected' : '' }}>
                                                            {{ $u->no_member }} --
                                                            {{ $u->name }} --
                                                            {{ $u->role == 4 ? 'Nasabah' : 'Pelanggan' }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                            </div>
                                            <div class="mb-3">
                                                <label for="file" class="form-label">Foto Untuk Notifikasi</label>
                                                <input id="file" type="file" accept=".jpg,.jpeg,.png"
                                                    onchange="previewImg()" name="gambar" class="form-control ">

                                                <div class=" form-text">Hanya menerima file dengan format .jpg .png
                                                    atau .jpeg dengan ukuran maksimal 2Mb.
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="judulArtikel" class="form-label">Judul Informasi <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="judul" style="text-transform: capitalize;"
                                                    class="form-control" value="{{ old('judul') }}" id="judulArtikel"
                                                    aria-describedby="emailHelp" required>

                                            </div>
                                            <div class="mb-3 ck-editor__editable ck-editor__editable_inline">
                                                <label for="konten" class="form-label">Isi Notifikasi <span
                                                        class="text-danger">*</span></label>
                                                <textarea rows="20" name="konten" class="form-control " id="konten">{{ old('konten') }}</textarea>

                                            </div>

                                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                                <div class="form-actions-footer">
                                                    <div class="text-end">
                                                        <a href="{{ route('notifikasi') }}"><button type="button"
                                                                class="btn btn-danger">Kembali</button></a>
                                                        <button type="submit" class="btn btn-primary ms-1">Simpan</button>
                                                    </div>
                                                </div>
                                            </div>

                                        </form>
                                    </div>
                                    <!-- Row end -->

                                </div>
                                <!-- Row end -->

                            </div>

                        </div>
                        <!-- Row end -->
                    </div>
                </div>
                <!-- Card end -->

            </div>
        </div>
        <!-- Row end -->
    </div>
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
    <script src="https://cdn.ckeditor.com/ckeditor5/29.1.0/classic/ckeditor.js"></script>

    <script>
        $(document).ready(function() {
            $('#user').selectize({
                sortField: 'text'
            });
        });
    </script>
    <script>
        const pilihOpsi = (e) => {
            if (e == "custom") {
                $('#user_form').css("display", "");
                $('#user').attr('required', 'required');
            } else {
                $('#user_form').css("display", "none");
                $('#user').removeAttr('required');
            }
        }
    </script>
    <script>
        ClassicEditor
            .create(document.querySelector('#konten'))
            .catch(error => {
                console.error(error);
            });
    </script>

@endsection
