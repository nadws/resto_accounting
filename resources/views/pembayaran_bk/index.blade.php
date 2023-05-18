<x-theme.app title="{{ $title }}" table="Y" sizeCard="12">
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
                <h3 class="float-start mt-1">{{ $title }}</h3>
            </div>
            <div class="col-lg-6">
                <x-theme.button modal="Y" idModal="view" icon="fa-filter" addClass="float-end" teks="" />
            </div>
        </div>
    </x-slot>
    <x-slot name="cardBody">
        <form action="{{ route('pembayaranbk.add') }}" method="get">
            <div class="row justify-content-end">

            </div>
            <section class="row">
                <table class="table table-hover" id="table1">
                    <thead>
                        <tr>
                            <th width="5">#</th>
                            <th>Tanggal</th>
                            <th>No Nota</th>
                            <th width="10%">Akun</th>
                            <th>Suplier Awal</th>
                            <th>Suplier Akhir</th>
                            <th style="text-align: right">Debit</th>
                            <th style="text-align: right">Kredit</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>

                    </thead>
                    <tbody>
                        @php
                        $i = 1;
                        @endphp
                        @foreach ($pembelian as $no => $p)
                        @php
                        $bayar = DB::select("SELECT a.no_nota, a.tgl, c.nm_suplier, b.suplier_akhir, a.debit, a.kredit,
                        d.nm_akun
                        FROM bayar_bk as a
                        left join invoice_bk as b on b.no_nota = a.no_nota
                        left join tb_suplier as c on c.id_suplier = b.id_suplier
                        left join akun as d on d.id_akun = a.id_akun
                        where a.no_nota = '$p->no_nota'
                        group by a.id_bayar_bk;");
                        @endphp
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{date('d-m-Y',strtotime($p->tgl))}}</td>
                            <td>{{$p->no_nota}}</td>
                            <td>Bkin</td>
                            <td>{{ucwords(strtolower($p->nm_suplier))}}</td>
                            <td>{{ucwords(strtolower($p->suplier_akhir))}}</td>
                            <td align="right">Rp. {{number_format($p->total_harga,0)}}</td>
                            <td align="right">Rp. 0</td>
                            <td>
                                <span
                                    class="badge {{$p->lunas == 'D' ? 'bg-warning' :  ($p->total_harga + $p->debit - $p->kredit == 0 ? 'bg-success' : 'bg-danger')}}">
                                    {{$p->lunas == 'D' ? 'Draft' : ($p->total_harga + $p->debit - $p->kredit == 0 ?
                                    'Paid' :
                                    'Unpaid')}}
                                </span>
                            </td>
                            <td>
                                @if ($p->lunas == 'D' )
                                <a href="#" class="btn btn-primary btn-sm disabled">Bayar</a>
                                @else
                                @if ($p->total_harga + $p->debit - $p->kredit == 0 )
                                <a href="#" class="btn btn-primary btn-sm disabled">Bayar</a>
                                @else
                                <a href="{{route('pembayaranbk.add',['nota' => $p->no_nota])}}"
                                    class="btn btn-primary btn-sm">Bayar</a>
                                @endif

                                @endif
                                <a href="{{route('pembayaranbk.edit',['nota' => $p->no_nota])}}"
                                    class="btn btn-primary btn-sm"><i class="fas fa-pen"></i> Edit</a>
                            </td>
                        </tr>
                        @foreach ($bayar as $n => $b)
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{date('d-m-Y',strtotime($b->tgl))}}</td>
                            <td>{{$b->no_nota}}</td>
                            <td>{{ucwords(strtolower($b->nm_akun))}}</td>
                            <td>{{ucwords(strtolower($b->nm_suplier))}}</td>
                            <td>{{ucwords(strtolower($b->suplier_akhir))}}</td>
                            <td align="right">Rp. {{number_format($b->debit,0)}}</td>
                            <td align="right">Rp. {{number_format($b->kredit,0)}}</td>
                            <td></td>
                            <td></td>
                        </tr>
                        @endforeach
                        @endforeach
                    </tbody>
                </table>
            </section>
        </form>


        <form action="" method="get">
            <x-theme.modal title="Filter Jurnal Umum" idModal="view">
                <div class="row">
                    <div class="col-lg-12">

                        <table width="100%" cellpadding="10px">
                            <tr>
                                <td>Tanggal</td>
                                <td colspan="2">
                                    <select name="period" id="" class="form-control filter_tgl">
                                        <option value="daily">Hari ini</option>
                                        <option value="weekly">Minggu ini</option>
                                        <option value="mounthly">Bulan ini</option>
                                        <option value="costume">Custom</option>
                                    </select>
                                </td>
                            </tr>
                            <tr class="costume_muncul">
                                <td></td>
                                <td>
                                    <label for="">Dari</label>
                                    <input type="date" name="tgl1" class="form-control tgl">
                                </td>
                                <td>
                                    <label for="">Sampai</label>
                                    <input type="date" name="tgl2" class="form-control tgl">
                                </td>
                            </tr>
                            <tr class="bulan_muncul">
                                <td></td>
                                <td>
                                    <label for="">Bulan</label>
                                    <select name="bulan" id="bulan" class="selectView bulan">
                                        @foreach($listbulan as $l)
                                        <option value="{{ $l->bulan }}" {{ (int) date('m')==$l->bulan ? 'selected' : ''
                                            }}>{{
                                            $l->nm_bulan }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <label for="">Tahun</label>
                                    <select name="tahun" id="" class="selectView bulan">
                                        <option value="2023">2023</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

            </x-theme.modal>
        </form>





    </x-slot>
    @section('scripts')


    @endsection
</x-theme.app>