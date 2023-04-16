<table>
    <thead>
        <tr>
            <th width="39px">#</th>
            <th width="110px">Tanggal</th>
            <th width="117px">No Nota</th>
            <th width="90px">No Urut</th>
            <th width="350px">Akun</th>
            <th width="149px">Proyek</th>
            <th width="149px">Keterangan</th>
            <th width="129px" style="text-align: right">Debit</th>
            <th width="129px" style="text-align: right">Kredit</th>
            <th width="70px">Admin</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($jurnal as $no => $a)
        <tr>
            <td>{{$no + 1}}</td>
            <td>{{date('d-m-Y',strtotime($a->tgl))}}</td>
            <td>{{$a->no_nota}}</td>
            <td>{{$a->no_urut}}</td>
            <td>{{$a->akun->nm_akun}}</td>
            <td>{{empty($a->proyek->nm_proyek) ? '' : $a->proyek->nm_proyek }}</td>
            <td>{{$a->ket}}</td>
            <td align="right">{{number_format($a->debit,0)}}</td>
            <td align="right">{{number_format($a->kredit,0)}}</td>
            <td>{{$a->admin}}</td>
        </tr>
        @endforeach
    </tbody>
</table>