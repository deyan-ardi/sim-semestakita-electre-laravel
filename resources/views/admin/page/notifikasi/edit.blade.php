@extends('admin.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Notifikasi, Ubah Notifikasi')
@section('meta-description', 'Data Notifikasi, Ubah Notifikasi')
@section('meta-keyword', 'Notifikasi, Ubah Notifikasi')
{{-- End Meta --}}
@section('title', 'Notifikasi - Ubah Notifikasi')
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
                        <h4>Ubah dan Kirim Notifikasi</h4>
                    </div>
                    <div class="card-body">
                        <!-- Row start -->
                        <div class="text-danger mb-3 d-flex justify-content-end">* Wajib Diisi</div>
                        <div class="row justify-content-between">

                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">

                                <!-- Row start -->
                                <div class="widget widget-map">
                                    <div class="widget-map-area">
                                        @if (empty($notifikasi->gambar))
                                            <img class="img-profile img-thumbnail border-primary img-preview"
                                                src="{{ asset('assets/admin/img/default-artikel.png') }}">
                                        @else
                                            <img class="img-profile img-thumbnail border-primary img-preview"
                                                src="{{ asset('storage/' . $notifikasi->gambar) }}">
                                        @endif
                                    </div>
                                </div>

                                <!-- Row end -->

                            </div>
                            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-12">

                                <!-- Row start -->
                                <div class="row gutters">

                                    <!-- Row start -->
                                    <div class="row gutters">
                                        <form action="{{ route('notifikasi.update', [$notifikasi->id]) }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('patch')
                                            <div class="mb-3">
                                                <label for="user_find" class="form-label">Notifikasi Untuk <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" disabled
                                                    value="{{ $notifikasi->user->no_member }}--{{ $notifikasi->user->name }} -- {{ $notifikasi->user->role == 4 ? 'Nasabah' : 'Pelanggan' }}">
                                                <input type="hidden" name="user" value="{{ $notifikasi->user_id }}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="file" class="form-label">Foto Untuk Notifikasi</label>
                                                <input id="file" type="file" accept=".jpg,.jpeg,.png"
                                                    onchange="previewImg()" name="gambar" class="form-control ">

                                                <div class=" form-text">Tidak wajib jika tidak ingin diubah, Hanya
                                                    menerima
                                                    file dengan format .jpg .png
                                                    atau .jpeg dengan ukuran maksimal 2Mb.
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="judulArtikel" class="form-label">Judul Informasi <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" value="{{ old('judul') ?? $notifikasi->judul }}"
                                                    name="judul" style="text-transform: capitalize;" class="form-control"
                                                    id="judulArtikel" aria-describedby="emailHelp" required>

                                            </div>
                                            <div class="mb-3 ck-editor__editable ck-editor__editable_inline">
                                                <label for="konten" class="form-label">Isi Notifikasi <span
                                                        class="text-danger">*</span></label>
                                                <textarea rows="20" name="konten" class="form-control"
                                                    id="konten">{{ old('konten') ?? $notifikasi->konten }}</textarea>

                                            </div>

                                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                                <div class="form-actions-footer">
                                                    <div class="text-end">
                                                        <a href="{{ route('notifikasi') }}"><button type="button"
                                                                class="btn btn-danger">Kembali</button></a>
                                                        <button type="submit"
                                                            class="btn btn-primary ms-1">Perbaharui</button>
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
        ClassicEditor
            .create(document.querySelector('#konten'))
            .catch(error => {
                console.error(error);
            });
    </script>

@endsection
