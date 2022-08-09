@extends('admin.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Master Data, Ubah Iuran Rutin')
@section('meta-description', 'Data Master Data, Ubah Iuran Rutin')
@section('meta-keyword', 'Master Data, Ubah Iuran Rutin')
{{-- End Meta --}}
@section('title', 'Master - Ubah Iuran Rutin')
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
                            Edit Data Iuran Rutin
                        </div>
                    </div>
                    <div class="card-body">
                        {{-- Form Start --}}
                        <div class="text-danger mb-3 d-flex justify-content-end">* Wajib Diisi</div>
                        <form action="{{ route('iuran.master.update', [$pembayaranRutin->id]) }}" method="POST">
                            @method('PUT')
                            @csrf
                            <div class="row gutters mb-3">
                                <label class="col-sm-2 col-form-label">Nama Tagihan <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input name="nama_pembayaran" style="text-transform: capitalize" type="text"
                                        class="form-control "
                                        value="{{ old('nama_pembayaran') ?? $pembayaranRutin->nama_pembayaran }}"
                                        required>

                                </div>
                            </div>
                            <div class="row gutters mb-3">
                                <label class="col-sm-2 col-form-label">Total Tagihan <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            Rp
                                        </span>
                                        <input name="total_biaya" onKeyPress="if(this.value.length==10) return false;"
                                            type="number" min="0" class="form-control"
                                            value="{{ old('total_biaya') ?? $pembayaranRutin->total_biaya }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row gutters mb-3">
                                <label class="col-sm-2 col-form-label">Tanggal Generate <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input name="tgl_generate" type="number" min="1" max="28" placeholder="1 - 28"
                                        onkeyup="if(parseInt(this.value)>28 || parseInt(this.value)<1){ this.value =28; return false; }"
                                        class=" form-control"
                                        value="{{ old('tgl_generate') ?? $pembayaranRutin->tgl_generate }}" required>
                                </div>
                            </div>
                            <div class="row gutters mb-3">
                                <label class="col-sm-2 col-form-label">Durasi Pembayaran <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input name="durasi_pembayaran" type="number" min="1" max="28" placeholder="1 - 28"
                                        onkeyup="if(parseInt(this.value)>28 || parseInt(this.value)<1){ this.value =28; return false; }"
                                        class="form-control"
                                        value="{{ old('durasi_pembayaran') ?? $pembayaranRutin->durasi_pembayaran }}"
                                        required>
                                </div>
                            </div>
                            <div class="row gutters mb-3">
                                <label class="col-sm-2 col-form-label">Deskripsi <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <textarea name="deskripsi" style="text-transform: capitalize" class="form-control"
                                        required>{{ old('deskripsi') ?? $pembayaranRutin->deskripsi }}</textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-10 offset-sm-2">
                                    <div class="form-actions-footer">
                                        <a href="{{ route('iuran.master') }}" class="btn btn-danger">Kembali</a>
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
    </div>
    <!-- Row end -->


@endsection
