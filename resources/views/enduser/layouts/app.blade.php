<!DOCTYPE html>
<html lang="en" id="main">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="title" content="{{ config('mitra.name') }},@yield('meta-name')">
    <meta name="description" content="@yield('meta-description')">
    <meta name="keywords" content="{{ config('mitra.name') }}, @yield('meta-keyword')">
    <meta name="robots" content="index, follow">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta content='id' name='language' />
    <meta content='id' name='geo.country' />
    <meta content='Indonesia' name='geo.placename' />
    <meta name="revisit-after" content="7 days">
    <meta name="google-site-verification" content="Iz-c9sI-ZfLuKHHK8HL7QIuXsdB11F3534dgVsl2Gj0" />
    {{-- Favicon --}}
    <link rel="shortcut icon" href="{{ config('mitra.fav') }}">

    {{-- TITLE --}}
    <title>{{ config('mitra.name') }} ::. @yield('title') </title>

    {{-- Link --}}
    @include('enduser.layouts._header')
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-LJG321TP7G"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-LJG321TP7G');
    </script>

</head>

<body>
    <div class="berhasil" data-berhasil="{{ Session::get('success') }}"></div>
    <div class="gagal" data-gagal="{{ Session::get('error') }}"></div>
    <!-- Page Wrapper Start -->
    <div class="main-wrapper">

        <!-- Navbar Start -->
        @include('enduser.layouts._navbar')
        <!-- Navbar End -->

        <!-- Sidebar Start -->
        @include('enduser.layouts._sidebar')
        <!-- Sidebar End -->

        <div class="page-wrapper">

            <!-- Page Content Start -->
            @yield('content')
            <!-- Page Content End -->

            <!-- Page Content Start -->
            @include('enduser.layouts._footer')
            <!-- Page Content End -->
        </div>


    </div>
    <!-- Page Wrapper End -->


    @include('enduser.layouts._script')
    @yield('custom-script')
</body>

</html>
