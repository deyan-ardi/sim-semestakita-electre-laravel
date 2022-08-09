@extends('admin.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Manajemen Artikel & Produk, Tambah Data')
@section('meta-description', 'Ubah Data Manajemen Artikel & Produk')
@section('meta-keyword', 'Manajemen Artikel & Produk, Ubah Data')
{{-- End Meta --}}
@section('title', 'Data Artikel & Produk - Ubah')
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
                        <h4>Edit Artikel</h4>
                    </div>
                    <div class="card-body">
                        <!-- Row start -->
                        <div class="row justify-content-between">
                            <div class="text-danger mb-3 d-flex justify-content-end">* Wajib Diisi</div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">

                                <!-- Row start -->
                                <div class="widget widget-map">
                                    <div class="widget-map-area">
                                        @if (!empty($artikel->gambar))
                                            <img class="img-profile img-thumbnail border-primary img-preview"
                                                src="{{ asset('storage/' . $artikel->gambar) }}">
                                        @else
                                            <img class="img-profile img-thumbnail border-primary img-preview"
                                                src="{{ asset('assets/admin/img/default-artikel.png') }}">
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

                                        <form action="{{ route('artikel.update', [$artikel->id]) }}" method="POST"
                                            enctype="multipart/form-data">
                                            @method('PUT')
                                            @csrf
                                            <div class="mb-3">
                                                <label for="file" class="form-label">Foto Artikel</label>
                                                <input id="file" type="file" accept=".jpg,.png" onchange="previewImg()"
                                                    name="gambar" class="form-control ">

                                                <div class="form-text">Hanya menerima file dengan format .jpg .png
                                                    atau .jpeg dengan ukuran maksimal 2Mb.</div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="judulArtikel" class="form-label">Judul <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="judul" style="text-transform: capitalize;"
                                                    class="form-control" id="judulArtikel" aria-describedby="emailHelp"
                                                    value="{{ old('judul') ?? $artikel->judul }}" required>

                                            </div>

                                            <div class="mb-3">
                                                <label for="kategoriArtikel" class="form-label">Kategori <span
                                                        class="text-danger">*</span></label>
                                                <select name="kategori" id="kategoriArtikel"
                                                    class="select-single js-states form-control"
                                                    title="Select Artikel Category" data-live-search="true" required>
                                                    <option
                                                        {{ $artikel->kategori == 'Artikel' || old('kategori') == 'Artikel' ? 'selected' : '' }}
                                                        value="Artikel">Artikel</option>
                                                    <option
                                                        {{ $artikel->kategori == 'Produk' || old('kategori') == 'Produk' ? 'selected' : '' }}
                                                        value="Produk">Produk</option>
                                                    <option
                                                        {{ $artikel->kategori == 'Bibit Tanaman' || old('kategori') == 'Bibit Tanaman' ? 'selected' : '' }}
                                                        value="Bibit Tanaman">Bibit Tanaman</option>
                                                </select>
                                            </div>

                                            <div class="mb-3" id="stokBarang">
                                                <label for="stok" class="form-label">Stok Barang <span
                                                        class="text-danger">*</span></label>

                                                <div class="input-group">
                                                    <input type="number" min="0.01" name="stok" step="0.01"
                                                        onKeyPress="if(this.value.length==10) return false;"
                                                        class="form-control" id="stok" required
                                                        value="{{ old('stok') ?? $artikel->stok }}">
                                                    <span class="input-group-text">
                                                        Kg
                                                    </span>
                                                </div>

                                            </div>
                                            <div class="mb-3" id="hargaBarang">
                                                <label for="harga" class="form-label">Harga Barang <span
                                                        class="text-danger">*</span></label>

                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        Rp
                                                    </span>
                                                    <input type="number" min="0" name="harga" step="0.01"
                                                        onKeyPress="if(this.value.length==10) return false;"
                                                        class="form-control" id="harga" required
                                                        value="{{ old('harga') ?? $artikel->harga }}">
                                                    <span class="input-group-text">
                                                        /Kg
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="mb-3 ck-editor__editable ck-editor__editable_inline">
                                                <label for="konten" class="form-label">Konten <span
                                                        class="text-danger">*</span></label>
                                                <textarea rows="20" name="konten" class="form-control" id="konten">{!! old('konten') ?? $artikel->konten !!}</textarea>
                                            </div>

                                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                                <div class="form-actions-footer">
                                                    <div class="text-end">
                                                        <a href="{{ route('artikel') }}"><button type="button"
                                                                class="btn btn-light">Kembali</button></a>
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
        ClassicEditor
            .create(document.querySelector('#konten'))
            .catch(error => {
                console.error(error);
            });
    </script>

    <script>
        var stokBarang = document.getElementById('stokBarang');
        var hargaBarang = document.getElementById('hargaBarang');
        var stok = document.getElementById('stok');
        var harga = document.getElementById('harga');
        var kategoriArtikel = document.getElementById('kategoriArtikel');

        if ("{{ old('kategori') }}" != "") {
            if ("{{ old('kategori') }}" == "Produk" || "{{ old('kategori') }}" == "Bibit Tanaman") {
                stokBarang.style.display = 'block';
                hargaBarang.style.display = 'block';
                stok.required = true;
                harga.required = true;
            } else {
                stokBarang.style.display = 'none';
                stok.value = null;
                stok.required = false;
                hargaBarang.style.display = 'none';
                harga.value = null;
                harga.required = false;
            }
        }

        if (kategoriArtikel.value == "Produk" || kategoriArtikel.value == "Bibit Tanaman") {
            stokBarang.style.display = 'block';
            hargaBarang.style.display = 'block';
        } else {
            stokBarang.style.display = 'none';
            hargaBarang.style.display = 'none';
            stokBarang.style.display = 'none';
            hargaBarang.style.display = 'none';
            stok.required = false;
            harga.required = false;
        }

        kategoriArtikel.onchange = function() {
            if (kategoriArtikel.value == "Produk" || kategoriArtikel.value == "Bibit Tanaman") {
                stokBarang.style.display = 'block';
                hargaBarang.style.display = 'block';
                // console.log(kategoriArtikel.value);
                stok.setAttribute("required", "");
                harga.setAttribute("required", "");
            } else {
                stokBarang.style.display = 'none';
                stok.value = null;
                stok.removeAttribute("required");

                hargaBarang.style.display = 'none';
                harga.value = null;
                harga.required = false;
            }
        }
    </script>
@endsection
