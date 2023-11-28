<div class="row">
    <table class="table" id="tblProfit">
        <thead>
            <tr>
                <th class="dhead">#</th>
                <th class="dhead">Kode</th>
                <th class="dhead">Inisial</th>
                <th class="dhead">Nama Akun</th>
                <th class="dhead">klasifikasi</th>
                <th class="dhead">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($akun as $i => $d)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $d->kode_akun }}</td>
                    <td>{{ $d->inisial }}</td>
                    <td>{{ $d->nm_akun }}</td>
                    <td>{{ $d->nm_klasifikasi }}</td>
                    <td>
                        <a href="#" class="btn btn-sm icon btn-primary edit" id_akun="{{ $d->id_akun }}"><i class="bi bi-pencil"></i></a>
                        <a href="#" class="btn btn-sm icon btn-danger hapus" id_akun="{{ $d->id_akun }}"><i class="bi bi-trash"></i></a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

