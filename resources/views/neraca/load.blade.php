<style>
    .dhead {
        background-color: #435EBE !important;
        color: white;
    }
</style>
<div class="row">
    <div class="col-lg-6">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th colspan="2" width="50%" class="dhead">AKTIVA</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2" class="fw-bold">
                        <a href="#" onclick="event.preventDefault();" class="tmbhsub_kategori" kategori='1'>AKTIVA
                            LANCAR</a>
                    </td>
                </tr>
                @php
                $total1 = 0;
                @endphp
                @foreach ($aktiva_lancar as $a)
                @php
                $total1 += $a->debit - $a->kredit;
                @endphp
                <tr>
                    <td>
                        <a href="#" onclick="event.preventDefault();" class="tmbhakun_neraca"
                            id_sub_kategori="{{$a->id_sub_ketagori_neraca}}">{{$a->nama_sub_kategori}}</a>

                    </td>
                    <td align="right">Rp {{number_format($a->debit - $a->kredit,0)}}</td>
                </tr>
                @endforeach

                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td class="fw-bold">JUMLAH AKTIVA LANCAR</td>
                    <td class="fw-bold" align="right">Rp {{number_format($total1,0)}}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" class="fw-bold">
                        <a href="#" onclick="event.preventDefault();" class="tmbhsub_kategori" kategori='3'>AKTIVA
                            TETAP</a>
                    </td>
                </tr>
                @php
                $total3 = 0;
                @endphp
                @foreach ($aktiva_tetap as $a)
                @php
                $total3 += $a->debit - $a->kredit;
                @endphp
                <tr>
                    <td>
                        <a href="#" onclick="event.preventDefault();" class="tmbhakun_neraca"
                            id_sub_kategori="{{$a->id_sub_ketagori_neraca}}">{{$a->nama_sub_kategori}}</a>

                    </td>
                    <td align="right">Rp {{number_format($a->debit - $a->kredit,0)}}</td>
                </tr>
                @endforeach
                <tr>
                    <td><a href="#" onclick="event.preventDefault();">AKUMULASI PENYUSUTAN(-)</a></td>
                    <td align="right">Rp {{number_format($akumulasi->total_akumulasi)}}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td class="fw-bold">NILAI BUKU</td>
                    <td class="fw-bold" align="right">Rp {{number_format($total3 - $akumulasi->total_akumulasi,0)}}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td class="fw-bold">JUMLAH AKTIVA</td>
                    <td class="fw-bold" align="right">Rp {{number_format(($total1 + $total3) -
                        $akumulasi->total_akumulasi,0)}}</td>
                </tr>

            </tbody>
        </table>
    </div>
    <div class="col-lg-6">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th colspan="2" width="50%" class="dhead">PASSIVA</th>
                </tr>
            </thead>
            <tbody>
                <tr>

                    <td colspan="2" class="fw-bold">
                        <a href="#" onclick="event.preventDefault();" class="tmbhsub_kategori" kategori='2'>HUTANG</a>
                    </td>
                </tr>
                @php
                $total2 =0;
                @endphp
                @foreach ($hutang as $h)
                @php
                $total2 +=$h->kredit - $h->debit
                @endphp
                <tr>
                    <td>
                        <a href="#" onclick="event.preventDefault();" class="tmbhakun_neraca"
                            id_sub_kategori="{{$h->id_sub_ketagori_neraca}}">{{$h->nama_sub_kategori}}</a>
                    </td>
                    <td align="right">Rp {{number_format($h->kredit - $h->debit,0)}}</td>
                </tr>
                @endforeach
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td class="fw-bold">JUMLAH AKTIVA LANCAR</td>
                    <td class="fw-bold" align="right">Rp {{number_format($total2,0)}}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" class="fw-bold">
                        <a href="#" onclick="event.preventDefault();" class="tmbhsub_kategori" kategori='4'>EKUITAS</a>
                    </td>
                </tr>
                @php
                $total4 =0;
                @endphp
                @foreach ($ekuitas as $h)
                @php
                $total4 +=$h->kredit - $h->debit;
                @endphp
                <tr>
                    <td>
                        <a href="#" onclick="event.preventDefault();" class="tmbhakun_neraca"
                            id_sub_kategori="{{$h->id_sub_ketagori_neraca}}">{{$h->nama_sub_kategori}}</a>
                    </td>
                    <td align="right">Rp {{number_format($h->kredit - $h->debit,0)}}</td>
                </tr>
                @endforeach
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td class="fw-bold">TOTAL EKUITAS</td>
                    <td class="fw-bold" align="right">Rp {{number_format($total4,0)}}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td class="fw-bold">JUMLAH PASSIVA</td>
                    <td class="fw-bold" align="right">Rp {{number_format($total2 + $total4,0)}}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>