<table width="100%" cellpadding="10px">
    <tr>
        <th style="background-color: white;" width="10%">Tanggal</th>
        <th style="background-color: white;" width="2%">:</th>
        <th style="background-color: white;">{{tanggal($detail->tgl)}}</th>
        <th style="background-color: white;" width="10%">No Nota</th>
        <th style="background-color: white;" width="2%">:</th>
        <th style="background-color: white;">{{$detail->no_nota}}</th>
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
            <th class="dhead">Telur</th>
            <th class="dhead">Keterangan</th>
            <th class="dhead" style="text-align: right">Pcs</th>
            <th class="dhead" style="text-align: right">Kg</th>
            <th class="dhead">Admin</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($stok as $no => $a)
        <tr>
            <td>{{$no + 1}}</td>
            <td>{{$a->nm_telur}}</td>
            <td>{{$a->ket}}</td>
            <td align="right">{{number_format($a->pcs,0)}}</td>
            <td align="right">{{number_format($a->kg,0)}}</td>
            <td>{{$a->admin}}</td>
        </tr>
        @endforeach
    </tbody>
</table>