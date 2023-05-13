<tr class="baris{{$count}}">
    <td><input type="date" class="form-control" name="tgl_pembayaran[]" value="{{date('Y-m-d')}}"></td>
    <td>
        <select name="id_akun[]" id="" class="select" required>
            <option value="">Pilih Akun Pembayaran</option>
            <option value="4">KAS BESAR</option>
            <option value="10">BANK MANDIRI NO.REK 031-00-5108889-9</option>
            <option value="45">PENDAPATAN  DILUAR USAHA</option>
        </select>
    </td>
    <td>
        <input type="text" class="form-control" name="ket[]" value="Pembayaran BKIN {{$p->no_nota}}">
    </td>
    <td>{{$p->nm_suplier}}</td>
    <td>{{$p->suplier_akhir}}</td>
    <td align="right">
        <input type="text" class="form-control bayardebit bayardebit{{$count}} text-end" count="{{$count}}">
        <input type="hidden" name="debit[]" class="form-control bayardebitbiasa bayardebitbiasa{{$count}}" value="0">
    </td>
    <td align="right">
        <input type="text" class="form-control bayar bayar{{$count}} text-end" count="{{$count}}">
        <input type="hidden" name="kredit[]" class="form-control bayarbiasa bayarbiasa{{$count}}" value="0">
    </td>
    <td>
        <button type="button" class="btn rounded-pill remove_baris" count="{{$count}}"><i
                class="fas fa-trash text-danger"></i>
        </button>
    </td>
</tr>