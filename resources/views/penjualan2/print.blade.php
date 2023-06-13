<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>

    <link rel="stylesheet" href="{{ asset('theme') }}/assets/css/main/app.css">
    <link rel="stylesheet" href="{{ asset('theme') }}/assets/css/pages/fontawesome.css">
    <style>
        table {
            font-size: 11px;
        }

        .dhead {
            background-color: #435EBE !important;
            color: white;
        }

        .dborder {
            border-color: #435EBE
        }
    </style>
</head>

<body>
    <div class="py-3 px-3">
        <div class="row">
            <div class="col text-start">
                <h3>Penjualan AGL : {{ $detail->nota_manual }}</h3>
            </div>
            <div class="col text-end">
                <span style="font-size:10px;"><em><b>Tanggal Cetak: {{ tanggal(date('Y-m-d')) }},
                            {{ ucwords($detail->admin) }}</b></em></span>
            </div>
        </div>

        <hr>
        <table width="100%" cellpadding="10px">
            <tr>
                <th width="10%">Tanggal</th>
                <th width="2%">:</th>
                <th>{{ tanggal($detail->tgl) }}</th>
                <th width="10%">No Nota</th>
                <th width="2%">:</th>
                <th>PAGL-{{ $detail->urutan }}</th>
            </tr>
            <tr>
                <th width="10%">Customer</th>
                <th width="2%">:</th>
                <th>{{ $detail->nm_customer }}</th>
                <th width="10%">Driver</th>
                <th width="2%">:</th>
                <th>{{ $detail->driver }}</th>
            </tr>
        </table>
        <br>
        <br>
        <table class="table table-hover table-bordered dborder">
            <thead>
                <tr>
                    <th class="dhead" width="5">#</th>
                    <th class="dhead">Nama Produk</th>
                    <th width="15%" class="dhead text-center">Qty</th>
                    <th width="15%" class="dhead" style="text-align: right">Rp Satuan</th>
                    <th width="15%" class="dhead" style="text-align: right">Total Rp</th>
                    <th width="15%" class="dhead">Admin</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $ttlQty = 0;
                    $ttlRp = 0;
                    $ttlTotal = 0;
                @endphp
                @foreach ($produk as $no => $a)
                    @php
                        $ttlQty += $a->qty;
                        $ttlRp += $a->rp_satuan;
                        $ttlTotal += $a->total_rp;
                    @endphp
                    <tr>
                        <td>{{ $no + 1 }}</td>
                        <td>{{ $a->nm_produk }}</td>
                        <td align="center">{{ $a->qty }}</td>
                        <td align="right">{{ number_format($a->rp_satuan, 0) }}</td>
                        <td align="right">{{ number_format($a->total_rp, 0) }}</td>
                        <td>{{ $a->admin }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td></td>
                    <th colspan="1">Total</th>
                    <th style="text-align: center">{{ $ttlQty }}</th>
                    <th style="text-align: right">Rp. {{ number_format($ttlRp, 0) }}</th>
                    <th style="text-align: right">Rp. {{ number_format($ttlTotal, 0) }}</th>
                    <td></td>
                </tr>
            </tbody>

        </table>

    </div>

</body>

</html>
