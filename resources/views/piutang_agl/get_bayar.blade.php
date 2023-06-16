@foreach ($piutang as $n => $b)
<tr class="show_detail{{$b->no_nota}}">
    <td></td>
    <td></td>
    <td align="right">{{tanggal($b->tgl)}}</td>
    <td align="right">{{$b->no_nota_piutang}}</td>
    <td align="right"></td>
    <td></td>
    <td align="right">Rp.{{number_format($b->debit,0)}}</td>
    <td align="right"></td>
    <td align="right"></td>
    <td></td>
    <td>{{ ucwords($b->admin) }}</td>
    <td style="white-space: nowrap"><a
            href="{{route('edit_pembayaran_piutang_telur',['no_nota' => $b->no_nota_piutang])}}"
            class="btn rounded-pill text-primary"><i class="fas fa-pen "></i>
            Edit</a>
    </td>


</tr>
@endforeach