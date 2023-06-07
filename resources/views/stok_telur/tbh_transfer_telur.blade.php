<tr class="baris{{$count}}">
    <td>
        <select name="id_produk_telur[]" id="" class="select">
            <option value="">Pilih Produk</option>
            @foreach ($produk as $p)
            <option value="{{$p->id_produk_telur}}">{{$p->nm_telur}}</option>
            @endforeach
        </select>
    </td>
    <td><input type="text" name="ket[]" class="form-control"></td>
    <td><input type="text" name="pcs[]" class="form-control" value="0"></td>
    <td><input type="text" name="kg[]" class="form-control" value="0"></td>
    <td></td>
    <td><button type="button" class="btn rounded-pill remove_baris" count="{{$count}}"><i
                class="fas fa-trash text-danger"></i>
        </button></td>
</tr>