<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>{{ $title }}</title>
    <style>
        .table>:not(caption)>*>* {
            border-bottom-width: 0px;
        }

        .bdr {
            border-radius: 16px;
            overflow: hidden;
        }

        .rounded-tfoot th:first-child {
            border-bottom-left-radius: 16px;
        }

        .rounded-tfoot th:last-child {
            border-bottom-right-radius: 16px;
        }
    </style>
</head>

<body>
    <div class="container py-3 text-center">
        <div class="row align-items-center">
            <div class="col text-start">
                <img src="{{ app('gambarLogo') }}" width="60" alt="">
            </div>
            <div class="col">
                <h1 class="text-center">{{ app('nm_lokasi') }}</h1>

            </div>
            <div class="col text-end">
                <p class="text-success fst-italic" style="font-size: 11px">Tanggal Cetak :
                    {{ tanggal(date('Y-m-d')) . ', ' . auth()->user()->name }}</p>

            </div>
        </div>
        <div class="row mt-3">
            <div class="col text-start">
                <h4 class="text-primary">Informasi Suplier</h4>
                <div class="row">
                    <span><b>{{ strtoupper($transaksi->nm_suplier) }}</b></span>
                    <span>{{ $transaksi->npwp }}</span>
                    <span>{{ ucwords($transaksi->alamat) }}</span>
                    <span>{{ $transaksi->email }} ({{ $transaksi->telepon }})</span>
                </div>
            </div>
            <div class="col text-end">
                <h3 class="text-primary">{{ $title }}</h3>
                <table class="table">
                    <tr>
                        <td>No Order</td>
                        <td width="5">:</td>
                        <th width="140"">#PO/{{ $transaksi->no_nota }}</th>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td width="5">:</td>
                        <th width="140"">{{ tanggal($transaksi->tgl_transaksi) }}</th>
                    </tr>
                </table>
            </div>
        </div>
        <hr>
        <div class="row mt-3">
            <div class="col text-start">
                <table class="table table-bordered table-info">
                    <tr>
                        <td width="205">Banyaknya Uang</td>
                        <td width="5">:</td>
                        <td>{{ terbilang($transaksi->jumlah) }}</td>
                    </tr>
                    <tr>
                        <td width="205">Untuk Pembayaran</td>
                        <td width="5">:</td>
                        <td>{{ ucwords($transaksi->nm_transaksi) }}: {{ ucwords($transaksi->nm_suplier) }}</td>
                    </tr>
                </table>
            </div>

        </div>
        <div class="row mt-3">
            <div class="col text-decoration-underline">
                <h5>Total</h5>
            </div>
            <div class="col text-decoration-underline">
                <h5>Rp {{ number_format($transaksi->jumlah, 0) }}</h5>
            </div>
        </div>
        <br><br>

        <p class="mt-5 text-end me-5">Yang bertanda Tangan</p>
        
    </div>






</body>

</html>
