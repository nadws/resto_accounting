<tr class="baris{{ $count }}" x-data="{}">
    <td>{{ $count }}</td>
    <td>
        <select required name="id_bahan[]" class="select-tbh form-control">
            <option value="">Pilih Bahan</option>
            @foreach ($bahan as $b)
                <option value="{{ $b->id_list_bahan }}">{{ strtoupper($b->nm_bahan) }}</option>
            @endforeach
        </select>
    </td>
    <td>
        <input required x-mask:dynamic="$money($input)" type="text"
            class="form-control selectAll text-end qtyKeyup qtyKeyup{{ $count }}" count="{{ $count }}"
            placeholder="qty" name="qty[]">
    </td>
    <td>
        <select required name="id_satuan_beli[]" class="select-tbh form-control selectAll">
            <option value="">Pilih Satuan Beli</option>
            @foreach ($satuan as $s)
                <option value="{{ $s->id_satuan }}">{{ strtoupper($s->nm_satuan) }}</option>
            @endforeach
        </select>
    </td>
    <td>
        <input name="persen[]" type="number" class="form-control selectAll text-end persenKeyup persenKeyup{{$count}}"
            count="{{$count}}" x-mask:dynamic="$money($input)" max="100">
    </td>
    <td>
        <input type="hidden" class="pajakValue pajakValue{{$count}}">
        <input name="pajak[]" type="number" class="form-control selectAll pajak text-end pajakKeyup pajakKeyup{{$count}}"
            count="{{$count}}" x-mask:dynamic="$money($input)" max="100">
    </td>
    <td>
        <input required x-mask:dynamic="$money($input)" type="text"
            class="form-control selectAll text-end rpSatuanKeyup rpSatuanKeyup{{ $count }}" placeholder="rp satuan"
            name="rp_satuan[]" count="{{ $count }}">
    </td>
    <td>
        <input x-mask:dynamic="$money($input)" readonly type="text"
            class="form-control selectAll text-end ttlRp totalRpKeyup{{ $count }}" placeholder="total rp" name="ttl_rp[]">
    </td>
    <td>
        <input type="text" placeholder="keterangan" class="form-control selectAll" name="ket[]">
    </td>
    <td style="vertical-align: top;">
        <button type="button" class="btn rounded-pill remove_baris" count="{{ $count }}"><i
                class="fas fa-trash text-danger"></i>
        </button>
    </td>
</tr>
