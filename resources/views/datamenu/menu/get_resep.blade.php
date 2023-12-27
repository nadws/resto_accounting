<h6>Menu : {{ $menu->nm_menu }}</h6>
<br>
<input type="hidden" name="id_menu" value="{{ $menu->id_menu }}">
<table class="table table-bordered">
    <thead>
        <tr>
            <th width="50">Bahan</th>
            <th width="10">Qty</th>
            <th width="10">Satuan</th>
            <th width="5">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($resep as $no => $r)
            <tr class="baris{{ $no + 1 }}">
                <td>
                    <select name="id_bahan[]" id="" class="select_edit_resep id_bahan" count="1">
                        <option value="">Pilih Bahan</option>
                        @foreach ($bahan as $b)
                            <option value="{{ $b->id_list_bahan }}"
                                {{ $r->id_bahan == $b->id_list_bahan ? 'selected' : '' }}>{{ $b->nm_bahan }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td><input type="text" class="form-control" name="qty[]" value="{{ $r->qty }}">
                </td>
                <td><input type="text" class="form-control nm_bahan1" value="{{ $r->nm_satuan }}" readonly>
                </td>
                <td class="text-center"><button type="button" class="btn btn-rounded remove_baris" count="{{ $no + 1 }}"><i
                            class="fas fa-trash text-danger"></i></button>
                </td>
            </tr>
        @endforeach
        <tr>
    <tbody class="load_tambah_resep"></tbody>
    </tr>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="4"><button type="button" class="btn btn-primary btn-block tambah_baris_resep">Tambah
                    Baris</button>
            </td>
        </tr>
    </tfoot>
</table>
