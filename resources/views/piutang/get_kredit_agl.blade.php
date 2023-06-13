@foreach ($bayar as $n => $b)
<tr class="show_detail{{$b->nota_jurnal}}">
    <td></td>
    <td></td>
    <td align="right">{{tanggal($b->tgl)}}</td>
    <td align="right"><a href="{{ route('piutang.edit_pembayaran', [
        'no_nota' => $b->no_nota
    ]) }}"> {{$b->no_nota}}</a></td>
    <td></td>
    <td></td>
    <td align="right">Rp. {{number_format($b->debit,0)}}</td>
    <td align="right">Rp. {{number_format($b->kredit,0)}}</td>
    <td></td>
    <td></td>
    <td>{{ $b->admin }}</td>
    <td></td>
</tr>
@endforeach