<table class="table table-stripped">
    <thead>
        <tr>
            <th class="dhead">Tanggal</th>
            <th class="dhead">Nota</th>
            <th class="dhead">Akun Pembayaran</th>
            <th class="dhead">Rupiah</th>
            <th class="text-center dhead">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($setor as $no => $d)
        <tr>
            <td>{{ tanggal($d->tgl) }}</td>
            <td>{{ $d->no_nota }}</td>
            <td>{{ $d->nm_akun }}</td>
            <td>Rp. {{number_format($d->debit,0)}}</td>
            <td align="center">
                <a href="{{ route('penyetoran.print_setor', ['nota_setor' => $d->no_nota]) }}" class="btn btn-sm btn-primary"><i class="fas fa-print"></i></a>
                <a id_produk="{{ $d->no_nota }}" class="btn btn-sm btn-primary kembali"
                    href="#"><i class="fas fa-redo"></i>
                    </a>
            </td>

        </tr>
        @endforeach
    </tbody>
</table>


