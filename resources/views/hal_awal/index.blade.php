<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Halaman Awal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        body {
            margin: 0;
            /* Hapus margin yang mungkin membatasi body */
            padding: 0;
            /* Hapus padding yang mungkin membatasi body */
            height: 100vh;
            /* Pastikan body mencakup seluruh tinggi viewport */
            background-image: url('/assets/login/img/back.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            font-family: 'Poppins', sans-serif;
        }

        .judul {
            color: #34364A;
            font-weight: bold;
            font-size: 60px;
            text-align: center;
            margin-top: 80px
        }

        .logo_login {
            width: 270px;

            text-align: center;
            padding: 2px
        }

        .card:hover {
            transform: scale(1.05);
            transition: all 0.3s ease-in-out;
        }

        .card:hover .logo_login {
            filter: brightness(1.5) blur(1px) contrast(120%);
            transition: all 0.3s ease-in-out;
        }

        .logo_login {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0 auto;
            transition: all 0.3s ease-in-out;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <h1 class="judul">PILIH RESTORAN</h1>
            <div class="col-lg-2"></div>
            <div class="col-lg-4">
                <a href="{{ $link1 }}/login">
                    <div class="card mt-5 shadow-lg">
                        <div class="card-body">
                            <center>
                                <img src="{{ asset('assets/login/img/takemori_3.jpg') }}" alt=""
                                    class="logo_login">
                            </center>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-4">
                <a href="{{ $link2 }}/login">
                    <div class="card mt-5 shadow-lg">
                        <div class="card-body">
                            <center>
                                <img src="{{ asset('assets/login/img/sdb_logo.png') }}" alt=""
                                    class="logo_login">
                            </center>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-2"></div>
        </div>
    </div>

</body>

</html>
