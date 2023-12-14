<div class="row">
    <table class="table table-striped table-bordered" id="tblPost">
        <thead>
            <tr>
                <th class="dhead">#</th>
                <th class="dhead">Nama Post Center</th>
                <th class="dhead">Aksi</th>
            </tr>
        </thead>
        <tbody>
                <tr>
                    <td></td>
                    <td>
                        <input type="hidden" class="form-control" value="{{ $id_akun }}" name="id_akun">
                        <input type="text" class="form-control" name="nm_post">
                    </td>
                    <td><button class="btn btn-sm btn-primary" type="submit">save</button></td>
                </tr>
            @foreach ($post_center as $i => $d)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $d->nm_post }}</td>
                    <td align="center">
                        <a href="#" class="btn btn-sm btn-primary edit_post" id_post="{{ $d->id_post_center }}"><i
                                class="fas fa-pen"></i></a>
                        <a href="#" class="btn btn-sm btn-danger hapus_post" id_akun="{{ $id_akun }}" id_post="{{ $d->id_post_center }}"><i
                                class="fas fa-trash-alt"></i></a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
