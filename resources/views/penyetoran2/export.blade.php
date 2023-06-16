<table>
    <tr>
        <th>No</th>
        <th>Tanggal</th>
        <th>No Nota</th>
        <th>Pembayaran</th>
        <th>Keterangan</th>
        <th>Nominal</th>
        <th>Admin</th>
    </tr>
    @foreach ($datas as $no => $d)
        <tr>
            <td>{{ $no + 1 }}</td>
            <td>{{ tanggal($d->tgl) }}</td>
            <td>{{ $d->no_nota }}</td>
            <td>{{ $d->nm_akun }}</td>
            <td>{{ $d->ket }}</td>
            <td align="right">{{ $d->debit }}</td>
            <td>{{ ucwords($d->admin) }}</td>
        </tr>
    @endforeach
</table>
