<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>{{ config('mitra.name') }} ::. @yield('title')</title>

    <link rel="shortcut icon" href="{{ config('mitra.fav') }}">

    <link rel="stylesheet" href="{{ asset('assets/enduser/css/bootstrap.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/enduser/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/enduser/plugins/fontawesome/css/all.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/enduser/css/style.css') }}">
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

<body class="error-page">

    @yield('content')


    <script src="{{ asset('assets/enduser/js/jquery-3.5.1.min.js') }}"></script>

    <script src="{{ asset('assets/enduser/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/enduser/js/bootstrap.min.js') }}"></script>

    <script src="{{ asset('assets/enduser/js/feather.min.js') }}"></script>

    <script src="{{ asset('assets/enduser/js/script.js') }}"></script>
    <script>
        function goBack() {
            window.history.back();
            location.reload()
        }
        console.clear();
        var info = `
    Semesta Kita ~ Yayasan Taksu Tridatu
    ===============================
    Product Name: SEMESTA KITA
    Develop By  : PT Ganadev Multi Solusi
    Powered By  : Undiksha, Sistem Informasi Undiksha
    Develop For : Yayasan Taksu Tridatu
    `;
        console.log(info);
    </script>
</body>


</html>
