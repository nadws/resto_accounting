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
                        <h5 class="mt-2 text-center">{{ tanggal($tgl1) }} ~
                            {{ tanggal($tgl2) }}</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $totalPendapatan = 0;
                            $totalBiaya = 0;
                            $totalLaba = 0;
                            
                            function getAkun($id_kategori, $tgl1, $tgl2, $jenis)
                            {
                                $jenis = $jenis == 1 ? 'b.kredit' : 'b.debit';
                            
                                return DB::select("SELECT c.nm_akun, b.kredit, b.debit
                                    FROM profit_akun as a 
                                    left join (
                                    SELECT b.id_akun, sum(b.debit) as debit, sum(b.kredit) as kredit
                                        FROM jurnal as b
                                        WHERE b.id_buku not in('1','5') and $jenis != 0 and b.penutup = 'T' and b.tgl between '$tgl1' and '$tgl2'
                                        group by b.id_akun
                                    ) as b on b.id_akun = a.id_akun
                                    left join akun as c on c.id_akun = a.id_akun
                                    where a.kategori_id = '$id_kategori';");
                            }
                            
                        @endphp
                        <table class="table table-bordered">
                            <tr>
                                <th colspan="2" class="dhead">Uraian</th>
                                <th class="dhead" style="text-align: right">Rupiah</th>
                            </tr>
                            @foreach ($subKategori1 as $d)
                                <tr>
                                    <th colspan="2">{{ $d->sub_kategori }}
                                    </th>
                                </tr>
                                @foreach (getAkun($d->id, $tgl1, $tgl2, 1) as $a)
                                    @php
                                        $totalPendapatan += $a->kredit;
                                    @endphp
                                    <tr>
                                        <td colspan="2" style="padding-left: 20px">
                                            {{ ucwords(strtolower($a->nm_akun)) }}</td>
                                        <td style="text-align: right">Rp. {{ number_format($a->kredit, 2) }}</td>
                                    </tr>
                                @endforeach
                            @endforeach

                            <tr>
                                <td colspan="2" class="fw-bold" style="border-bottom: 1px solid black;">Total
                                    Pendapatan</td>
                                <td class="fw-bold" align="right" style="border-bottom: 1px solid black;">
                                    Rp. {{ number_format($totalPendapatan, 0) }}</td>
                            </tr>
                            <tr>
                                <td colspan="2">&nbsp;</td>
                            </tr>
                            <tr>
                                <th class="dhead">Biaya - Biaya</th>
                                <th colspan="2" class="dhead" style="text-align: right">Rupiah</th>
                            </tr>
                            @foreach ($subKategori2 as $d)
                                <tr>
                                    <th colspan="2">{{ $d->sub_kategori }}
                                    </th>
                                </tr>
                                @foreach (getAkun($d->id, $tgl1, $tgl2, 2) as $a)
                                    @php
                                        $totalBiaya += $a->debit;
                                    @endphp
                                    <tr>
                                        <td colspan="2" style="padding-left: 20px">
                                            {{ ucwords(strtolower($a->nm_akun)) }}</td>
                                        <td style="text-align: right">Rp. {{ number_format($a->debit, 2) }}</td>
                                    </tr>
                                @endforeach
                            @endforeach

                            <tr>
                                <td colspan="2" class="fw-bold" style="border-bottom: 1px solid black;">Total
                                    Biaya-biaya</td>
                                <td class="fw-bold" align="right" style="border-bottom: 1px solid black;">
                                    {{ number_format($totalBiaya, 0) }}</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="fw-bold">TOTAL LABA BERSIH</td>
                                <td class="fw-bold" align="right">
                                    Rp.{{ number_format($totalPendapatan - $totalBiaya, 0) }}</td>
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
