<table class="table" width="100%" id="tableScroll">
    <thead>
        <tr>
            <th width="5">#</th>
            <th>Kode Akun</th>
            <th>Inisial</th>
            <th>Nama Akun</th>
            <th>ID Klasifikasi</th>
            <th>Klasifikasi</th>
            <th>Iktisar</th>
    </thead>
    <tbody>
        <tr class="fw-bold induk_detail">
            <td>kosongkan untuk menambah akun, dibiarkan untuk edit</td>
            <td></td>
            <td></td>
            <td></td>
            <td>tambahlan id klasifikasi untuk tambah akun</td>
            <td></td>
            <td></td>
        </tr>
        @foreach ($query as $no => $d)
            <tr class="fw-bold induk_detail">
                <td>{{ $d->id_akun }}</td>
                <td>{{ $d->kode_akun }}</td>
                <td>{{ $d->inisial }}</td>
                <td>{{ ucwords(strtolower($d->nm_akun)) }}</td>
                <td>{{ $d->id_klasifikasi }}</td>
                <td>{{ $d->nm_subklasifikasi }}</td>
                <td>{{ $d->iktisar }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
