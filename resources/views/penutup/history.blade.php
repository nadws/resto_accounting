<div class="row">
    <style>
        .kanan {
            text-align: right;
        }
    </style>
    <div class="col-lg-12">

        <table class="table table-stripped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tanggal Ditutup</th>
                    <th>Bulan</th>
                    <th>No Nota</th>
                    <th class="kanan">Debit</th>
                    <th class="kanan">Kredit</th>
                    <th class="kanan">Saldo</th>
                </tr>
            </thead>
            @php
                $ttlDebit = 0;
                $ttlKredit = 0;
                $ttlSaldo = 0;
            @endphp
            
            @foreach ($history as $no => $d)
                @php
                    $ttlDebit += $d->debit;
                    $ttlKredit += $d->kredit;
                    $ttlSaldo += $d->debit - $d->kredit;
                @endphp

                <tr>
                    <td>{{ $no + 1 }}</td>
                    <td>{{ tanggal($d->tgl) }}</td>
                    <td>{{ date('M', strtotime($d->tgl_dokumen)) }}</td>
                    <td>{{ $d->no_nota }}</td>
                    <td align="right">{{ number_format($d->debit, 2) }}</td>
                    <td align="right">{{ number_format($d->kredit, 2) }}</td>
                    <td align="right">{{ number_format($d->debit - $d->kredit, 2) }}</td>
                </tr>
            @endforeach
            <tfoot>
                <tr>
                    <th colspan="4" class="text-center">Total</th>
                    <th class="kanan">{{ number_format($ttlDebit, 2) }}</th>
                    <th class="kanan">{{ number_format($ttlKredit, 2) }}</th>
                    <th class="kanan">{{ number_format($ttlSaldo, 2) }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>