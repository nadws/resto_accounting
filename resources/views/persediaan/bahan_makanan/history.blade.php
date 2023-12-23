<a class="btn btn-primary btn-sm" data-bs-toggle="collapse" href="#masuk" role="button" aria-expanded="true"
    aria-controls="collapseExample">
    Stok Masuk / Keluar
</a>

<a class="btn btn-primary btn-sm" data-bs-toggle="collapse" href="#opname" role="button" aria-expanded="true"
    aria-controls="collapseExample">
    Opname
</a>
<hr class="mt-3 mb-3">

<div class="collapse" id="masuk" style="">
    <h6>Stok Masuk & Keluar</h6>
    <table class="table" id="tblMasuk">
        <thead>
            <tr>
                <th class="dhead">#</th>
                <th class="dhead">Invoice</th>
                <th class="dhead">Nama Bahan</th>
                <th class="dhead">Tgl</th>
                <th class="dhead text-center">Debit</th>
                <th class="dhead text-center">Kredit</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($stokMasuk as $no => $d)
                <tr>
                    <td>{{ $no + 1 }}</td>
                    <td>{{ $d->invoice }}</td>
                    <td>{{ $d->nm_bahan }}</td>
                    <td>{{ tanggal($d->tgl) }}</td>
                    <td align="right">{{ number_format($d->debit, 0) }}</td>
                    <td align="right">{{ number_format($d->kredit, 0) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>


<div class="collapse mt-3" id="opname" style="">
    <h6>Stok Opname</h6>
    <table class="table" id="tblOpname">
        <thead>
            <tr>
                <th class="dhead">#</th>
                <th class="dhead">Invoice</th>
                <th class="dhead">Nama Bahan</th>
                <th class="dhead">Tgl</th>
                <th class="dhead">Stok Program</th>
                <th class="dhead">Stok Aktual</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($stokOpname as $no => $d)
                <tr>
                    <td>{{ $no + 1 }}</td>
                    <td>{{ $d->invoice }}</td>
                    <td>{{ $d->nm_bahan }}</td>
                    <td>{{ tanggal($d->tgl) }}</td>
                    <td>{{ number_format($d->program, 0) }}</td>
                    <td>{{ number_format($d->debit == 0 ?  $d->program - $d->kredit : $d->debit + $d->program, 0) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
