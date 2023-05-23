<table class="table" width="100%" id="tableScroll">
    <thead>
        <tr>
            <th width="5">#</th>
            <th>Tanggal</th>
            <th>No Nota</th>
            <th>No Penjual</th>
            <th>Keterangan</th>
            <th>Total Rp</th>
            <th>Terbayar</th>
            <th>Sisa Piutang</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($invoice as $no => $d)
            <tr class="fw-bold induk_detail{{ $d->no_nota }}">
                <td>{{ $no + 1 }}</td>
                <td>{{ date('d-m-Y', strtotime($d->tgl)) }}</td>
                <td>{{ $d->no_nota }}</td>
                <td>{{ $d->no_penjualan }}</td>
                <td>{{ $d->ket }}</td>
                <td align="right">{{ $d->total_rp }}</td>
                <td align="right">{{ $d->kredit }}</td>
                <td align="right">{{ $d->total_rp + $d->debit - $d->kredit }}</td>
                <td>{{ $d->status }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
