<table>

</table>
<div class="row">
    <form id="formSubKategori">
        <input type="hidden" class="jenisSub" name="jenis">

        <div class="col-lg-3">
            <div class="form-group">
                <label for="">urutan</label>
                <input id="urutanInput" type="number" class="form-control urutanInput">
            </div>
        </div>
        <div class="col-lg-4">
            <div class="form-group">
                <label for="">Nama Kategori</label>
                <input id="sub_kategoriInput" type="text" class="form-control sub_kategoriInput">
            </div>
        </div>
        <div class="col-lg-3">
            <label for="">Aksi</label><br>
            <button class="btn btn-sm btn-primary" type="button" id="btnFormSubKategori"><i class="fas fa-plus"></i> Tambah Kategori</button>
        </div>
    </form>
</div>
<table class="table" id="table2">
    <thead>
        <tr>
            <th width="10%" style="white-space: nowrap">Urutan</th>
            <th>Sub Kategori</th>
            <th align="center">Aksi</th>
        </tr>
    </thead>
    <tbody>


        <form action="formUraian">
            @foreach ($subKategori as $no => $d)
                <input type="text" style="display: none;" name="id_edit[]" value="{{ $d->id }}">
                <input type="text" style="display: none;" name="jenis_edit[]" value="{{ $d->jenis }}">
                <tr>
                    <td>
                        <input value="{{ $d->urutan }}" id="urutan" type="number" class="form-control"
                            name="urutan[]">
                    </td>
                    <td>
                        <input value="{{ $d->sub_kategori }}" id="sub_kategori" type="text" class="form-control"
                            name="nm_kategori[]">
                    </td>
                    <td>
                        <center>
                            <a href="#" class="btn btn-sm btn-danger btnDeleteSubKategori"
                                id_jenis="{{ $d->jenis }}" id="{{ $d->id }}"><i
                                    class="fas fa-trash-alt"></i></a>
                        </center>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td></td>
                <td></td>
                <td align="center">
                    <button class="btn btn-sm btn-primary" type="submit">Edit & Save</button>
                </td>
            </tr>
        </form>
    </tbody>
</table>
