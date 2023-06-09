<tr class="baris2{{ $count }}">
    <td>
        <select required name="akun_pembayaran[]" class="form-control select2" id="">
            <option value="">- Pilih Akun -</option>
            @foreach ($akun as $d)
                <option value="{{ $d->id_akun }}">{{ strtoupper($d->nm_akun) }}
                </option>
            @endforeach
        </select>
    </td>
    <td>
        <input type="text" class="form-control dikanan pembayaranDebit-nohide text-end"
            value="Rp. 0" count="{{ $count }}">
        <input type="text" class="form-control dikanan debit pembayaranDebit-hide{{ $count }}" value="0"
            name="debit[]">
    </td>
    <td>
        <input type="text" class="form-control dikanan pembayaranKredit-nohide text-end" value="Rp. 0"
            count="{{$count}}">
        <input type="text" class="form-control dikanan kredit pembayaranKredit-hide{{$count}}" value="0" name="kredit[]">
    </td>
    <td align="center">
        <button type="button" class="btn rounded-pill remove_baris2" count="{{ $count }}"><i
                class="fas fa-trash text-danger"></i>
        </button>
    </td>
</tr>
