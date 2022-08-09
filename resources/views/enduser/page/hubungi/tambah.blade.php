@extends('enduser.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Tambah Feedback')
@section('meta-description', 'Data Tambah Feedback')
@section('meta-keyword', 'Tambah Feedback')
{{-- End Meta --}}
@section('title', 'Tambah Feedback')
@section('content')
    <div class="content container-fluid">
        <div class="row">
            <div class="col-12 m-b-100">
                <div class="section-header">
                    <h3 class="section-title">Tambah Feedback</h3>
                    <div class="line"></div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Berikan Feedback Anda Kepada Kami</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-danger mb-3 d-flex justify-content-end">* Wajib Diisi</div>
                        <div class="row">
                            <div class="col-md-3 col-12 mb-3">
                                <div class="widget widget-map">
                                    <div class="widget-map-area">
                                        <img class="img-profile img-thumbnail border-success img-preview"
                                            src="{{ asset('assets/admin/img/default-artikel.png') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-9 col-12">
                                <form action="{{ route('enduser.hubungi.tambah.aksi') }}" enctype="multipart/form-data"
                                    method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label>Foto/Gambar Feedback</label>
                                        <input type="file" id="file" accept=".jpg,.png,.jpeg" name="gambar"
                                            class="form-control @error('gambar') is-invalid @enderror"
                                            onchange="previewImg()">
                                        @error('gambar')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <small>Hanya menerima file bertipe .jpg,.jpeg, atau .png dengan maksimal 1 mb
                                        </small>
                                    </div>
                                    <div class="form-group">
                                        <label>Judul <span class="text-danger">*</span></label>
                                        <input type="text" value="{{ old('judul') }}" style="text-transform: capitalize;"
                                            name="judul" class="form-control @error('judul') is-invalid @enderror"
                                            placeholder="Tuliskan Judul Feedback">
                                        @error('judul')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Kategori <span class="text-danger">*</span></label>
                                        <select name="kategori"
                                            class="form-control @error('kategori') is-invalid @enderror">
                                            <option value="Pengaduan"
                                                {{ old('kategori') == 'Pengaduan' ? 'selected' : '' }}>Pengaduan</option>
                                            <option value="Kritik" {{ old('kategori') == 'Kritik' ? 'selected' : '' }}>
                                                Kritik</option>
                                            <option value="Saran" {{ old('kategori') == 'Saran' ? 'selected' : '' }}>
                                                Saran</option>
                                        </select>
                                        @error('kategori')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Feedback Anda <span class="text-danger">*</span></label>
                                        <textarea style="text-transform: capitalize;" placeholder="Tuliskan Feedback Anda Disini" name="konten" rows="5"
                                            cols="5"
                                            class="form-control @error('konten') is-invalid @enderror">{{ old('konten') }}</textarea>
                                        @error('konten')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn col-12 btn-primary"> Kirim Feedback </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom-script')
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
