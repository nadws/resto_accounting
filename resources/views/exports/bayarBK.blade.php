<table>
    <thead>
        <tr>
            <th width="39px">#</th>
            <th width="110px">Tanggal</th>
            <th width="177px">Suplier Awal</th>
            <th width="102px">Nota BK</th>
            <th width="184px">Suplier Akhir</th>
            <th width="151px">Keterangan</th>
            <th width="125px">Gr Beli</th>
            <th width="156px">Total Nota Bk</th>
            <th width="151px">Gr Basah</th>
            <th width="114px">Pcs Awal</th>
            <th width="151px">Gr Kering</th>
            <th width="80px">Susut</th>
            <th width="100px">No Grade</th>
            <th width="80px">TGL Grade</th>
        </tr>
    </thead>
    <tbody>
        @php
        $i = 1;
        @endphp
        @foreach ($pembelian as $no => $p)
        <tr class="fw-bold induk_detail{{$p->no_nota}}">
            <td>{{$i++}}</td>
            <td>{{date('d-m-Y',strtotime($p->tgl))}}</td>
            <td>{{$p->no_nota}}</td>
            <td>Bkin</td>
            <td>{{ucwords(strtolower($p->nm_suplier))}}</td>
            <td>{{ucwords(strtolower($p->suplier_akhir))}}</td>
            <td align="right">Rp. {{number_format($p->total_harga,0)}}</td>
            <td align="right">Rp. {{number_format($p->kredit,0)}}</td>
            <td align="right">Rp. {{number_format($p->total_harga + $p->debit - $p->kredit,0)}}</td>
            <td>
                <span
                    class="badge {{$p->lunas == 'D' ? 'bg-warning' :  ($p->total_harga + $p->debit - $p->kredit == 0 ? 'bg-success' : 'bg-danger')}}">
                    {{$p->lunas == 'D' ? 'Draft' : ($p->total_harga + $p->debit - $p->kredit == 0 ?
                    'Paid' :
                    'Unpaid')}}
                </span>
            </td>
            <td>
                <div class="btn-group" role="group">
                    <span class="btn btn-sm" data-bs-toggle="dropdown">
                        <i class="fas fa-ellipsis-v text-primary"></i>
                    </span>
                    <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        <li>
                            <a class="dropdown-item text-primary edit_akun"
                                href="{{route('pembayaranbk.edit',['nota' => $p->no_nota])}}"><i
                                    class="me-2 fas fa-pen"></i>Edit
                            </a>
                        </li>
                        <li>
                            @if ($p->lunas == 'D' )
                            {{-- <a class="dropdown-item text-primary  disabled" href="#"><i
                                    class="fas fa-money-bill-wave me-2"></i>Bayar</a> --}}
                            @else
                            @if ($p->total_harga + $p->debit - $p->kredit == 0 )
                            {{-- <a href="#" class="dropdown-item text-primary  disabled"><i
                                    class="fas fa-money-bill-wave me-2"></i>Bayar</a> --}}
                            @else
                            <a href="{{route('pembayaranbk.add',['nota' => $p->no_nota])}}"
                                class="dropdown-item text-success  "><i
                                    class="fas fa-money-bill-wave me-2"></i>Bayar</a>
                            @endif
                            @endif
                        </li>
                    </ul>
                </div>
            </td>
        </tr>



        @endforeach

    </tbody>
</table>