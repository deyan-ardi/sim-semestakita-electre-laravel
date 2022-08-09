@extends('enduser.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Rekening Pengguna')
@section('meta-description', 'Data Rekening Pengguna')
@section('meta-keyword', 'Rekening Pengguna')
{{-- End Meta --}}
@section('title', 'Rekening Pengguna')
@section('content')
    <div class="content container-fluid">
        @include('enduser.widgets.profile_menu')
        <div class="row">
            <div class="col-12 m-b-100">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Informasi Rekening</h5>
                    </div>
                    <div class="card-body">

                        <form method="POST" action="{{ route('enduser.profil.rekening.aksi', [Auth::user()->id]) }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row form-group">
                                <label for="bank" class="col-xl-2 col-form-label input-label">Bank</label>
                                <div class="col-xl-10">
                                    <select name="bank" required id="bank"
                                        class="form-control @error('bank') is-invalid @enderror">
                                        <option value="">Silahkan Pilih Bank</option>
                                        <option value="BRI" {{ Auth::user()->nama_bank == 'BRI' ? 'selected' : '' }}>BRI
                                        </option>
                                        <option value="BCA" {{ Auth::user()->nama_bank == 'BCA' ? 'selected' : '' }}>BCA
                                        </option>
                                        <option value="BNI" {{ Auth::user()->nama_bank == 'BNI' ? 'selected' : '' }}>BNI
                                        </option>
                                        <option value="Mandiri"
                                            {{ Auth::user()->nama_bank == 'Mandiri' ? 'selected' : '' }}>
                                            Mandiri</option>
                                    </select>
                                    @error('bank')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row form-group">
                                <label for="no_rek" class="col-xl-2 col-form-label input-label">Nomor Rekening</label>
                                <div class="col-xl-10">
                                    <input type="number" min="0" pattern="[0-9]" required
                                        class="form-control @error('no_rek') is-invalid @enderror" id="no_rek" name="no_rek"
                                        placeholder="Tambahkan No Rekening"
                                        value="{{ !empty(Auth::user()->no_rekening) ? Auth::user()->no_rekening : '' }}">
                                    @error('no_rek')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <small>Nama Pemilik Rekening yang tertera di Bank wajib sama dengan Nama Anda</small>
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
