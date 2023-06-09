<table class="table table-striped ">
    <thead>
        <tr>
            <th width="2%">#</th>
            <th width="10%">Produk</th>
            <th width="10%" style="text-align: right">Pcs</th>
            <th width="10%" style="text-align: right">Kg</th>
            <th width="10%" style="text-align: right">Ikat</th>
            <th width="10%" style="text-align: right">Kg(-rak)</th>
            <th width="10%" style="text-align: right">Rp Satuan</th>
            <th width="10%" style="text-align: right">Total Rp</th>
            <th width="5%">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <tr class="baris1">
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
                <input type="text" class="form-control pcs pcs1" count="1" style="text-align: right" required>
                <input type="hidden" class="form-control  pcs_biasa1" name="pcs[]" value="0">
            </td>
            <td align="right">
                <input type="text" class="form-control kg kg1" count="1" style="text-align: right" required>
                <input type="hidden" class="form-control kgbiasa kgbiasa1" name="kg[]" count="1" value="0">
            </td>
            <td align="right" class="ikat1"></td>
            <td align="right" class="kgminrak1">

            </td>
            <td align="right">
                <input type="text" class="form-control rp_satuan rp_satuan1" count="1" style="text-align: right"
                    required>
                <input type="hidden" class="form-control kgminrakbiasa1" name="kg_jual[]" value="0">
                <input type="hidden" class="form-control rp_satuanbiasa1" name="rp_satuan[]" value="0">
                <input type="hidden" class="form-control ttl_rpbiasa ttl_rpbiasa1" name="total_rp[]" value="0">
            </td>
            <td align="right" class="ttl_rp1"></td>
            <td style="vertical-align: top;">
                <button type="button" class="btn rounded-pill remove_baris_kg" count="1"><i
                        class="fas fa-trash text-danger"></i>
                </button>
            </td>
        </tr>
        <tr class="baris2">
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
                <input type="text" class="form-control pcs pcs2" count="2" style="text-align: right" required>
                <input type="hidden" class="form-control  pcs_biasa2" value="0" name="pcs[]">
            </td>
            <td align="right">
                <input type="text" class="form-control kg kg2" count="2" style="text-align: right" required>
                <input type="hidden" class="form-control kgbiasa kgbiasa2" count="2" value="0" name="kg[]">
            </td>
            <td align="right" class="ikat2"></td>
            <td align="right" class="kgminrak2">

            </td>
            <td align="right">
                <input type="text" class="form-control rp_satuan rp_satuan2" count="2" style="text-align: right"
                    required>
                <input type="hidden" class="form-control kgminrakbiasa2" value="0" name="kg_jual[]">
                <input type="hidden" class="form-control rp_satuanbiasa2" value="0" name="rp_satuan[]">
                <input type="hidden" class="form-control ttl_rpbiasa ttl_rpbiasa2" value="0" name="total_rp[]">
            </td>
            <td align="right" class="ttl_rp2"></td>
            <td style="vertical-align: top;">
                <button type="button" class="btn rounded-pill remove_baris_kg" count="2"><i
                        class="fas fa-trash text-danger"></i>
                </button>
            </td>
        </tr>

    </tbody>
    <tbody id="tb_baris_kg">

    </tbody>
    <tfoot>
        <tr>
            <th colspan="9">
                <button type="button" class="btn btn-block btn-lg tbh_baris_kg"
                    style="background-color: #F4F7F9; color: #8FA8BD; font-size: 14px; padding: 13px;">
                    <i class="fas fa-plus"></i> Tambah Baris Baru

                </button>
            </th>
        </tr>
    </tfoot>


</table>