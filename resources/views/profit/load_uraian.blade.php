<table class="table" id="table2">
    <thead>
        <tr>
            <th width="10%" style="white-space: nowrap">Urutan</th>
            <th>Sub Kategori</th>
            <th align="center">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <form id="formSubKategori">
            <input type="hidden" class="jenisSub" name="jenis">
            <tr>
                <td><input id="urutanInput" type="number" class="form-control urutanInput"></td>
                <td>
            <input id="sub_kategoriInput" type="text" class="form-control sub_kategoriInput">
                </td>
                <td><button class="btn btn-sm btn-primary" type="button" id="btnFormSubKategori">Save</button></td>
            </tr>
        </form>
        @foreach ($subKategori as $no => $d)
        <input type="text" style="display: none;" name="id_edit[]" value="{{ $d->id }}">
        <input type="text" style="display: none;" name="jenis_edit[]" value="{{ $d->jenis }}">
        <tr>
            <td><input value="{{ $d->urutan }}" id="urutan" type="number" class="form-control" name="urutan_edit[]">
            </td>
            <td><input value="{{ $d->sub_kategori }}" id="sub_kategori" type="text" class="form-control"
                    name="sub_kategori_edit[]"></td>
            <td>
                <center>

                    <a href="#" class="btn btn-sm btn-danger btnDeleteSubKategori" id_jenis="{{ $d->jenis }}"
                        id="{{$d->id}}"><i class="fas fa-trash-alt"></i></a>
                </center>

            </td>
        </tr>
        @endforeach
    </tbody>
</table>