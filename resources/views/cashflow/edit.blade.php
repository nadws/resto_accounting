<table class="table" x-data="{}">
    <thead>
        <tr>
            <th class="dhead">Tanggal</th>
            <th class="dhead">Debit</th>
            <th class="dhead">Kredit</th>
            <th class="dhead">Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @php
            $debit = (int) Crypt::decrypt($detail->debit);
            $kredit = (int) Crypt::decrypt($detail->kredit);
        @endphp
        <tr>
            <input type="hidden" name="id_transaksi" value="{{ $detail->id_transaksi }}">
            <td>
                <input name="tgl" type="date" value="{{ $detail->tgl }}" class="form-control">
            </td>
            <td>
                <input name="debit" value="{{ $debit }}" type="text" x-mask:dynamic="$money($input)"
                    class="form-control">
            </td>
            <td>
                <input name="kredit" value="{{ $kredit }}" type="text" x-mask:dynamic="$money($input)"
                    class="form-control">
            </td>
            <td>
                <input name="ket" type="text" value="{{ $detail->ket }}" id="ket" class="form-control">

            </td>
        </tr>
    </tbody>
</table>
