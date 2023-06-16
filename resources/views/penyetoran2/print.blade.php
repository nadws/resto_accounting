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
    <div class="py-3 px-3 container">
        <div class="row">
            <div class="col text-start">
                <h3>{{ $title }}</h3>
            </div>
            <div class="col text-end">
                <span style="font-size:10px;"><em><b>Tanggal Cetak: {{ tanggal(date('Y-m-d')) }}, {{ ucwords($detail->admin) }}</b></em></span>
            </div>
        </div>
        
        <hr>
        <table width="100%" cellpadding="10px">
            <tr>
                <th width="10%">Nota Setor</th>
                <th width="2%">:</th>
                <th>{{ $detail->nota_setor }}</th>
            </tr>
        </table>


        <table class="table table-hover table-bordered dborder">
            <thead>
                <tr>
                    <th class="dhead">Tanggal</th>
                    <th class="dhead">Nota</th>
                    <th class="dhead">Pembayaran</th>
                    <th class="dhead text-end">Total Rp</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $ttlNominal = 0;
                @endphp
                <input type="hidden" name="nota_setor" value="{{ $nota }}">
                @foreach ($datas as $d)
                @php
                    $ttlNominal += $d->nominal;
                @endphp
                    <tr>
                        <td>{{ tanggal($d->tgl) }}</td>
                        <td>{{ $d->no_nota }}</td>
                        <td>{{ $d->nm_akun }}</td>
                        <td align="right">Rp. {{ number_format($d->nominal, 0) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th class="text-center" colspan="3">TOTAL</th>
                    <th class="text-end">Rp. {{ number_format($ttlNominal,0) }}</th>
                </tr>
            </tfoot>

        </table>
        
    </div>
    
</body>

</html>
