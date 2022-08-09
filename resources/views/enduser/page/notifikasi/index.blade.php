@extends('enduser.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Info Pengelola')
@section('meta-description', 'Data Info Pengelola')
@section('meta-keyword', 'Info Pengelola')
{{-- End Meta --}}
@section('title', 'Info Pengelola')
@section('content')
    <div class="content container-fluid faq-section">
        <div class="row">
            <div class="col-12 m-b-100">
                <div class="section-header">
                    <h3 class="section-title">Pengumuman Dari Pengelola</h3>
                    <div class="line"></div>
                </div>

                <!--Accordion wrapper-->
                <div class="accordion md-accordion" id="faq-accordion" role="tablist" aria-multiselectable="true">
                    @if ($find->count() > 0)

                        @foreach ($find as $i => $n)
                            <!-- Accordion card -->
                            <div class="card">

                                <!-- Card header -->
                                <div class="card-header" role="tab" id="heading-{{ $n->id }}">
                                    <a data-toggle="collapse" data-parent="#faq-accordion"
                                        href="#collapse-{{ $n->id }}" aria-expanded="true"
                                        aria-controls="collapse-{{ $n->id }}">
                                        <h5 class="mb-0">
                                            Info Tentang {{ ucWords($n->judul) }} <i
                                                class="fas fa-angle-down rotate-icon"></i>
                                        </h5>
                                    </a>
                                </div>

                                <!-- Card body -->
                                <div id="collapse-{{ $n->id }}" class="collapse {{ $i == 0 ? 'show' : '' }}"
                                    role="tabpanel" aria-labelledby="heading-{{ $n->id }}"
                                    data-parent="#faq-accordion">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-3">
                                                @if (!empty($n->gambar))
                                                    <img src="{{ asset('storage/' . $n->gambar) }}"
                                                        class="img-fluid">
                                                @else
                                                    <img src="{{ asset('assets/admin/img/default-artikel.png') }}"
                                                        class="img-fluid">
                                                @endif
                                            </div>
                                            <div class="col-9">
                                                <p class="mb-3 text-right">Notifikasi Dikirim
                                                    {{ \Carbon\Carbon::parse($n->created_at)->diffForHumans() }}</p>
                                                {!! $n->konten !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!-- Accordion card -->
                        @endforeach
                    @else
                        <div class="card">
                            <div class="card-body text-center">
                                Belum Ada Data Pengumuman Untuk Kamu
                            </div>
                        </div>
                    @endif
                </div>
                <!-- Accordion wrapper -->
            </div>
        </div>
    </div>
@endsection
