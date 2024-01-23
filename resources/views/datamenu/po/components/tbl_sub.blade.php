<div class="col-6">
    <table class="table table-sm table-border">
        <tr>
            <td class="dhead  text-start">
                <h6 class="text-white">Catatan Tambahan</h6>
            </td>
        </tr>
        <tr>
            <td align="left">{{ $poDetail->catatan ?? '-' }}</td>
        </tr>
    </table>
</div>

<div class="col-6">
    <table class="table table-hover text-start" style="padding-bottom: 1px">
        <tr>
            <th>Sub Total</th>
            <th>
                <h6 class="subTotal text-end">{{ number_format($poDetail->sub_total, 2) }}</h6>
            </th>
        </tr>
        @if ($poDetail->potongan)
            <tr>
                <th>
                    Potongan Diskon
                    {{ $poDetail->diskon < 100 ? "$poDetail->diskon %" : number_format($poDetail->diskon, 2) }}
                </th>
                <td align="right">
                    <h6> {{ number_format($poDetail->potongan, 2) }}</h6>

                </td>
            </tr>
            <tr>
                <th>
                    Subtotal - Potongan Diskon
                </th>
                <td align="right">
                    <h6> {{ number_format($poDetail->sub_total - $poDetail->potongan, 2) }}</h6>

                </td>
            </tr>
        @endif
        @if ($poDetail->biaya)
            <tr>
                <th>
                    Biaya Pengiriman
                </th>
                <td align="right">
                    <h6>{{ number_format($poDetail->biaya, 2) }}</h6>
                </td>
            </tr>
        @endif
        
        <tr>
            <th>Pajak</th>
            <th>
                <h6 class="grandTotal text-end">{{ number_format($poDetail->ttl_pajak, 2) }}</h6>
            </th>
        </tr>
        <tr>
            <th>Total</th>
            <th>
                <h6 class="grandTotal text-end">{{ number_format($total, 2) }}</h6>
            </th>
        </tr>

        @if ($bayarSum->ttlBayar)
            @foreach ($cekSudahPernahBayar as $i => $d)
                <tr class="text-primary border">
                    <th style="font-size:13px"><i class="fas fa-check me-2"></i>Pembayaran
                        
                        {{ strtoupper($d->nm_akun) }} ke -
                        {{ $i + 1 }}</th>
                    <th>
                        <h6 class="text-primary text-end"> {{ number_format($d->jumlah, 2) }}</h6>
                    </th>
                </tr>
            @endforeach

        @endif
        <tr>
            <th>Sisa Tagihan</th>
            <th>
                
                <input type="hidden" name="sisaTagihan" class="sisaTagihanValue" value="{{ $sisaTagihan }}">
                <h5 class="text-end"><em class="sisaTagihan ">{{ number_format($sisaTagihan, 2) }}</em>
                </h5>
            </th>
        </tr>
        @php
            $bTambahan = DB::table('po_biaya_tambahan as a')
                ->join('tb_ekspedisi as b', 'a.id_ekspedisi', 'b.id_ekspedisi')
                ->join('akun as c', 'a.id_akun', 'c.id_akun')
                ->where('a.no_nota', $poDetail->no_nota)
                ->first();
        @endphp
        @if ($bTambahan)
            <tr>
                <th>
                    Biaya Tambahan Setelah Nota Datang
                    <br>
                    {{ strtoupper($bTambahan->nm_akun) }}

                </th>
                <th>
                    <h6 class="text-end">{{ number_format($bTambahan->ttl_rp_biaya, 2) }}</h6>

                </th>
            </tr>

            <tr>
                <th>
                    <h6>Grand Total</h6>
                </th>
                <th>
                    @php
                        $grandTotal = $total + $bTambahan->ttl_rp_biaya;
                    @endphp
                    <input type="hidden" name="sisaTagihan" class="sisaTagihanValue" value="{{ $grandTotal }}">
                    <h5 class="text-end"><em class="sisaTagihan ">{{ number_format($grandTotal, 2) }}</em>
                    </h5>
                </th>
            </tr>
        @endif
    </table>
</div>
