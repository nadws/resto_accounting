<tr class="baris{{$count}}">
    <td>
        <div class="load_produk_stok{{$count}}"></div>
    </td>
    <td><input x-mask:dynamic="$money($input)" type="text" class="form-control text-end" name="debit[]"></td>
    <td><input x-mask:dynamic="$money($input)" type="text" name="total_rp[]" class="form-control text-end"></td>
    <td style="vertical-align: top;">
        <button count="{{$count}}" type="button" class="btn rounded-pill remove_baris" count="1"><i
                class="fas fa-trash text-danger"></i>
        </button>
    </td>
</tr>