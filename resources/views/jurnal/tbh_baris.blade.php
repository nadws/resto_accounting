<tr id="baris{{$count}}">
    <td>
        <select name="" id="" class="select">
            <option value="">Pilih</option>
            @foreach ($akun as $a)
            <option value="{{$a->id_akun}}">{{$a->nm_akun}}</option>
            @endforeach
        </select>
    </td>
    <td><input type="text" class="form-control"></td>
    <td>
        <input type="text" class="form-control debit_rupiah text-end" value="Rp 0" count="{{$count}}">
        <input type="hidden" class="form-control debit_biasa debit_biasa{{$count}}" value="0" name="debit[]">
    </td>
    <td>
        <input type="text" class="form-control kredit_rupiah text-end" value="Rp 0" count="{{$count}}">
        <input type="hidden" class="form-control kredit_biasa kredit_biasa{{$count}}" value="0" name="debit[]">
    </td>
    <td>
        <button type="button" class="btn rounded-pill remove_baris" count="{{$count}}"><i
                class="fas fa-trash text-danger"></i>
        </button>
    </td>
</tr>