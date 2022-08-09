<!DOCTYPE html>
<html>
@include('pdf.layout.head')

<body>
    @yield('header')
    @yield('footer')
    <main>
        @yield('content')
    </main>
    @include('pdf.layout.foot')
</body>

</html>