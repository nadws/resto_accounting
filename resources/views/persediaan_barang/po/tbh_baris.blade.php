<tr class="baris{{$count}}">
    <td>
        <select name="example" class="form-control select2" id="">
            <option value="">- Pilih Produk -</option>
            @foreach ($produk as $p)
                <option value="{{ $p->id_produk }}">{{ $p->nm_produk }}</option>
            @endforeach
        </select>
    </td>
    <td>
        <input type="number" min="0" class="form-control dikanan">
    </td>
    <td>
        <select name="example" class="form-control select2" id="">
            <option value="">- Pilih Satuan -</option>
            @foreach ($satuan as $s)
                <option value="{{ $s->id_satuan }}">{{ strtoupper($s->nm_satuan) }}</option>
            @endforeach
        </select>
    </td>
    <td>
        <input type="text" class="form-control dikanan rp-nohide text-end" value="Rp 0"
            count="{{ $count }}">
        <input type="hidden" class="form-control dikanan rp-hide rp-hide{{ $count }}" value="0"
            name="rp_satuan[]">
    </td>
    <td style="vertical-align: top;">
        <button type="button" class="btn rounded-pill remove_baris" count="{{ $count }}"><i
                class="fas fa-trash text-danger"></i>
        </button>
    </td>
</tr>