<div class="card">
    <div class="card-header">
        <h6 class="text-success float-start">Daftar Akun</h6>
        <x-theme.button modal="Y" idModal="tambah" icon="fa-plus" variant="primary" addClass="float-end"
            teks="Akun" />
    </div>
    <div class="card-body">
        <table class="table" id="table_akun">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kode Akun</th>
                    <th>Nama Akun</th>
                    <th>Klasifikasi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($akun as $no => $a)
                    <tr>
                        <td>{{ $no + 1 }}</td>
                        <td>{{ $a->kode_akun }}</td>
                        <td>{{ $a->nm_akun }}</td>
                        <td>{{ $a->nm_subklasifikasi }}</td>
                        <td>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#edit"
                                class="btn btn-sm btn-warning edit_akun" id_akun="{{ $a->id_akun }}"><i
                                    class="fas fa-edit"></i></a>
                            <a href="" class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
