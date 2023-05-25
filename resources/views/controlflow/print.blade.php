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
                        <h5 class="text-center">Arus Kas</h5>
                        <h5 class="mt-2 text-center">{{date('d-F-Y',strtotime($tgl1))}} ~
                            {{date('d-F-Y',strtotime($tgl2))}}</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th class="dhead">Akun</th>
                                <th class="dhead" style="text-align: right">Rupiah</th>
                            </tr>
                            <tr>
                                <td colspan="2" class="fw-bold">Pendapatan</td>
                            </tr>
                            @php
                            $total_cash ="0";
                            @endphp
                            @foreach ($cash as $c)
                            @php
                            $total_cash += $c->kredit;
                            @endphp
                            <tr>
                                <td>
                                    <p style="padding-left: 20px">{{$c->nama}}</p>
                                </td>
                                <td align="right">Rp. {{empty($c->kredit) ? '0' : number_format($c->kredit,0)}}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td class="fw-bold">Total Pendapatan</td>
                                <td class="fw-bold" align="right">Rp. {{number_format($total_cash,0)}}</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="fw-bold">Pengeluaran</td>
                            </tr>
                            @php
                            $total_p = 0;
                            @endphp
                            @foreach ($pengeluaran as $c)
                            @php
                            $total_p += $c->debit;
                            @endphp
                            <tr>
                                <td style="padding-left: 20px">{{$c->nama}}</td>
                                <td align="right">Rp. {{empty($c->debit) ? '0' : number_format($c->debit,0)}}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td class="fw-bold">Total Pengeluaran</td>
                                <td class="fw-bold" align="right">Rp. {{number_format($total_p,0)}}</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Arus Kas Bersih</td>
                                <td class="fw-bold" align="right">Rp. {{number_format($total_cash - $total_p,0)}}</td>
                            </tr>
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