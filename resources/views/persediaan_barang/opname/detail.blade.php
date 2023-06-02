<table width="100%" cellpadding="10px">
    <tr>
        <td width="10%"><b>Tanggal</b> </td>
        <td width="2%">:</td>
        <td>{{ tanggal($detail->tgl) }}</td>
        <td width="10%"><b>No Nota</b> </td>
        <td width="2%">:</td>
        <td>{{ $detail->no_nota }}</td>
    </tr>
    <tr>
        <td width="10%"><b>Gudang</b> </td>
        <td width="2%">:</td>
        <td>{{ $detail->gudang->nm_gudang }}</td>
        <td width="10%"><b>Keterangan</b> </td>
        <td width="2%">:</td>
        <td>{{ $detail->ket }}</td>
    </tr>
</table>
<br>
<br>

<style>
    .dhead {
        background-color: #435EBE !important;
        color: white;
    }

    .dborder {
        border-color: #435EBE
    }
</style>
<input type="text" autofocus class="form-control mb-3" id="searchInput" placeholder="search...">

<table class="table table-hover table-bordered dborder" id="tblId">
    <thead>
        <tr>
            <th class="dhead" width="5">#</th>
            <th class="dhead">Nama Produk </th>
            <th width="15%" class="dhead" style="text-align: right">Tersedia (Program)</th>
            <th width="15%" class="dhead" style="text-align: right">Tersedia (Fisik)</th>
            <th width="15%" class="dhead" style="text-align: right">Selisih</th>
        </tr>
    </thead>
    <tbody>
        @php
            $ttlDebit = 0;
            $ttlSelisih = 0;
        @endphp
        @foreach ($stok as $no => $d)
            @php
                $ttlDebit += $d->debit;
                $ttlSelisih += $d->selisih;
            @endphp
            <tr>
                <td>{{ $no + 1 }}</td>
                <td>{{ $d->produk->nm_produk }}</td>
                <td align="right">{{ $d->jml_sebelumnya }}</td>
                <td align="right">{{ $d->jml_sesudahnya }}</td>
                <td align="right">{{ $d->selisih }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td></td>
            <th colspan="2">Total</th>
            <th style="text-align: right">{{ $ttlDebit }}</th>
            <th style="text-align: right">{{ $ttlSelisih }}</th>
        </tr>
    </tfoot>
</table>
