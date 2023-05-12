<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<style>
    body {
        width: 100%;
        height: 100%;
        margin: 0;
        padding: 0;

        font: 12pt "Tahoma";
    }

    * {
        box-sizing: border-box;
        -moz-box-sizing: border-box;
    }

    .page {
        width: 210mm;
        min-height: 297mm;
        padding: 20mm;
        margin: 10mm auto;

        border-radius: 5px;
        background: white;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }

    .subpage {
        padding: 1cm;
        height: 257mm;

    }

    @page {
        size: A4;
        margin: 0;
    }

    @media print {

        html,
        body {
            width: 210mm;
            height: 297mm;
        }

        .page {
            margin: 0;
            border: initial;
            border-radius: initial;
            width: initial;
            min-height: initial;
            box-shadow: initial;
            background: initial;
            page-break-after: always;
        }
    }
</style>

<body>
    <div class="book">
        <div class="page">
            <div class="subpage">
                <table style="font-size: small; white-space: nowrap;  " width="100%">
                    <tr>
                        <td rowspan="3" width="80%"><img src="/assets/login/img/empat.svg" width="100" alt=""></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>Tanggal</td>
                        <td>&nbsp;</td>
                        <td>:</td>
                        <td>&nbsp;</td>
                        <td style="text-align: center;">
                            <?= date('d-F-Y', strtotime($pembelian->tgl)) ?>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>No Faktur</td>
                        <td>&nbsp;</td>
                        <td>:</td>
                        <td>&nbsp;</td>
                        <td style="text-transform: uppercase;">
                            <?= $pembelian->no_nota ?>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>Kpd Yth, Bpk/Ibu</td>
                        <td>&nbsp;</td>
                        <td>:</td>
                        <td>&nbsp;</td>
                        <td>
                            <?= strtoupper($pembelian->suplier_akhir) ?>
                        </td>
                    </tr>
                </table>
                <br>
                <table style="text-align: center; border-collapse: collapse;" width="100%" border="1">
                    <thead>
                        <tr>
                            <th style="text-align: center">Produk</th>
                            <th>Qty</th>
                            <th>Satuan</th>
                            <th> Harga</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $qty_total = 0;
                        @endphp
                        @foreach ($produk as $no => $p)
                        @php
                        $qty_total += $p->qty;
                        @endphp
                        <tr>
                            <td>{{$p->nm_produk}}</td>
                            <td>{{number_format($p->qty,0)}}</td>
                            <td>{{$p->nm_satuan}}</td>
                            <td>{{number_format($p->h_satuan,0)}}</td>
                            <td>{{number_format($p->qty == '0' ? '0' : $p->qty * $p->h_satuan,0)}}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="5" rowspan="4" style="height: 100px;">&nbsp;</td>
                        </tr>

                    </tbody>
                    <tfoot>
                        <tr>
                            <th style="text-align: center;">Total</th>
                            <th style="text-align: right;">
                                <?= number_format($qty_total, 0) ?>
                            </th>
                            <th></th>
                            <th style="text-align: right;">
                                <?= number_format($pembelian->total_harga == '0' ? '0':$pembelian->total_harga /$qty_total,0 ) ?>
                            </th>
                            <th style="text-align: right;">
                                <?= number_format($pembelian->total_harga, 0) ?>
                            </th>
                        </tr>


                    </tfoot>
                </table>
                <br>
                <br>
                <hr style="border: 1px solid black;">
                <br>
                <br>
                <table style="font-size: small; white-space: nowrap;  " width="100%">
                    <tr>
                        <td rowspan="3" width="80%"><img src="/assets/login/img/empat.svg" width="100" alt=""></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>Tanggal</td>
                        <td>&nbsp;</td>
                        <td>:</td>
                        <td>&nbsp;</td>
                        <td style="text-align: center;">
                            <?= date('d-F-Y', strtotime($pembelian->tgl)) ?>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>No Faktur</td>
                        <td>&nbsp;</td>
                        <td>:</td>
                        <td>&nbsp;</td>
                        <td style="text-transform: uppercase;">
                            <?= $pembelian->no_nota ?>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>Kpd Yth, Bpk/Ibu</td>
                        <td>&nbsp;</td>
                        <td>:</td>
                        <td>&nbsp;</td>
                        <td>
                            <?= strtoupper($pembelian->suplier_akhir) ?>
                        </td>
                    </tr>
                </table>
                <br>
                <table style="text-align: center; border-collapse: collapse;" width="100%" border="1">
                    <thead>
                        <tr>
                            <th style="text-align: center">Produk</th>
                            <th>Qty</th>
                            <th>Satuan</th>
                            <th> Harga</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $qty_total = 0;
                        @endphp
                        @foreach ($produk as $no => $p)
                        @php
                        $qty_total += $p->qty;
                        @endphp
                        <tr>
                            <td>{{$p->nm_produk}}</td>
                            <td>{{number_format($p->qty,0)}}</td>
                            <td>{{$p->nm_satuan}}</td>
                            <td>{{number_format($p->h_satuan,0)}}</td>
                            <td>{{number_format($p->qty == '0' ? '0' : $p->qty * $p->h_satuan,0)}}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="5" rowspan="4" style="height: 100px;">&nbsp;</td>
                        </tr>

                    </tbody>
                    <tfoot>
                        <tr>
                            <th style="text-align: center;">Total</th>
                            <th style="text-align: right;">
                                <?= number_format($qty_total, 0) ?>
                            </th>
                            <th></th>
                            <th style="text-align: right;">
                                <?= number_format($pembelian->total_harga == '0' ? '0':$pembelian->total_harga / $qty_total,0 ) ?>
                            </th>
                            <th style="text-align: right;">
                                <?= number_format($pembelian->total_harga, 0) ?>
                            </th>
                        </tr>


                    </tfoot>
                </table>


            </div>
        </div>

    </div>

</body>

<script>
    window.print()
</script>

</html>