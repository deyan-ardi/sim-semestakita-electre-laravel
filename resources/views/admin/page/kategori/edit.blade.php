@extends('admin.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Master Data, Ubah Kategori Sampah')
@section('meta-description', 'Data Master Data, Ubah Kategori Sampah')
@section('meta-keyword', 'Master Data, Ubah Kategori Sampah')
{{-- End Meta --}}
@section('title', 'Master - Ubah Kategori Sampah')
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
                            Edit Data Kategori Sampah
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="text-danger mb-3 d-flex justify-content-end">* Wajib Diisi</div>
                        {{-- Form Start --}}
                        <form action="{{ route('kategori.update', [$kategoriSampah->id]) }}" method="POST">
                            @method('PUT')
                            @csrf
                            <div class="row gutters mb-3">
                                <label class="col-sm-2 col-form-label">Nama Kategori<span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input name="nama_kategori" style="text-transform: capitalize;" type="text"
                                        class="form-control "
                                        value="{{ old('nama_kategori') ?? $kategoriSampah->nama_kategori }}" required>
                                </div>
                            </div>
                            <div class="row gutters mb-3">
                                <label class="col-sm-2 col-form-label">Jenis Sampah<span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <select name="jenis_sampah" class="select-single js-states form-control "
                                        title="Select Product Category" data-live-search="true" required>
                                        <option
                                            {{ $kategoriSampah->jenis_sampah == 'organik' || old('jenis_sampah') == 'organik' ? 'selected' : '' }}
                                            value="organik">Organik</option>
                                        <option
                                            {{ $kategoriSampah->jenis_sampah == 'nonorganik' || old('jenis_sampah') == 'nonorganik' ? 'selected' : '' }}
                                            value="nonorganik">Non Organik</option>
                                        <option
                                            {{ $kategoriSampah->jenis_sampah == 'B3' || old('jenis_sampah') == 'B3' ? 'selected' : '' }}
                                            value="B3">
                                            B3
                                        </option>
                                        <option
                                            {{ $kategoriSampah->jenis_sampah == 'residu' || old('jenis_sampah') == 'residu' ? 'selected' : '' }}
                                            value="residu">Residu</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row gutters mb-3">
                                <label class="col-sm-2 col-form-label">Harga Beli<span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            Rp
                                        </span>
                                        <input name="harga_beli" onKeyPress="if(this.value.length==10) return false;"
                                            type="number" class="form-control" min="0"
                                            value="{{ old('harga_beli') ?? $kategoriSampah->harga_beli }}" required>
                                        <span class="input-group-text">
                                            /Kg
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row gutters mb-3">
                                <label class="col-sm-2 col-form-label">Total Sampah Saat Ini<span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <input name="total_sampah" type="number"
                                            onKeyPress="if(this.value.length==10) return false;" class="form-control"
                                            min="0" step="0.01"
                                            value="{{ old('total_sampah') ?? $kategoriSampah->total_sampah }}" required>
                                        <span class="input-group-text">
                                            Kg
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-10 offset-sm-2">
                                    <div class="form-actions-footer">
                                        <a href="{{ route('kategori') }}" class="btn btn-danger">Kembali</a>
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
