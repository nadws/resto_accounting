<table class="table table-bordered" id="table2">
    <thead>
        <tr>
            <th>#</th>
            <th>Akun</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($akun as $no => $a)
        <tr>
            <td>{{$no+1}}</td>
            <td>{{$a->nm_akun}}</td>
            <td align="center">
                <span class="badge {{ empty($a->id_akun) ? 'bg-danger' : 'bg-success' }}">
                    {{empty($a->id_akun) ? 'Tidak Masuk' : 'Masuk' }}
                </span>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>