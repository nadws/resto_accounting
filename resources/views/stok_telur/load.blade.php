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
            <th width="20%">Kandang</th>
            <th width="20%">Produk</th>
            <th width="15%">Pcs</th>
            <th width="15%">Kg</th>
            <th width="15%">Ikat</th>
            <th width="5%">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <tr class="baris1">
            <td>
                <select name="id_kandang[]" id="" class="select">
                    <option value="">Pilih Kandang</option>
                    @foreach ($kandang as $k)
                    <option value="{{$k->id_kandang}}">{{$k->nm_kandang}}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <select name="id_produk_telur[]" id="" class="select">
                    <option value="">Pilih Produk</option>
                    @foreach ($produk as $p)
                    <option value="{{$p->id_produk_telur}}">{{$p->nm_telur}}</option>
                    @endforeach
                </select>
            </td>
            <td><input type="text" name="pcs[]" class="form-control" value="0"></td>
            <td><input type="text" name="kg[]" class="form-control" value="0"></td>
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