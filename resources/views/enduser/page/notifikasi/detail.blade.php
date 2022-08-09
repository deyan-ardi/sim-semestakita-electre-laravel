@extends('enduser.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Notifikasi Sistem')
@section('meta-description', 'Data Notifikasi Sistem')
@section('meta-keyword', 'Notifikasi Sistem')
{{-- End Meta --}}
@section('title', 'Notifikasi Sistem')
@section('content')
    <div class="content container-fluid faq-section">
        <div class="row">
            <div class="col-12 m-b-100">
                <div class="section-header">
                    <h3 class="section-title">Notifikasi Sistem</h3>
                    <div class="line"></div>
                </div>

                <!--Accordion wrapper-->
                <div class="accordion md-accordion" id="faq-accordion" role="tablist" aria-multiselectable="true">
                    <!-- Accordion card -->
                    <div class="card">

                        <!-- Card header -->
                        <div class="card-header" role="tab" id="heading-{{ $detail->id }}">
                            <a data-toggle="collapse" data-parent="#faq-accordion" href="#collapse-{{ $detail->id }}"
                                aria-expanded="true" aria-controls="collapse-{{ $detail->id }}">
                                <h5 class="mb-0">
                                    Notifikasi {{ ucWords($detail->judul) }} <i class="fas fa-angle-down rotate-icon"></i>
                                </h5>
                            </a>
                        </div>

                        <!-- Card body -->
                        <div id="collapse-{{ $detail->id }}" class="collapse show" role="tabpanel"
                            aria-labelledby="heading-{{ $detail->id }}" data-parent="#faq-accordion">
                            <div class="card-body">
                                <div class="row justify-content-center">
                                    <div class="col-6">
                                        <p>{{ $detail->konten }}</p>
                                        @php
                                            if ($detail->key == 'pengaduan') {
                                                $to = route('enduser.hubungi.index');
                                            } elseif ($detail->key == 'iuran') {
                                                $to = route('enduser.tagihan.index');
                                            } elseif ($detail->key == 'nabung') {
                                                $to = route('enduser.penyetoran.index');
                                            } elseif ($detail->key == 'tarik') {
                                                $to = route('enduser.tabungan.index');
                                            } elseif ($detail->key == 'angkut') {
                                                $to = route('enduser.rekapan-penilaian.index');
                                            }
                                        @endphp
                                        <a href="{{ $to }}"><button type="button"
                                                class="btn btn-primary mt-3 col-12">Lihat
                                                Informasi</button></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- Accordion wrapper -->
            </div>
        </div>
    </div>
@endsection
