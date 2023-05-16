<table width="100%" cellpadding="10px">
    <tr>
        <th width="10%">Tanggal</th>
        <th width="2%">:</th>
        <th>{{date('d-m-Y',strtotime($head_jurnal->tgl))}}</th>
        <th width="10%">No Nota</th>
        <th width="2%">:</th>
        <th>{{$head_jurnal->no_nota}}</th>
    </tr>
    <tr>
        <th width="10%">Proyek</th>
        <th width="2%">:</th>
        <th>{{$head_jurnal->nm_proyek}}</th>
    </tr>
</table>
<br>
<br>

<style>
    .dhead {
        background-color: #435EBE !important;
        color: white;
    }

    .dborder {
        border-color: #435EBE
    }
</style>
<table class="table table-hover table-bordered dborder">
    <thead>
        <tr>
            <th class="dhead" width="5">#</th>
            <th class="dhead">Akun</th>
            <th class="dhead">Keterangan</th>
            <th class="dhead" style="text-align: right">Debit</th>
            <th class="dhead" style="text-align: right">Kredit</th>
            <th class="dhead" style="text-align: right">Admin</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($jurnal as $no => $a)
        <tr>
            <td>{{$no + 1}}</td>
            <td>{{$a->akun->nm_akun}}</td>
            <td>{{$a->ket}}</td>
            <td align="right">{{number_format($a->debit,0)}}</td>
            <td align="right">{{number_format($a->kredit,0)}}</td>
            <td>{{$a->admin}}</td>
        </tr>
        @endforeach
    </tbody>
</table>