<tr class="baris{{$count}}">
    <td></td>
    <td>
        <select name="id_produk[]" class="select" required>
            <option value="">-Pilih Produk-</option>
            @foreach ($produk as $p)
            <option value="{{$p->id_produk_telur}}">{{$p->nm_telur}}</option>
            @endforeach
        </select>
    </td>
    <td align="right">
        <input type="text" class="form-control tipe_pcs tipe_pcs{{$count}}" count="{{$count}}" style="text-align: right"
            required>
        <input type="hidden" class="form-control  tipe_pcs_biasa{{$count}}" name="pcs[]" value="0">
    </td>
    <td align="right">
        <input type="text" class="form-control tipe_kg tipe_kg{{$count}}" count="{{$count}}" style="text-align: right"
            required>
        <input type="hidden" class="form-control tipe_kgbiasa tipe_kgbiasa{{$count}}" name="kg[]" count="{{$count}}"
            value="0">
    </td>
    <td align="right">
        <input type="text" class="form-control tipe_rp_satuan tipe_rp_satuan{{$count}}" count="{{$count}}"
            style="text-align: right" required>
        <input type="hidden" class="form-control tipe_rp_satuanbiasa{{$count}}" name="rp_satuan[]" value="0">
        <input type="hidden" class="form-control ttl_rpbiasa tipe_ttl_rpbiasa{{$count}}" name="total_rp[]" value="0">
    </td>
    <td align="right" class="tipe_ttl_rp{{$count}}"></td>
    <td style="vertical-align: top;">
        <button type="button" class="btn rounded-pill remove_baris_pcs" count="{{$count}}"><i
                class="fas fa-trash text-danger"></i>
        </button>
    </td>
</tr>