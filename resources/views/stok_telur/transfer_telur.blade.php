<style>
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #000000;
        line-height: 36px;
        /* font-size: 12px; */
        width: 150px;

    }
</style>
<table class="table table-striped">
    <thead>
        <tr>
            <th width="20%">Produk</th>
            <th width="20%">Keterangan</th>
            <th width="15%" style="text-align: right">Pcs Stok</th>
            <th width="15%" style="text-align: right">Pcs</th>
            <th width="15%" style="text-align: right">Kg Stok</th>
            <th width="15%" style="text-align: right">Kg</th>
            <th width="15%">Ikat</th>
            <th width="5%">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <tr class="baris1">
            <td>
                <select name="id_telur[]" id="" class="select pilih_telur pilih_telur1" count="1">
                    <option value="">Pilih Produk</option>
                    @foreach ($produk as $p)
                    <option value="{{$p->id_produk_telur}}">{{$p->nm_telur}}</option>
                    @endforeach
                </select>
            </td>
            <td><input type="text" name="ket[]" class="form-control"></td>
            <td class="pcs_telur1" align="right"></td>
            <td><input type="text" name="pcs[]" class="form-control" style="text-align: right" value="0"></td>
            <td class="kg_telur1" align="right"></td>
            <td><input type="text" name="kg[]" class="form-control" style="text-align: right" value="0"></td>
            <td></td>
            <td><button type="button" class="btn rounded-pill remove_baris" count="1"><i
                        class="fas fa-trash text-danger"></i>
                </button></td>
        </tr>
    </tbody>
    <tbody id="tb_baris">

    </tbody>
    <tfoot>
        <tr>
            <th colspan="9">
                <button type="button" class="btn btn-block btn-lg tbh_baris"
                    style="background-color: #F4F7F9; color: #8FA8BD; font-size: 14px; padding: 13px;">
                    <i class="fas fa-plus"></i> Tambah Baris Baru

                </button>
            </th>
        </tr>
    </tfoot>
</table>