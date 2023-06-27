<x-theme.app title="{{ $title }}" table="Y" sizeCard="12" cont="container-fluid">
    <x-slot name="cardHeader">

        <h5 class="float-start mt-1">{{ $title }} ~ {{ tanggal(date('Y-m-d')) }}</h5>

        <div class="row justify-content-end">

            <div class="col-lg-12">
                <div class="btn-group border-1 float-end" role="group">
                    <span class="btn btn-sm" data-bs-toggle="dropdown">
                        <i class="fas fa-plus-circle text-primary"></i> Tambah
                    </span>
                    <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        <li>
                            <a class="dropdown-item text-info" href="#" data-bs-toggle="modal"
                                data-bs-target="#tambah_kandang"><i class="me-2 fas fa-border-all"></i>kandang</a>
                        </li>

                    </ul>
                </div>
            </div>
        </div>

    </x-slot>
    <x-slot name="cardBody">
        <style>
            .abu {
                background-color: #d3d3d3 !important;
                color: rgb(37, 37, 37);
            }

            .putih {
                background-color: #f6e6e6 !important;
                color: rgb(37, 37, 37);
            }

            .merah {
                background-color: #ff3030 !important;
                color: white;
            }
        </style>

        {{-- stok mtd --}}
        <div class="row">
            @if (session()->has('errorMin'))
            <x-theme.alert pesan="ada input yang salah !" />
            @endif
            <div class="col-lg-12">
                <h6>
                    Stok Telur

                </h6>
                <table class="table table-bordered text-center">
                    @php
                    $ttlPcs = 0;
                    $ttlKg = 0;
                    $ttlIkat = 0;
                    @endphp
                    <tr>
                        <th class="dhead" rowspan="2" style="vertical-align: middle">Gudang</th>
                        @foreach ($telur as $d)
                        <th class="dhead" colspan="3">
                            {{ ucwords(str_replace('telur', '', strtolower($d->nm_telur))) }}</th>
                        @endforeach
                    </tr>

                    <tr>
                        @php
                        $telur = DB::table('telur_produk')->get();
                        @endphp
                        @foreach ($telur as $d)
                        <th class="dhead">Pcs</th>
                        <th class="dhead">Kg</th>
                        <th class="dhead">Ikat</th>
                        @endforeach
                    </tr>
                    <tr>
                        <td align="left">
                            Martadah
                            <a href="{{ route('opnamemtd') }}" class="badge bg-primary text-sm">Opname</a>
                            <a href="#" class="badge bg-primary text-sm history_opname">History Opname</a>
                        </td>
                        @foreach ($telur as $d)
                        @php
                        $stok = DB::selectOne("SELECT SUM(pcs - pcs_kredit) as pcs, SUM(kg - kg_kredit) as kg FROM
                        `stok_telur`
                        WHERE id_telur = '$d->id_produk_telur' AND id_gudang = 1 AND opname = 'T';");
                        request()
                        ->session()
                        ->forget('errorMin', '1');
                        if ($stok->pcs < 0) { request() ->session()
                            ->put('errorMin', '1');
                            }
                            @endphp
                            <td>{{ $stok->pcs }}</td>
                            <td>{{ $stok->kg }}</td>
                            <td>{{ number_format($stok->pcs / 180, 1) }}</td>
                            @endforeach
                    </tr>
                    <tr>
                        <td align="left">
                            Penjualan Martadah
                            <a href="{{ route('dashboard_kandang.add_penjualan_telur') }}"
                                class="badge bg-primary text-sm"><i class="fas fa-plus"></i></a>
                            <a href="{{ route('dashboard_kandang.penjualan_telur', ['id_gudang' => 1]) }}"
                                class="badge bg-primary text-sm"><i class="fas fa-history"></i>
                            </a>
                        </td>
                        @foreach ($telur as $d)
                        @php
                        $stok = DB::selectOne("SELECT SUM(pcs_kredit) as pcs, SUM(kg_kredit) as kg FROM `stok_telur`
                        WHERE id_telur = '$d->id_produk_telur' AND jenis = 'penjualan' AND opname = 'T';");

                        @endphp
                        <td>{{ $stok->pcs ?? 0 }}</td>
                        <td>{{ $stok->kg ?? 0 }}</td>
                        <td>{{ number_format($stok->pcs / 180, 1) }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td align="left">
                            Transfer Alpa
                            <a href="{{ route('dashboard_kandang.add_transfer_stok', ['id_gudang' => 1]) }}"
                                class="badge bg-primary text-sm"><i class="fas fa-plus"></i></a>
                            <a href="{{ route('dashboard_kandang.transfer_stok', ['id_gudang' => 1]) }}"
                                class="badge bg-primary text-sm"><i class="fas fa-history"></i>
                            </a>
                        </td>
                        @foreach ($telur as $d)
                        @php
                        $stok = DB::selectOne("SELECT SUM(pcs - pcs_kredit) as pcs, SUM(kg - kg_kredit) as kg FROM
                        `stok_telur`
                        WHERE id_telur = '$d->id_produk_telur' AND id_gudang = 2 AND opname = 'T';");

                        @endphp
                        <td>{{ $stok->pcs ?? 0 }}</td>
                        <td>{{ $stok->kg ?? 0 }}</td>
                        <td>{{ number_format($stok->pcs / 180, 1) }}</td>
                        @endforeach
                    </tr>
                </table>
            </div>

        </div>
        {{-- end stok mtd --}}

        {{-- table input --}}
        <section class="row">
            @if (session()->has('error'))
            <div class="col-lg-12">
                <x-theme.alert pesan="kontak dr anto kalo ada yg merah" />
            </div>
            @endif
            <div class="col-lg-4">
                <h6>
                    Penjualan Umum
                    <a href="{{ route('dashboard_kandang.add_penjualan_umum') }}" class="badge bg-primary text-sm"><i
                            class="fas fa-plus"></i></a>
                    <a href="{{ route('dashboard_kandang.penjualan_umum') }}" class="badge bg-primary text-sm"><i
                            class="fas fa-history"></i></a>
                    <a href="{{ route('barang_dagangan.index') }}" class="badge bg-primary text-sm"><i
                            class="fas fa-list"></i> Produk</a>
                </h6>
                <table class="table table-bordered table-hover" id="">
                    <thead>
                        <tr>
                            <th class="text-center dhead">Produk</th>
                            <th width="30%" class="text-center dhead">Ttl Rp</th>
                            <th width="20%" class="text-center dhead">Nota PAGL</th>
                            <th width="16%" class="text-center dhead">Ttl Nota</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @foreach ($produk as $d)
                        @php
                        $datas = DB::selectOne("SELECT GROUP_CONCAT(CONCAT(urutan)) as urutan,count(*) as ttl,
                        sum(total_rp) as ttl_rp FROM penjualan_agl
                        WHERE id_produk = '$d->id_produk' AND cek = 'T' AND lokasi = 'mtd' GROUP BY id_produk");

                        $urutan = implode(', ', explode(',', $datas->urutan));
                        @endphp
                        <tr>
                            <td>{{ $d->nm_produk }}</td>
                            <td>Rp. {{ !empty($datas) ? number_format($datas->ttl_rp, 0) ?? 0 : 0 }}</td>
                            <td data-bs-toggle="modal" data-bs-target="#detail_nota"
                                class="detail_nota text-primary cursor-pointer"
                                urutan="{{ $urutan }}, {{ $d->id_produk }}">{{ $urutan }}</td>
                            <td>{{ !empty($datas) ? $datas->ttl ?? 0 : 0 }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-lg-8">

                <h6>Input Kandang Harian</h6>
                <table class="table table-bordered table-hover " id="">
                    <thead>
                        <tr>
                            <th rowspan="2" width="7%" class="text-center dhead">Tanggal</th>
                            <th rowspan="2" width="1%" class="text-center dhead">Kdg</th>
                            <th colspan="3" class="text-center  putih">Populasi</th>
                            <th colspan="7" class="text-center abu">Telur</th>
                            <th colspan="2" class="text-center putih">pakan</th>
                            <th width="2%" class="text-center dhead" rowspan="2">Aksi</th>
                        </tr>
                        <tr>
                            <th width="2%" class="dhead text-center">Minggu</th>
                            <th width="1%" class="dhead text-center">Pop</th>
                            <th width="6%" class="dhead text-center">Mati / Jual</th>
                            @php
                            $telur = DB::table('telur_produk')->get();
                            @endphp
                            @foreach ($telur as $d)
                            <th width="1%" class="dhead text-center">
                                {{ ucwords(str_replace('telur', '', strtolower($d->nm_telur))) }}</th>
                            @endforeach
                            <th width="1%" class="dhead text-center">Ttl Pcs</th>
                            <th width="1%" class="dhead text-center">Ttl Kg</th>
                            <th width="1%" class="dhead text-center">Kg</th>
                            <th width="3%" class="dhead text-center">Gr / Ekor</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @foreach ($kandang as $no => $d)
                        <tr>
                            <td>{{ tanggal(date('Y-m-d')) }}</td>
                            <td align="center" data-bs-toggle="modal" data-bs-target="#tambah_kandang">
                                {{ $d->nm_kandang }}</td>
                            @php
                            $populasi = DB::table('populasi')
                            ->where([['id_kandang', $d->id_kandang], ['tgl', date('Y-m-d')]])
                            ->first();
                            $mati = $populasi->mati ?? 0;
                            $jual = $populasi->jual ?? 0;
                            $kelas = $mati > 3 ? 'merah' : 'putih';
                            @endphp
                            <td data-bs-toggle="modal" id_kandang="{{ $d->id_kandang }}"
                                nm_kandang="{{ $d->nm_kandang }}" class="tambah_populasi putih"
                                data-bs-target="#tambah_populasi">82 / 91%</td>

                            @php
                            $pop = DB::selectOne("SELECT sum(a.mati + a.jual) as pop,b.stok_awal FROM populasi as a
                            LEFT JOIN kandang as b ON a.id_kandang = b.id_kandang
                            WHERE a.id_kandang = '$d->id_kandang';");
                            @endphp

                            <td data-bs-toggle="modal" id_kandang="{{ $d->id_kandang }}"
                                nm_kandang="{{ $d->nm_kandang }}" class="tambah_populasi putih"
                                data-bs-target="#tambah_populasi">{{ $pop->stok_awal - $pop->pop }}</td>


                            {{-- mati dan jual --}}
                            <td data-bs-toggle="modal" id_kandang="{{ $d->id_kandang }}"
                                nm_kandang="{{ $d->nm_kandang }}" class="tambah_populasi {{ $kelas }}"
                                data-bs-target="#tambah_populasi">{{ $mati ?? 0 }} / {{ $jual ?? 0 }}</td>
                            {{-- end mati dan jual --}}

                            {{-- telur --}}
                            @php
                            $telur = DB::table('telur_produk')->get();
                            $ttlKg = 0;
                            $ttlPcs = 0;
                            @endphp
                            @foreach ($telur as $t)
                            @php
                            $tgl = date('Y-m-d');
                            $tglKemarin = Carbon\Carbon::yesterday()->format('Y-m-d');

                            $stok = DB::selectOne("SELECT * FROM stok_telur as a WHERE a.id_kandang = '$d->id_kandang'
                            AND a.tgl = '$tgl' AND a.id_telur = '$t->id_produk_telur'");
                            $stokKemarin = DB::selectOne("SELECT * FROM stok_telur as a WHERE a.id_kandang =
                            '$d->id_kandang'
                            AND a.tgl = '$tglKemarin' AND a.id_telur = '$t->id_produk_telur'");

                            $pcs = $stok->pcs ?? 0;
                            $pcsKemarin = $stokKemarin->pcs ?? 0;

                            $ttlKg += $stok->kg ?? 0;
                            $ttlPcs += $stok->pcs ?? 0;
                            // dd($pcsKemarin - $pcs);
                            $kelasTelur = $pcsKemarin - $pcs > 60 ? 'merah' : 'abu';
                            @endphp

                            <td data-bs-toggle="modal" id_kandang="{{ $d->id_kandang }}"
                                nm_kandang="{{ $d->nm_kandang }}" class="tambah_telur {{$kelasTelur}}"
                                data-bs-target="#tambah_telur">
                                {{ $stok->pcs ?? 0 }}
                            </td>
                            @endforeach
                            <td data-bs-toggle="modal" id_kandang="{{ $d->id_kandang }}"
                                nm_kandang="{{ $d->nm_kandang }}" class="tambah_telur abu        "
                                data-bs-target="#tambah_telur">{{ $ttlPcs }}</td>
                            <td data-bs-toggle="modal" id_kandang="{{ $d->id_kandang }}"
                                nm_kandang="{{ $d->nm_kandang }}" class="tambah_telur abu        "
                                data-bs-target="#tambah_telur">{{ $ttlKg }}</td>
                            {{-- end telur --}}

                            <td>150</td>
                            <td>65</td>
                            <td align="center">
                                <a href="" class="badge bg-primary"><i class="fas fa-check"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </section>

        {{-- tambah telur --}}
        <form action="{{ route('dashboard_kandang.tambah_telur') }}" method="post">
            @csrf
            <x-theme.modal title="Tambah Telur" size="modal-lg-max" idModal="tambah_telur">
                <div id="load_telur"></div>
            </x-theme.modal>
        </form>
        {{-- end tambah telur --}}


        {{-- tambah populasi --}}
        <form action="{{ route('dashboard_kandang.tambah_populasi') }}" method="post">
            @csrf
            <x-theme.modal title="Tambah Populasi" size="modal-lg-max" idModal="tambah_populasi">
                <div id="load_populasi"></div>
            </x-theme.modal>
        </form>
        {{-- end tambah populasi --}}


        {{-- tambah detail nota --}}
        <x-theme.modal title="Detail Nota Penjualan Umum" btnSave="" size="modal-lg" idModal="detail_nota">
            <div id="load_detail_nota"></div>
        </x-theme.modal>
        {{-- end tambah detail nota --}}



        @include('dashboard_kandang.modal.tambah_kandang')
        @include('dashboard_kandang.modal.tambah_karung')
        @include('dashboard_kandang.modal.history_opname')
    </x-slot>
    @section('js')
    <script>
        edit('tambah_telur', 'id_kandang', 'dashboard_kandang/load_telur', 'load_telur')
            edit('tambah_populasi', 'id_kandang', 'dashboard_kandang/load_populasi', 'load_populasi')
            edit('detail_nota', 'urutan', 'dashboard_kandang/load_detail_nota', 'load_detail_nota')

            modalSelect2()

            function modalSelect2() {
                $('.select2-kandang').select2({
                    dropdownParent: $('#tambah_kandang .modal-content')
                });
            }


            $(document).on("keyup", ".pcs", function() {
                var count = $(this).attr('count');
                var pcs = $('.pcs' + count).val()
                var kgPcs = $('.kgPcs' + count).val()

                var potongan = ((30 - parseFloat(pcs)) * 5.5) / 1000

                $('.potongan' + count).val(potongan);
                $('.ttlKgPcs' + count).val(kgPcs - potongan);

            });

            $(document).on("keyup", ".kgPcs", function() {
                var count = $(this).attr('count');
                var pcs = $('.kgPcs' + count).val()
                var potongan = $('.potongan' + count).val()
                var ttlKg = parseFloat(pcs) - parseFloat(potongan)
                $('.ttlKgPcs' + count).val(ttlKg);
            });
            $(document).on("click", ".history_opname", function() {
                $.ajax({
                    type: "get",
                    url: "/history_opname_mtd",
                    success: function (data) {
                        $('#history_opname').html(data);
                        $('#history_opn').modal('show');
                        $('#table_history').DataTable({
                            "paging": true,
                            "pageLength": 10,
                            "lengthChange": true,
                            "stateSave": true,
                            "searching": true,
                        });
                    }
                });
            });
            $(document).on('submit', '#history_serach_opname_mtd', function(e) {
                e.preventDefault();
                var tgl1 = $(".tgl1").val();
                var tgl2 = $(".tgl2").val();
                $.ajax({
                    type: "get",
                    url: "/history_opname_mtd?tgl1=" + tgl1 + "&tgl2=" + tgl2,
                    success: function (data) {
                        
                        $('#history_opname').html(data)
                        $('#table_history').DataTable({
                            "paging": true,
                            "pageLength": 10,
                            "lengthChange": true,
                            "stateSave": true,
                            "searching": true,
                        });
                    }
                });
            });  
    </script>
    @endsection
</x-theme.app>