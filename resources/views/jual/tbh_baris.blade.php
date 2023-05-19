<tr class="baris{{$count}}">
    <td colspan="1"></td>
    <td>
        <select name="id_akun[]" class="form-control select2" count="{{ $count }}">
            <option value="">- Pilih Akun -</option>
            @foreach ($akun as $d)
                <option value="{{ $d->id_akun }}">{{ ucwords($d->nm_akun) }}</option>
            @endforeach
        </select>
    </td>
    <td>
        <input type="text" class="form-control debit_rupiah text-end"
            value="Rp 0" count="{{ $count }}">
        <input type="hidden" class="form-control debit_biasa debit_biasa{{$count}}" count="{{ $count }}"
            value="" name="setor[]">
    </td>
    <td align="center">
        <button type="button" class="btn rounded-pill remove_baris" count="{{$count}}"><i
                class="fas fa-trash text-danger"></i>
        </button>
    </td>
</tr>