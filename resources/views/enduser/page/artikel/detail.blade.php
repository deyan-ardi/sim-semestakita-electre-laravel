@extends('enduser.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Detail Artikel')
@section('meta-description', 'Data Detail Artikel')
@section('meta-keyword', 'Detail Artikel')
{{-- End Meta --}}
@section('title', 'Detail Artikel')
@section('content')
    <div class="content container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="article-container m-b-100">
                    <div class="card article-card">
                        <div class="card-body">
                            <div class="article-title">
                                <h5 class="text-muted">{{ $artikel->kategori }} Untuk Anda</h5>
                                <h3>{{ $artikel->judul }}</h3>
                                <div class="article-info text-muted">
                                    <span><i class="fas fa-calendar-alt"></i>
                                        {{ $artikel->created_at->format('d M Y') }}</span>
                                    <span><i class="fas fa-user-edit"></i> {{ $artikel->created_by }}</span>
                                </div>
                            </div>
                            <div class="article-description">
                                <div class="row">
                                    <div class="col-md-4">
                                        <img src="{{ asset('storage/' . $artikel->gambar) }}" alt="Artikel Image"
                                            class="card-img-top article-img">
                                    </div>
                                    <div class="col-md-8 mt-4" style="text-transform: capitalize;text-align:justify">
                                        {!! $artikel->konten !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="section-header">
                        <h3 class="section-title">Artikel Lainnya</h3>
                        <div class="line"></div>
                    </div>
                    <div class="row">
                        <div class="blog-list owl-carousel owl-theme">
                            @foreach ($artikel_lain as $v)
                                <div class="item">
                                    <a href="{{ route('enduser.artikel.detail', $v->slug) }}">
                                        <div class="card">
                                            <img src="{{ asset('storage/' . $v->gambar) }}"
                                                alt="Artikel Image {{ $v->judul }}" class="card-img-top img-md">
                                        </div>
                                        <div class="card-img-overlay  d-flex align-items-center p-0">
                                            <p class="card-title text-center flex-fill p-4"
                                                style="background-color: rgba(0, 0, 0, 0.5); color: white; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                {{ $v->judul }}
                                            </p>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-script')
    <script>
        $(window).on('load', function() {
            // Owl Carousel
            var owl = $('.owl-carousel');
            owl.owlCarousel({
                loop: true,
                margin: 10,
                autoplay: true,
                autoplayTimeout: 5000,
                autoplayHoverPause: true,
                responsiveClass: true,
                responsiveRefreshRate: true,
                responsive: {
                    0: {
                        items: 1,
                        stagePadding: 15
                    },
                    690: {
                        items: 2,
                        stagePadding: 15
                    },
                    1000: {
                        items: 3
                    }
                }
            });
        });
    </script>
@endsection
