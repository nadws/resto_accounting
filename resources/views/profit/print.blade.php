<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profit & Loss</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center fw-bold">PT.AGRIKA GATYA ARUM</h3>
                        <h5 class="text-center">LABA RUGI</h5>
                        <h5 class="mt-2 text-center">{{date('d-F-Y',strtotime($tgl1))}} ~
                            {{date('d-F-Y',strtotime($tgl2))}}</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">

                            <tr>
                                <th colspan="4">Uraian</th>
                            </tr>
                            <tr>
                                <th colspan="4">PEREDARAN USAHA</th>
                            </tr>
                            @php
                            $total_pendapatan = 0;
                            @endphp
                            @foreach ($profit as $p)
                            @php
                            $total_pendapatan += $p->kredit - $p->debit;
                            @endphp
                            <tr>
                                <td></td>
                                <td>{{ ucwords(strtolower($p->nm_akun))}}</td>
                                <td width="5%">Rp</td>
                                <td align="right">{{number_format($p->kredit - $p->debit,0)}}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td style="border-bottom: 1px solid black;"></td>
                                <td class="fw-bold" style="border-bottom: 1px solid black;">Total Pendapatan</td>
                                <td class="fw-bold" style="border-bottom: 1px solid black;">Rp</td>
                                <td class="fw-bold" align="right" style="border-bottom: 1px solid black;">
                                    {{number_format($total_pendapatan,0)}}</td>
                            </tr>
                            <tr>
                                <th colspan="4">BIAYA - BIAYA</th>
                            </tr>
                            @php
                            $total_biaya = 0;
                            @endphp
                            @foreach ($loss as $l)
                            @php
                            $total_biaya += $l->debit - $l->kredit;
                            @endphp
                            <tr>
                                <td></td>
                                <td>{{ ucwords(strtolower($l->nm_akun))}}</td>
                                <td width="5%">Rp</td>
                                <td align="right">{{number_format($l->debit - $l->kredit,0)}}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td style="border-bottom: 1px solid black;"></td>
                                <td class="fw-bold" style="border-bottom: 1px solid black;">Total Biaya-biaya</td>
                                <td class="fw-bold" style="border-bottom: 1px solid black;">Rp</td>
                                <td class="fw-bold" align="right" style="border-bottom: 1px solid black;">
                                    {{number_format($total_biaya,0)}}</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="fw-bold">TOTAL LABA BERSIH</td>
                                <td class="fw-bold">Rp</td>
                                <td class="fw-bold" align="right">{{number_format( $total_pendapatan - $total_biaya,0)}}
                                </td>
                            </tr>

                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>

<script>
    window.print()
</script>

</html>