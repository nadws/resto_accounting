<tr class="baris{{$count}}">
    <td>
        <select name="id_telur[]" id="" class="select pilih_telur pilih_telur{{$count}}" count="{{$count}}">
            <option value="">Pilih Produk</option>
            @foreach ($produk as $p)
            <option value="{{$p->id_produk_telur}}">{{$p->nm_telur}}</option>
            @endforeach
        </select>
    </td>
    <td><input type="text" name="ket[]" class="form-control"></td>
    <td class="pcs_telur{{$count}}" align="right"></td>
    <td><input type="text" name="pcs[]" class="form-control" style="text-align: right" value="0"></td>
    <td class="kg_telur{{$count}}" align="right"></td>
    <td><input type="text" name="kg[]" class="form-control" style="text-align: right" value="0"></td>
    <td></td>
    <td><button type="button" class="btn rounded-pill remove_baris" count="{{$count}}"><i
                class="fas fa-trash text-danger"></i>
        </button></td>
</tr>