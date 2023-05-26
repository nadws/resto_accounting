@foreach ($bayar as $n => $b)
<tr class="show_detail{{$b->no_nota}}">
    <td></td>
    <td></td>
    <td align="right">{{tanggal($b->tgl)}}</td>
    <td align="right">{{$b->no_nota}}</td>
    <td align="right">{{ucwords(strtolower($b->nm_akun))}}</td>
    <td></td>
    <td></td>
    <td align="right">Rp. {{number_format($b->debit,0)}}</td>
    <td align="right">Rp. {{number_format($b->kredit,0)}}</td>
    <td></td>
    <td></td>
    <td>{{ ucwords($b->admin) }}</td>
    <td></td>
</tr>
@endforeach