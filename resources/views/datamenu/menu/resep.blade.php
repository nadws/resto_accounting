<tr class="baris{{ $count }}">
    <td>
        <select name="id_bahan[]" id="" class="select_resep id_bahan" count="{{ $count }}">
            <option value="">Pilih Bahan</option>
            @foreach ($bahan as $b)
                <option value="{{ $b->id_list_bahan }}">{{ $b->nm_bahan }}</option>
            @endforeach
        </select>
    </td>
    <td><input type="text" class="form-control" name="qty[]" value="0">
    </td>
    <td><input type="text" class="form-control nm_bahan{{ $count }}" value="" readonly></td>
    <td class="text-center"><button type="button" class="btn btn-rounded remove_baris" count="{{ $count }}"><i
                class="fas fa-trash text-danger"></i></button></td>
</tr>
