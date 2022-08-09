<!-- Bootstrap css -->
<link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap.min.css') }}">

<!-- Icomoon Font Icons css -->
<link rel="stylesheet" href="{{ asset('assets/admin/fonts/style.css') }}">

<!-- Main css -->
<link rel="stylesheet" href="{{ asset('assets/admin/css/main.css') }}">

<!-- Search Filter JS -->
<link rel="stylesheet" href="{{ asset('assets/admin/vendor/search-filter/search-filter.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/vendor/search-filter/custom-search-filter.css') }}">

<!-- Data Tables -->
<link rel="stylesheet" href="{{ asset('assets/admin/vendor/datatables/dataTables.bs4.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/vendor/datatables/dataTables.bs4-custom.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/vendor/datatables/buttons.bs.css') }}">

{{-- Sweetalert --}}
<link rel="stylesheet" href="{{ asset('assets/admin/css/sweetalert2.css') }}">
{{-- Other CSS --}}
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css"
    integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
<style>
    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Firefox */
    input[type=number] {
        -moz-appearance: textfield;
    }

</style>
@yield('header')
