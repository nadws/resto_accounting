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
    <link rel="stylesheet" href="{{ asset('theme') }}/assets/extensions/toastify-js/src/toastify.css">
    

    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">


    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    {{-- my css --}}
    <link rel="stylesheet" href="{{ asset('mycss.css') }}">
    @yield('styles')
    
    <livewire:styles />
    <livewire:scripts />
</head>

<body>
    <div id="app">
        <div id="main" class="layout-horizontal">
