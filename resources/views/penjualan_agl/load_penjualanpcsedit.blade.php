<table class="table table-striped ">
    <thead>
        <tr>
            <th width="2%">#</th>
            <th width="10%">Produk</th>
            <th width="10%" style="text-align: right">Pcs</th>
            <th width="10%" style="text-align: right">Kg</th>
            <th width="10%" style="text-align: right">Rp Satuan</th>
            <th width="10%" style="text-align: right">Total Rp</th>
            <th width="5%">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($invoice as $no => $i)
        <tr class="baris{{$no+1}}">
            <td></td>
            <td>
                <select name="id_produk[]" class="select" required>
                    <option value="">-Pilih Produk-</option>
                    @foreach ($produk as $p)
                    <option value="{{$p->id_produk_telur}}" {{$i->id_produk == $p->id_produk_telur ? 'selected' :
                        ''}}>{{$p->nm_telur}}</option>
                    @endforeach
                </select>
            </td>
            <td align="right">
                <input type="text" class="form-control tipe_pcs tipe_pcs{{$no+1}}" count="{{$no+1}}"
                    style="text-align: right" value="{{number_format($i->pcs,0,',','.')}}" required>
                <input type="hidden" class="form-control  tipe_pcs_biasa{{$no+1}}" name="pcs[]" value="{{$i->pcs}}">
            </td>
            <td align="right">
                <input type="text" class="form-control tipe_kg tipe_kg{{$no+1}}" count="{{$no+1}}"
                    style="text-align: right" value="{{number_format($i->kg,2,',','.')}}" required>
                <input type="hidden" class="form-control tipe_kgbiasa tipe_kgbiasa{{$no+1}}" name="kg[]"
                    count="{{$no+1}}" value="{{$i->kg}}">
            </td>
            <td align="right">
                <input type="text" class="form-control tipe_rp_satuan tipe_rp_satuan{{$no+1}}" count="{{$no+1}}"
                    style="text-align: right" required value="Rp {{number_format($i->rp_satuan,0,',','.')}}">

                <input type="hidden" class="form-control tipe_rp_satuanbiasa{{$no+1}}" name="rp_satuan[]"
                    value="{{$i->rp_satuan}}">
                <input type="hidden" class="form-control ttl_rpbiasa tipe_ttl_rpbiasa{{$no+1}}" name="total_rp[]"
                    value="{{$i->total_rp}}">
            </td>
            <td align="right" class="tipe_ttl_rp{{$no+1}}">Rp {{number_format($i->total_rp,2,',','.')}}</td>
            <td style="vertical-align: top;">
                <button type="button" class="btn rounded-pill remove_baris_pcs" count="{{$no+1}}"><i
                        class="fas fa-trash text-danger"></i>
                </button>
            </td>
        </tr>
        @endforeach


    </tbody>
    <tbody id="tb_baris_pcs">

    </tbody>
    <tfoot>
        <tr>
            <th colspan="9">
                <button type="button" class="btn btn-block btn-lg tbh_baris_pcs"
                    style="background-color: #F4F7F9; color: #8FA8BD; font-size: 14px; padding: 13px;">
                    <i class="fas fa-plus"></i> Tambah Baris Baru

                </button>
            </th>
        </tr>
    </tfoot>


</table>