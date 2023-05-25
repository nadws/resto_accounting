<form id="formTambahAkun">
    <div class="row">
        <div class="col-lg-1">
            <label for="">Urutan</label>
            <input type="text" name="urutan" class="form-control">
            <input type="hidden" name="jenis" class="form-control jenis" value="{{$jenis}}">
        </div>
        <div class="col-lg-5">
            <label for="">Kategori</label>
            <input type="text" name="nama" class="form-control">
        </div>
        <div class="col-lg-2">
            <label for="">Aksi</label> <br>
            <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
        </div>
    </div>
</form>
<style>
    .dhead {
        background-color: #435EBE !important;
        color: white;
    }
</style>
<form id="Editinputanakun">
    <div class="row">
        <div class="col-lg-12 mt-4">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="dhead" width="5%">Urutan</th>
                        <th class="dhead">Akun</th>
                        <th class="dhead">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <input type="hidden" name="jenis" class="form-control jenis" value="{{$jenis}}">
                    @foreach ($cash as $c)
                    <tr>
                        <td>
                            <input type="hidden" name="id_kategori_cashcontrol[]" class="form-control"
                                value="{{$c->id_kategori_cashcontrol}}">
                            <input type="text" name="urutan[]" class="form-control" value="{{$c->urutan}}">
                        </td>
                        <td><input type="text" name="nama[]" class="form-control" value="{{$c->nama}}"></td>
                        <td align="center">
                            <a href="#" onclick="event.preventDefault();"
                                class="btn btn-sm btn-danger delete_kategori_akun"
                                id_kategori_cashcontrol="{{$c->id_kategori_cashcontrol }}" jenis="{{$jenis}}"><i
                                    class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-lg-10">

        </div>
        <div class="col-lg-2">
            <button type="submit" class="btn btn-primary btn-sm float-end">Simpan</button>
        </div>
    </div>
</form>