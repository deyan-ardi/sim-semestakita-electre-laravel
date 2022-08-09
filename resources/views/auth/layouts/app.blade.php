<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>{{ config('mitra.name') }} ::. @yield('title')</title>
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
    <link rel="shortcut icon" href="{{ config('mitra.fav') }}">
    @include('auth.layouts._header')
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
    @yield('content')

    @include('auth.layouts._footer')
    <script>
        var url = 'https://wati-integration-service.clare.ai/ShopifyWidget/shopifyWidget.js?46173';
        var s = document.createElement('script');
        s.type = 'text/javascript';
        s.async = true;
        s.src = url;
        var options = {
            "enabled": true,
            "chatButtonSetting": {
                "backgroundColor": "#4dc247",
                "ctaText": "",
                "borderRadius": "25",
                "marginLeft": "0",
                "marginBottom": "20",
                "marginRight": "20",
                "position": "right"
            },
            "brandSetting": {
                "brandName": "SEMESTA KITA",
                "brandSubTitle": "Layanan Chat Admin Aplikasi",
                "brandImg": "https://email.semestakita.id/icon.png",
                "welcomeText": "Halo {{ Auth::user()->name ?? 'Pengguna' }}!\nBagaimana saya bisa membantu Anda?",
                "backgroundColor": "#0a5f54",
                "ctaText": "Hubungi Pengembang",
                "borderRadius": "25",
                "autoShow": false,
                "phoneNumber": "628980125840"
            }
        };
        s.onload = function() {
            CreateWhatsappChatWidget(options);
        };
        var x = document.getElementsByTagName('script')[0];
        x.parentNode.insertBefore(s, x);
    </script>
</body>

</html>
