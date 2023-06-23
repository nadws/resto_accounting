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
            <div class="col-lg-12">
                <h5>
                    Stok Telur

                </h5>
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
                        <td align="left">Martadah</td>
                        @foreach ($telur as $d)
                        @php
                        $stok = DB::selectOne("SELECT SUM(pcs - pcs_kredit) as pcs, SUM(kg - kg_kredit) as kg FROM
                        `stok_telur`
                        WHERE id_telur = '$d->id_produk_telur' AND id_gudang = 1;");

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
                            <a href="{{ route('dashboard_kandang.transfer_stok', ['id_gudang' => 1]) }}"
                                class="badge bg-primary text-sm"><i class="fas fa-history"></i>
                            </a>
                        </td>

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
                        WHERE id_telur = '$d->id_produk_telur' AND id_gudang = 2;");

                        @endphp
                        <td>{{ $stok->pcs ?? 0 }}</td>
                        <td>{{ $stok->kg ?? 0 }}</td>
                        <td>{{ number_format($stok->pcs / 180, 1) }}</td>
                        @endforeach
                    </tr>
                    {{-- <tr>
                        <th align="center">Total</th>
                        @foreach ($telur as $i => $d)
                        @php
                        $stokMtd = DB::selectOne("SELECT SUM(pcs - pcs_kredit) as pcs, SUM(kg - kg_kredit) as kg FROM
                        `stok_telur`
                        WHERE id_telur = '$d->id_produk_telur' AND id_gudang = 1;");
                        $stokTf = DB::selectOne("SELECT SUM(pcs - pcs_kredit) as pcs, SUM(kg - kg_kredit) as kg FROM
                        `stok_telur`
                        WHERE id_telur = '$d->id_produk_telur' AND id_gudang = 2;");

                        $ttlPcs += $stokMtd->pcs + $stokTf->pcs;
                        $ttlKg += $stokMtd->kg + $stokTf->kg;
                        $ttlIkat += number_format($stokMtd->pcs / 180, 1) + number_format($stokTf->pcs / 180, 1);
                        @endphp
                        <th>{{ $ttlPcs }}</th>
                        <th>{{ $ttlKg }}</th>
                        <th>{{ $ttlIkat }}</th>
                        @endforeach
                    </tr> --}}
                </table>
            </div>

        </div>
        {{-- end stok mtd --}}

        {{-- table input --}}
        <section class="row">
            @if (session()->has('error'))
            <x-theme.alert pesan="kontak dr anto kalo ada yg merah" />
            @endif
            <div class="col-lg-12">
                <table class="table table-bordered table-hover " id="table">
                    <thead>
                        <tr>
                            <th colspan="2"></th>
                            <th colspan="3" class="text-center  putih">Populasi</th>
                            <th colspan="5" class="text-center abu">Telur</th>
                            <th class="putih">Pupuk</th>
                            <th colspan="2" class="text-center abu">pakan</th>
                            {{-- <th class="text-center dhead" rowspan="2">Aksi</th> --}}
                        </tr>
                        <tr>
                            <th>Tanggal</th>
                            <th>Kdg</th>
                            <th>Minggu</th>
                            <th>Pop</th>
                            <th>Mati / Jual</th>

                            @php
                            $telur = DB::table('telur_produk')->get();
                            @endphp
                            @foreach ($telur as $d)
                            <th>{{ ucwords(str_replace('telur', '', strtolower($d->nm_telur))) }}</th>
                            @endforeach
                            <th>Karung</th>
                            <th>Kg</th>
                            <th>Gr / Ekor</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kandang as $no => $d)
                        <tr>
                            <td>{{ tanggal(date('Y-m-d')) }}</td>
                            <td>{{ $d->nm_kandang }}</td>
                            <td data-bs-toggle="modal" id_kandang="{{ $d->id_kandang }}"
                                nm_kandang="{{ $d->nm_kandang }}" class="tambah_populasi putih"
                                data-bs-target="#tambah_populasi">82 / 91%</td>
                            <td data-bs-toggle="modal" id_kandang="{{ $d->id_kandang }}"
                                nm_kandang="{{ $d->nm_kandang }}" class="tambah_populasi putih"
                                data-bs-target="#tambah_populasi">2321</td>

                            @php
                            $populasi = DB::table('populasi')
                            ->where([['id_kandang', $d->id_kandang], ['tgl', date('Y-m-d')]])
                            ->first();
                            $mati = $populasi->mati ?? 0;
                            $jual = $populasi->jual ?? 0;
                            $kelas = $mati > 3 ? 'merah' : 'putih';
                            @endphp

                            {{-- mati dan jual --}}
                            <td data-bs-toggle="modal" id_kandang="{{ $d->id_kandang }}"
                                nm_kandang="{{ $d->nm_kandang }}" class="tambah_populasi {{ $kelas }}"
                                data-bs-target="#tambah_populasi">{{ $mati ?? 0 }} / {{ $jual ?? 0 }}</td>
                            {{-- end mati dan jual --}}

                            {{-- telur --}}
                            @php
                            $telur = DB::table('telur_produk')->get();
                            $ttlKg = 0;
                            @endphp
                            @foreach ($telur as $t)
                            @php
                            $tgl = date('Y-m-d');
                            $stok = DB::selectOne("SELECT * FROM stok_telur as a WHERE a.id_kandang = '$d->id_kandang'
                            AND a.tgl = '$tgl' AND a.id_telur = '$t->id_produk_telur'");
                            $ttlKg += $stok->kg ?? 0;
                            @endphp

                            <td data-bs-toggle="modal" id_kandang="{{ $d->id_kandang }}"
                                nm_kandang="{{ $d->nm_kandang }}" class="tambah_telur abu"
                                data-bs-target="#tambah_telur">
                                {{ $stok->pcs ?? 0 }}
                            </td>
                            @endforeach
                            <td data-bs-toggle="modal" id_kandang="{{ $d->id_kandang }}"
                                nm_kandang="{{ $d->nm_kandang }}" class="tambah_karung putih        "
                                data-bs-target="#tambah_karung">2</td>
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

        @include('dashboard_kandang.modal.tambah_kandang')
        @include('dashboard_kandang.modal.tambah_karung')
    </x-slot>
    @section('js')
    <script>
        edit('tambah_telur', 'id_kandang', 'dashboard_kandang/load_telur', 'load_telur')
            edit('tambah_populasi', 'id_kandang', 'dashboard_kandang/load_populasi', 'load_populasi')

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
    </script>
    @endsection
</x-theme.app>