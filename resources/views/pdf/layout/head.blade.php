<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>@yield('title', config('app.name'))</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link media="all" rel="stylesheet" type="text/css"
        href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <style>
        @page {
            margin-top: 70px;
            margin-bottom: 70px;
            margin-left: 0;
            margin-right: 0;
        }

        body {
            font-family: "times-roman", sans-serif, "Arial";
            font-size: 16px;
        }

        main {
            margin: 10px 2cm;
        }

        h1 {
            font-size: 16px;
        }

        div.pagebreak {
            page-break-after: always;
        }

        header {
            position: fixed;
            top: -55px;
            left: 0px;
            right: 0px;
            height: 50px;
            text-align: center;
            margin: 0 2cm;
        }

        footer {
            position: fixed;
            bottom: -10px;
            left: 0px;
            right: 0px;
            width: 100%;
            height: auto;
            text-align: center;
        }

        .w-100 {
            width: 100%;
        }

        .w-50 {
            width: 50%;
        }
    </style>
</head>