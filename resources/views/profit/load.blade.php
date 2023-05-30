<style>
    .dhead {
        background-color: #435EBE !important;
        color: white;
    }
</style>
<section class="row">

    <table class="table table-bordered">
        <tr>
            <th class="dhead"><a class="uraian text-white" href="#" data-bs-toggle="modal" jenis="1" data-bs-target="#tambah-uraian">Uraian</a> </th>
            <th colspan="2" class="dhead" style="text-align: right">Rupiah</th>
        </tr>
        @foreach ($subKategori1 as $d)
        <tr>
            <th colspan="2"><a href="#" data-bs-toggle="modal" data-bs-target="#tambah-profit">{{ $d->sub_kategori }}</a>
            </th>
        </tr>
        @endforeach

        {{-- @php
            $total_pendapatan = 0;
        @endphp
        @foreach ($profit as $p)
            @php
                $total_pendapatan += $p->kredit - $p->debit;
            @endphp
            <tr>
                <td colspan="2" style="padding-left: 20px">{{ ucwords(strtolower($p->nm_akun)) }}</td>
                <td align="right">Rp. {{ number_format($p->kredit - $p->debit, 0) }}</td>
            </tr>
        @endforeach --}}
        <tr>
            <td colspan="2" class="fw-bold" style="border-bottom: 1px solid black;">Total Pendapatan</td>
            <td class="fw-bold" align="right" style="border-bottom: 1px solid black;">
                Rp. {{ number_format(1, 0) }}</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <th class="dhead"><a class="uraian text-white" href="#" data-bs-toggle="modal" jenis="2" data-bs-target="#tambah-uraian">Biaya - Biaya</a> </th>
            <th colspan="2" class="dhead" style="text-align: right">Rupiah</th>
        </tr>
        @foreach ($subKategori2 as $d)
        <tr>
            <th colspan="2"><a href="#" data-bs-toggle="modal" data-bs-target="#tambah-profit">{{ $d->sub_kategori }}</a>
            </th>
        </tr>
        @endforeach
        {{-- @php
            $total_biaya = 0;
        @endphp
        @foreach ($loss as $l)
            @php
                $total_biaya += $l->debit - $l->kredit;
            @endphp
            <tr>
                <td>{{ ucwords(strtolower($l->nm_akun)) }}</td>
                <td width="5%">Rp</td>
                <td align="right">{{ number_format($l->debit - $l->kredit, 0) }}</td>
            </tr>
        @endforeach --}}
        <tr>
            <td colspan="2" class="fw-bold" style="border-bottom: 1px solid black;">Total Biaya-biaya</td>
            <td class="fw-bold" align="right" style="border-bottom: 1px solid black;">
                {{ number_format(1, 0) }}</td>
        </tr>
        <tr>
            <td colspan="2" class="fw-bold">TOTAL LABA BERSIH</td>
            <td class="fw-bold" align="right">Rp.{{ number_format(2, 0) }}</td>
        </tr>

        <tbody>

        </tbody>
    </table>


</section>
