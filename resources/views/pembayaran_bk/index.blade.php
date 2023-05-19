<x-theme.app title="{{ $title }}" table="Y" sizeCard="12" cont="container-fluid">
    <x-slot name="cardHeader">
        @php
        $total_paid =0;
        $total_unpaid =0;
        $total_draft =0;
        @endphp
        @foreach ($paid as $p)
        @php
        $total_paid += $p->total_harga + $p->debit ;
        @endphp
        @endforeach

        @foreach ($unpaid as $u)
        @php
        $total_unpaid += $u->total_harga + $u->debit - $u->kredit ;
        @endphp
        @endforeach

        @foreach ($draft as $d)
        @php
        $total_draft += $d->total_harga + $d->debit - $d->kredit ;
        @endphp
        @endforeach

        <div class="row justify-content-end">
            <div class="col-lg-12">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{empty($tipe) ? 'active' : ''}}" href="{{route('pembayaranbk')}}"
                            type="button" role="tab" aria-controls="pills-home" aria-selected="true">All</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{$tipe == 'D' ? 'active' : ''}}"
                            href="{{route('pembayaranbk',['tipe' => 'D'])}}" type="button" role="tab"
                            aria-controls="pills-home" aria-selected="true">Draft <br>
                            Rp {{number_format($total_draft,0)}}</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{$tipe == 'Y' ? 'active' : ''}}"
                            href="{{route('pembayaranbk',['tipe' => 'Y'])}}" type="button" role="tab"
                            aria-controls="pills-home" aria-selected="true">Paid <br>
                            Rp {{number_format($total_paid,0)}}</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{$tipe == 'T' ? 'active' : ''}}"
                            href="{{route('pembayaranbk',['tipe' => 'T'])}}" type="button" role="tab"
                            aria-controls="pills-home" aria-selected="true">Unpaid <br>
                            Rp {{number_format($total_unpaid,0)}}</a>
                    </li>
                </ul>
            </div>
            <div class="col-lg-6">
                <h5 class="float-start mt-1">{{ $title }} : {{date('d-m-Y',strtotime($tgl1))}}
                    ~ {{date('d-m-Y',strtotime($tgl1))}}
                </h5>

            </div>
            <div class="col-lg-6">
                <x-theme.btn_filter title="Filter Pembayaran Bk" />
            </div>
        </div>
    </x-slot>
    <x-slot name="cardBody">
        <form action="{{ route('pembayaranbk.add') }}" method="get">
            <div class="row justify-content-end">

            </div>
            <section class="row">
                <table class="table table-hover" id="tableScroll" width="100%">
                    <thead>
                        <tr>
                            <th width="5">#</th>
                            <th></th>
                            <th>Tanggal</th>
                            <th>No Nota</th>
                            <th width="10%">Akun</th>
                            <th>Suplier Awal</th>
                            <th>Suplier Akhir</th>
                            <th style="text-align: right">Total Rp</th>
                            <th style="text-align: right">Terbayar</th>
                            <th style="text-align: right">Sisa Hutang</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $i = 1;
                        @endphp
                        @foreach ($pembelian as $no => $p)
                        <tr class="fw-bold induk_detail{{$p->no_nota}}">
                            <td>{{$i++}}</td>
                            <td>
                                <a href="#" onclick="event.preventDefault();"
                                    class="detail_bayar detail_bayar{{$p->no_nota}}" no_nota="{{$p->no_nota}}"><i
                                        class="fas fa-angle-down"></i></a>

                                <a href="#" onclick="event.preventDefault();"
                                    class="hide_bayar hide_bayar{{$p->no_nota}}" no_nota="{{$p->no_nota}}"><i
                                        class="fas fa-angle-up"></i></a>
                            </td>
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
            </section>
        </form>








    </x-slot>
    @section('scripts')
    <script>
        $(document).ready(function() {
            $('.hide_bayar').hide();
            $(document).on("click", ".detail_bayar", function() {
                var no_nota = $(this).attr('no_nota');
                $.ajax({
                    type: "get",
                    url: "/get_kreditBK?no_nota=" + no_nota,
                    success: function(data) {
                        $('.induk_detail' + no_nota).after("<tr>" + data + "</tr>");
                        $(".show_detail" + no_nota).show();
                        $(".detail_bayar" + no_nota).hide();
                        $(".hide_bayar" + no_nota).show();
                    }
                });

            });
            $(document).on("click", ".hide_bayar", function() {
                var no_nota = $(this).attr('no_nota');
                $(".show_detail" + no_nota).remove();
                $(".detail_bayar" + no_nota).show();
                $(".hide_bayar" + no_nota).hide();

            });
         });
    </script>

    @endsection
</x-theme.app>