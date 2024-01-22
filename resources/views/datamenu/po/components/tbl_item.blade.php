<div class="col">
    <div class="tbl-container bdr">
        <table class="table table-sm table-bordered">
            <thead class="dhead text-white">
                <tr>
                    <th>No</th>
                    <th>Nama Bahan</th>
                    <th class="text-end">Qty</th>
                    <th>Satuan</th>
                    <th>Diskon %</th>
                    <th>Pajak %</th>
                    <th class="text-end">Rp Satuan</th>
                    <th class="text-end">Total Rp</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $qtySum = 0;
                    $ttlRpSum = 0;
                @endphp
                @foreach ($getBarang as $no => $d)
                    @php
                        $ttlRp = $d->diskon != 0 ? $d->ttl_rp / (1 - $d->diskon / 100) : $d->ttl_rp;
                        $qtySum += $d->qty;
                        $ttlRpSum += $d->ttl_rp;

                    @endphp
                    <tr>
                        <td>{{ $no + 1 }}</td>
                        <td>{{ ucwords($d->nm_bahan) }}</td>
                        <td align="right">{{ number_format($d->qty, 0) }}</td>
                        <td>{{ ucwords($d->nm_satuan) }}</td>
                        <td align="right">{{ $d->diskon }}</td>
                        <td align="right">{{ $d->pajak }}</td>
                        <td align="right">{{ number_format($ttlRp / $d->qty, 2) }}</td>
                        <td align="right">{{ number_format($d->ttl_rp, 2) }}</td>
                        <td>{{ $d->ket }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="rounded-tfoot">
                <tr>
                    <th class="text-center" colspan="2">Total</th>
                    <th class="text-end">{{ $qtySum }}</th>
                    <th class="text-end" colspan="5">{{ number_format($ttlRpSum, 2) }}</th>
                    <th></th>
                </tr>
            </tfoot>

        </table>
    </div>
</div>
