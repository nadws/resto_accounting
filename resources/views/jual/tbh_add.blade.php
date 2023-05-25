
<tr class="baris{{$count}}">
    <td>
        <input type="date" value="{{ date('Y-m-d') }}" class="form-control" name="tgl[]">
    </td>
    <td>
        <input type="text" class="form-control" name="no_penjualan[]">
    </td>
    <td>
        <input type="text" class="form-control" name="ket[]">
    </td>
    <td>
        <input type="text" class="form-control dikanan setor-nohide text-end"
            value="Rp. 0" count="{{$count}}">
        <input type="hidden"
            class="form-control dikanan setor-hide setor-hide{{$count}}"
            value="" name="total_rp[]">
    </td>
    <td align="center">
        <button type="button" class="btn rounded-pill remove_baris" count="{{$count}}"><i
                class="fas fa-trash text-danger"></i>
        </button>
    </td>
</tr>