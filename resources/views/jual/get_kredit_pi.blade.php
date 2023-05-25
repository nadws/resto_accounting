@foreach ($bayar as $n => $b)
<tr class="show_detail{{$b->nota_jurnal}}">
    <td></td>
    <td></td>
    <td align="right">{{date('d-m-Y',strtotime($b->tgl))}}</td>
    <td align="right">PBYR-{{$b->no_nota}}</td>
    <td></td>
    <td></td>
    <td align="right">Rp. {{number_format($b->debit,0)}}</td>
    <td align="right">Rp. {{number_format($b->kredit,0)}}</td>
    <td></td>
    <td></td>
    <td></td>
</tr>
@endforeach