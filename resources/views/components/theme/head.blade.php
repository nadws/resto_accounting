@props(['title' => 'Laravel'])
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>

    <link rel="stylesheet" href="{{ asset('theme') }}/assets/css/main/app.css">
    <link rel="shortcut icon" href="{{ asset('theme') }}/assets/images/logo/favicon.svg" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('theme') }}/assets/images/logo/favicon.png" type="image/png">
    <link rel="stylesheet" href="{{ asset('theme') }}/assets/css/pages/fontawesome.css">
    <link rel="stylesheet"
        href="{{ asset('theme') }}/assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="{{ asset('theme') }}/assets/css/pages/datatables.css">
    <link rel="stylesheet" href="{{ asset('theme') }}/assets/extensions/choices.js/public/assets/styles/choices.css">
    <link rel="stylesheet" href="{{ asset('theme') }}/assets/extensions/toastify-js/src/toastify.css">

    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">


    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }

        .form-switch2 .form-check-input2 {
            background-image: url(data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3E%3Ccircle r='3' fill='rgba(0, 0, 0, 0.25)'/%3E%3C/svg%3E);
            background-position: 0;
            border-radius: 2em;
            margin-left: -2.5em;
            transition: background-position .15s ease-in-out;
            width: 40px;
            transform: scale(1.5);
            margin-top: 8px;
            margin-left: -22px;
        }

        .modal-lg-max {
            max-width: 1200px;
        }

        .select2 {
            width: 100% !important;

        }

        .select2-container--default .select2-selection--single {
            background-color: #fff;
            border: 1px solid rgb(237, 238, 241);
            border-radius: 4px;
            height: 35px;
        }

        input:read-only {
            background-color: #E9ECEF;
        }

        input:active {
            background-color: #E9ECEF;
        }

        .active {
            text-decoration: underline;
            color: #ffffff !important;
        }

        .card-hover .card-front {
            position: relative;
            z-index: 2;
            transition: transform .5s;
        }

        .card-hover .card-back {
            position: absolute;
            z-index: 1;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            opacity: 0;
            transform: rotateY(180deg);
            transition: transform .5s, opacity .5s;
        }

        .card-hover:hover .card-front {
            transform: rotateY(180deg);
        }

        .card-hover:hover .card-back {

            opacity: 1;
            transform: rotateY(0deg);
        }

        .card-icon i {
            transition: all 0.3s ease-in-out;
        }

        .card:hover .card-icon i {
            transform: rotate(360deg);
        }

        .card:hover .card-title {
            opacity: 0;
        }

        .card:hover .card-text {
            opacity: 1;
        }
    </style>
    @yield('styles')
    <livewire:styles />
    <livewire:scripts />
</head>

<body>
    <div id="app">
        <div id="main" class="layout-horizontal">
