<div class="card">
    <div class="card-header">
        <h6 class="text-success">Daftar Akun</h6>
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
                        <td></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
