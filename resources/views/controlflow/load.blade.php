<style>
    .dhead {
        background-color: #435EBE !important;
        color: white;
    }
</style>
<div class="row">

    <div class="col-lg-12">
        <table class="table table-bordered">
            <tr>
                <th class="dhead">Akun</th>
                <th class="dhead" style="text-align: right">Rupiah</th>
            </tr>
            <tr>
                <td colspan="2"><a href="#" jenis='1' onclick="event.preventDefault();"
                        class="input_pendapatan fw-bold">Pendapatan</a></td>
            </tr>
            @php
                $total_cash = '0';
            @endphp
            @foreach ($cash as $c)
                @php
                    $total_cash += $c->kredit;
                @endphp
                <tr>
                    <td><a href="#" onclick="event.preventDefault();" class="tmbhakun"
                            id_kategori="{{ $c->id_kategori_cashcontrol }}" tgl1="{{ $tgl1 }}"
                            tgl2="{{ $tgl2 }}" style="padding-left: 20px">{{ $c->nama }}</a></td>
                    <td align="right">Rp. {{ empty($c->kredit) ? '0' : number_format($c->kredit, 0) }}</td>
                </tr>
            @endforeach
            <tr>
                <td class="fw-bold">Total Pendapatan</td>
                <td class="fw-bold" align="right">Rp. {{ number_format($total_cash, 0) }}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2"><a href="#" jenis='2' onclick="event.preventDefault();"
                        class="input_pendapatan fw-bold">Pengeluaran</a></td>
            </tr>
            @php
                $total_p = 0;
            @endphp
            @foreach ($pengeluaran as $c)
                @php
                    $total_p += $c->debit;
                @endphp
                <tr>
                    <td>
                        <a href="#" onclick="event.preventDefault();" tgl1="{{ $tgl1 }}"
                            tgl2="{{ $tgl2 }}" class="tmbhakun" id_kategori="{{ $c->id_kategori_cashcontrol }}"
                            style="padding-left: 20px">{{ $c->nama }}</a>
                    </td>
                    <td align="right">Rp. {{ empty($c->debit) ? '0' : number_format($c->debit, 0) }}</td>
                </tr>
            @endforeach
            <tr>
                <td class="fw-bold">Total Pengeluaran</td>
                <td class="fw-bold" align="right">Rp. {{ number_format($total_p, 0) }}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td class="fw-bold">Arus Kas Bersih</td>
                <td class="fw-bold" align="right">Rp. {{ number_format($total_cash - $total_p, 0) }}</td>
            </tr>
        </table>
    </div>
</div>
