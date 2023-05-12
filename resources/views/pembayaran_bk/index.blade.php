<x-theme.app title="{{ $title }}" table="Y" sizeCard="12">
    <x-slot name="cardHeader">
        <div class="row justify-content-end">
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
                        $bayar = DB::select("SELECT a.no_nota, a.tgl, c.nm_suplier, b.suplier_akhir, a.kredit, d.nm_akun
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
                            <td>{{$p->nm_suplier}}</td>
                            <td>{{$p->suplier_akhir}}</td>
                            <td align="right">Rp. {{number_format($p->total_harga,0)}}</td>
                            <td align="right">Rp. 0</td>
                            <td>
                                <span
                                    class="badge {{$p->lunas == 'D' ? 'bg-warning' :  ($p->total_harga - $p->kredit == 0 ? 'bg-success' : 'bg-danger')}}">
                                    {{$p->lunas == 'D' ? 'Draft' : ($p->total_harga - $p->kredit == 0 ? 'Paid' :
                                    'Unpaid')}}
                                </span>
                            </td>
                            <td>
                                @if ($p->lunas == 'D' )

                                @else
                                @if ($p->total_harga - $p->kredit == 0 )

                                @else
                                <a href="{{route('pembayaranbk.add',['nota' => $p->no_nota])}}"
                                    class="btn btn-primary btn-sm">Bayar</a>
                                @endif

                                @endif

                            </td>
                        </tr>
                        @foreach ($bayar as $n => $b)
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{date('d-m-Y',strtotime($b->tgl))}}</td>
                            <td>{{$b->no_nota}}</td>
                            <td>{{$b->nm_suplier}}</td>
                            <td>{{$b->suplier_akhir}}</td>
                            <td align="right">Rp. 0</td>
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
                                        <option value="costume">Costume</option>
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
                                        @foreach($listbulan as $key => $value)
                                        <option value="{{ $key }}" {{ (int) date('m')==$key ? 'selected' : '' }}>{{
                                            $value }}</option>
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