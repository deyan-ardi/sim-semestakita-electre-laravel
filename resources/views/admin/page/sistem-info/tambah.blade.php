@extends('admin.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Master Data, Tambah Informasi Sistem')
@section('meta-description', 'Data Master Data, Tambah Informasi Sistem')
@section('meta-keyword', 'Master Data, Tambah Informasi Sistem')
{{-- End Meta --}}
@section('title', 'Master - Tambah Bantuan dan Informasi Sistem')
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
                            Tambah Data Versi Aplikasi
                        </div>
                    </div>
                    <div class="card-body">
                        {{-- Form Start --}}
                        <div class="text-danger mb-3 d-flex justify-content-end">* Wajib Diisi</div>
                        <form action="{{ route('sistem.info.store') }}" method="POST">
                            @csrf
                            <div class="row gutters mb-3">
                                <label class="col-sm-2 col-form-label">Kode Versi <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input name="kode" style="text-transform: capitalize;" type="text"
                                        class="form-control" value="{{ old('kode') }}" required>

                                </div>
                            </div>
                            <div class="row gutters mb-3">
                                <label class="col-sm-2 col-form-label">Nama Versi <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input name="nama" style="text-transform: capitalize;" type="text"
                                        class="form-control" value="{{ old('nama') }}" required>

                                </div>
                            </div>
                            <div class="row gutters mb-3">
                                <label class="col-sm-2 col-form-label">Tanggal Rilis <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input name="date" type="date" value="{{ old('date') }}" class="form-control "
                                        required>

                                </div>
                            </div>
                            <div class="row gutters mb-3 ck-editor__editable ck-editor__editable_inline">
                                <label class="col-sm-2 col-form-label">Informasi Versi <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <textarea rows="20" name="konten" class="form-control " id="konten">{{ old('konten') }}</textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-10 offset-sm-2">
                                    <div class="form-actions-footer">
                                        <a href="{{ route('sistem.info') }}" class="btn btn-danger">Kembali</a>
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
    <script src="https://cdn.ckeditor.com/ckeditor5/29.1.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#konten'))
            .catch(error => {
                console.error(error);
            });
    </script>
@endsection
