<tr class="baris{{$count}}">
    <td>
        <select name="id_akun[]" class="form-control select2" count="{{ $count }}">
            <option value="">- Pilih Akun Setor -</option>
            @foreach ($akun as $d)
                <option value="{{ $d->id_akun }}">{{ ucwords($d->nm_akun) }}</option>
            @endforeach
        </select>
    </td>
    <td>
        <input type="text" class="form-control setor-nohide text-end"
            value="Rp 0" count="{{ $count }}">
        <input type="hidden" class="form-control setor-hide setor-hide{{$count}}" count="{{ $count }}"
            value="" name="debit[]">
    </td>
    <td>
        <input type="text" class="form-control kredit-nohide text-end"
            value="Rp 0" count="{{ $count }}">
        <input type="hidden" class="form-control kredit-hide kredit-hide{{$count}}" count="{{ $count }}"
            value="" name="kredit[]">
    </td>
    <td align="center">
        <button type="button" class="btn rounded-pill remove_baris" count="{{$count}}"><i
                class="fas fa-trash text-danger"></i>
        </button>
    </td>
</tr>