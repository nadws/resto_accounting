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
                <img src="{{ app('gambarLogo') }}"  width="60" alt="">
            </div>
            <div class="col">
                <h1 class="text-center">{{ app('nm_lokasi') }}</h1>

            </div>
            <div class="col text-end">
                <p class="text-success fst-italic" style="font-size: 11px">Tanggal Cetak : {{ tanggal(date('Y-m-d')) . ', ' . auth()->user()->name }}</p>
                
            </div>
        </div>
        <div class="row mt-3">
            <div class="col text-start">
                <h4 class="text-primary">Informasi Suplier</h4>
                <div class="row">
                    <span><b>Nama Suplier</b></span>
                    <span>Npwp</span>
                    <span>Alamat</span>
                    <span>Email (+62 Telp)</span>
                </div>
            </div>
            <div class="col text-end">
                <h3 class="text-primary">{{ $title }}</h3>
                <table class="table">
                    <tr>
                        <td>No Order</td>
                        <td width="5">:</td>
                        <th width="140" class="text-decoration-underline">#PO-1001</th>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td width="5">:</td>
                        <th width="140" class="text-decoration-underline">{{ tanggal(date('Y-m-d')) }}</th>
                    </tr>
                </table>
            </div>
        </div>
        <hr>
        <div class="row mt-3">
            <div class="col">
                <div class="tbl-container bdr">
                <table class="table table-bordered">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>No</th>
                            <th>Nama Bahan</th>
                            <th class="text-end">Qty</th>
                            <th>Satuan</th>
                            <th>Keterangan</th>
                            <th class="text-end">Rp Satuan</th>
                            <th class="text-end">Total Rp</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Saos</td>
                            <td align="right">2</td>
                            <td>Kotak</td>
                            <td>Saos untuk daging</td>
                            <td align="right">{{ number_format(10000,0) }}</td>
                            <td align="right">{{ number_format(20000,0) }}</td>
                        </tr>
                    </tbody>
                    <tfoot class="rounded-tfoot text-white bg-info">
                        <tr>
                            <th class="text-center " colspan="2">Total</th>
                            <th class="text-end">8</th>
                            <th class="text-end" colspan="4">20,000</th>
                        </tr>
                    </tfoot>
                </table>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <table class="table table-border">
                    <tr>
                        <td  class="bg-info text-white text-start"><h6>Catatan Tambahan</h6></td>
                    </tr>
                    <tr>
                        <td align="left">Pembayaran paling lambat tgl 30 hari setelah barang di terima</td>
                    </tr>
                </table>
            </div>
            <div class="col-7">

            </div>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>


</body>

</html>
