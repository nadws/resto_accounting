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
    <div class="container py-3">
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
                <h4 class="text-primary">Informasi Penginput</h4>
                <div class="row">
                    <span><b>{{ strtoupper($poDetail[0]->admin) }}</b></span>
                </div>
            </div>
            <div class="col text-end">
                <h3 class="text-primary">{{ $title }}</h3>
                <table class="table">
                    <tr>
                        <td>No Order</td>
                        <td width="5">:</td>
                        <th width="140"">#PO/{{ $poDetail[0]->no_nota }}</th>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td width="5">:</td>
                        <th width="140"">{{ tanggal($poDetail[0]->tgl_order) }}</th>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col">
                <h6>Kategori</h6>
                @php
                    $kategoriTampung = [];
                @endphp
                @foreach ($poDetail as $d)
                    @if (!in_array(strtoupper($d->nm_kategori), $kategoriTampung))
                        <div class="row">
                            <div class="col-lg-3">
                                <table class="table table-stripped">
                                    <thead>
                                        <tr>
                                            <th class="dhead">{{ strtoupper($d->nm_kategori) }}</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        @php
                            $kategoriTampung[] = strtoupper($d->nm_kategori);
                        @endphp
                        <div class="row ">
                            <div class="col-lg-6">
                                <table class="table table-bordered">
                                    <thead class="bg-info text-white">
                                        <tr>
                                            <th class="">Nama Barang</th>
                                            <th class=" text-center" width="100">Satuan Resep</th>
                                            <th class=" text-end" width="90">Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($poDetail as $ib => $b)
                                            @if (strtoupper($b->nm_kategori) == strtoupper($d->nm_kategori))
                                                <tr>
                                                    <td>{{ ucwords($b->nm_bahan) }}</td>
                                                    <td align="center">{{ strtoupper($b->nm_satuan) }}</td>
                                                    <td align="right">
                                                        {{ $b->qty }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                @endforeach

            </div>
        </div>
        <div class="row px-5">
            <div class="col text-start">
                Admin
                <br><br>
                ..............................
            </div>
            <div class="col text-end">
                Penerima
                <br><br>
                ..............................
            </div>
        </div>

    </div>





    <script>
        window.print()
    </script>
</body>

</html>
