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
                                if ($stok->pcs < 0) {
                                    request()
                                        ->session()
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
                            <a data-bs-toggle="tooltip" data-bs-placement="top" title="Tambah Penjualan Martadah"
                                href="{{ route('dashboard_kandang.add_penjualan_telur') }}"
                                class="badge bg-primary text-sm"><i class="fas fa-plus"></i></a>
                            <a data-bs-toggle="tooltip" data-bs-placement="top" title="History Penjualan Martadah"
                                href="{{ route('dashboard_kandang.penjualan_telur', ['id_gudang' => 1]) }}"
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
                            <a data-bs-toggle="tooltip" data-bs-placement="top" title="Tambah Transfer Alpa"
                                href="{{ route('dashboard_kandang.add_transfer_stok', ['id_gudang' => 1]) }}"
                                class="badge bg-primary text-sm"><i class="fas fa-plus"></i></a>
                            <a data-bs-toggle="tooltip" data-bs-placement="top" title="History Transfer Alpa"
                                href="{{ route('dashboard_kandang.transfer_stok', ['id_gudang' => 1]) }}"
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
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Tambah Penjualan Umum"
                        href="{{ route('dashboard_kandang.add_penjualan_umum') }}" class="badge bg-primary text-sm"><i
                            class="fas fa-plus"></i></a>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="History Penjualan Umum"
                        href="{{ route('dashboard_kandang.penjualan_umum') }}" class="badge bg-primary text-sm"><i
                            class="fas fa-history"></i></a>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Data Produk"
                        href="{{ route('barang_dagangan.index') }}" class="badge bg-primary text-sm"><i
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
                                if (!empty($datas)) {
                                    $urutan = implode(', ', explode(',', $datas->urutan));
                                }
                            @endphp

                            <tr>
                                <td>{{ $d->nm_produk }}</td>
                                <td>Rp. {{ !empty($datas) ? number_format($datas->ttl_rp, 0) ?? 0 : 0 }}</td>
                                @if (!empty($datas))
                                    <td data-bs-toggle="modal" data-bs-target="#detail_nota"
                                        class="detail_nota text-primary cursor-pointer"
                                        urutan="{{ $urutan }}, {{ $d->id_produk }}">{{ $urutan }}</td>
                                @else
                                    <td>0</td>
                                @endif
                                <td>{{ !empty($datas) ? $datas->ttl ?? 0 : 0 }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-lg-8">

                <h6>Input Kandang Harian ~ {{ tanggal(date('Y-m-d')) }}</h6>
                <table class="table table-bordered table-hover " id="">
                    <thead>
                        <tr>
                            <th rowspan="2" width="1%" class="text-center dhead">Kdg</th>
                            <th colspan="3" class="text-center  putih">Populasi</th>
                            <th colspan="7" class="text-center abu"> Telur </th>
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
                                <td align="center" class="detail_perencanaan" id_kandang="{{ $d->id_kandang }}"
                                    data-bs-toggle="modal" data-bs-target="#detail_perencanaan">
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
                                        nm_kandang="{{ $d->nm_kandang }}" class="tambah_telur {{ $kelasTelur }}"
                                        data-bs-target="#tambah_telur">
                                        <span>{{ $stok->pcs ?? 0 }}</span>
                                    </td>
                                @endforeach
                                <td data-bs-toggle="modal" id_kandang="{{ $d->id_kandang }}"
                                    nm_kandang="{{ $d->nm_kandang }}" class="tambah_telur abu"
                                    data-bs-target="#tambah_telur">{{ $ttlPcs }}</td>
                                <td data-bs-toggle="modal" id_kandang="{{ $d->id_kandang }}"
                                    nm_kandang="{{ $d->nm_kandang }}" class="tambah_telur abu"
                                    data-bs-target="#tambah_telur">{{ $ttlKg }}</td>
                                {{-- end telur --}}

                                <td data-bs-toggle="modal" id_kandang="{{ $d->id_kandang }}"
                                    class="tambah_perencanaan" data-bs-target="#tambah_perencanaan">150</td>
                                <td data-bs-toggle="modal" id_kandang="{{ $d->id_kandang }}"
                                    class="tambah_perencanaan" data-bs-target="#tambah_perencanaan">65</td>
                                <td align="center">
                                    <a href="" class="badge bg-primary"><i class="fas fa-check"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

            @include('dashboard_kandang.stok.index')
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

        {{-- tambah perencanaan --}}
        <form action="{{ route('dashboard_kandang.tambah_perencanaan') }}" method="post">
            @csrf
            <x-theme.modal title="Tambah Perencanaan" size="modal-lg" idModal="tambah_perencanaan">
                <div id="load_perencanaan"></div>
            </x-theme.modal>
        </form>
        @include('dashboard_kandang.modal.tambah_pakan')
        @include('dashboard_kandang.modal.tambah_obat_pakan')
        @include('dashboard_kandang.modal.tambah_obat_air')
        @include('dashboard_kandang.modal.tambah_obat_ayam')
        {{-- end tambah perencanaan --}}


        {{-- detail perencanaan --}}
        <x-theme.modal title="Detail Perencanaan" btnSave="" size="modal-lg-max" idModal="detail_perencanaan">
            <div class="row">
                <div class="col-lg-12">
                    <a data-bs-toggle="collapse" href="#perencanaan" class="btn btn-sm btn-primary">History
                        Perencanaan</a>
                    <button data-bs-toggle="collapse" href="#layer" type="button"
                        class="btn btn-sm btn-primary">History Layer</button>
                    {{-- <button data-bs-toggle="collapse" href="#pullet" type="button"
                        class="btn btn-sm btn-primary">History Pullet</button>
                    <button data-bs-toggle="collapse" href="#stok" type="button"
                        class="btn btn-sm btn-primary">History Stok</button> --}}
                </div>
            </div>
            <hr style="border: 1px solid #6777EF;">
            
            <div id="load_detail_perencanaan"></div>
        </x-theme.modal>
        {{-- end detail perencanaan --}}

        {{-- edit perencanaan --}}
        <form action="{{ route('dashboard_kandang.edit_perencanaan') }}" method="post">
            @csrf
            <x-theme.modal title="Edit Perencanaan" size="modal-lg" idModal="edit_perencanaan">
                <div id="hasilEditPerencanaan"></div>
            </x-theme.modal>
        </form>
        {{-- end edit perencanaan --}}

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
            function modalSelect2() {
                $('.select2-kandang').select2({
                    dropdownParent: $('#tambah_kandang .modal-content')
                });
                $('.select2-obat').select2({
                    dropdownParent: $('#tambah_obat_pakan .modal-content')
                });
                $('.select2-air').select2({
                    dropdownParent: $('#tambah_obat_air .modal-content')
                });
                $('.select2-edit-perencanaan').select2({
                    dropdownParent: $('#edit_perencanaan .modal-content')
                });
            }
            edit('tambah_telur', 'id_kandang', 'dashboard_kandang/load_telur', 'load_telur')
            edit('tambah_populasi', 'id_kandang', 'dashboard_kandang/load_populasi', 'load_populasi')
            edit('detail_nota', 'urutan', 'dashboard_kandang/load_detail_nota', 'load_detail_nota')
            edit('detail_perencanaan', 'id_kandang', 'dashboard_kandang/load_detail_perencanaan', 'load_detail_perencanaan')
            viewHistoryPerencanaan()
            viewEditPerencanaan()
            hasilLayer()
            function viewHistoryPerencanaan() {
                $(document).on('click', '#btnPerencanaan', function() {
                    var tgl = $("#tglHistoryPerencanaan").val();
                    var id_kandang = $("#id_kandangPerencanaan").val();

                    $.ajax({
                        type: "GET",
                        url: "{{ route('dashboard_kandang.viewHistoryPerencanaan') }}",
                        data: {
                            tgl: tgl,
                            id_kandang: id_kandang
                        },
                        success: function(r) {
                            $("#hasilPerencanaan").html(r);
                        }
                    });
                })
            }
            function hasilLayer() {
                $(document).on('click', '#btnLayer', function() {
                    var tgl = $("#tglLayer").val();

                    $.ajax({
                        type: "GET",
                        url: "{{ route('dashboard_kandang.hasilLayer') }}?tgl=" + tgl,
                        success: function(data) {
                            $("#hasilLayer").html(data);
                            $('.select2').select2()
                        }
                    });
                })
            }
            function viewEditPerencanaan() {
                $(document).on('click', "#edit_per", function() {
                    var id_kandang = $(this).attr('id_kandang')
                    var tgl = $(this).attr('tgl')
                    $("#edit_perencanaan").modal('show')
                    $.ajax({
                        type: "GET",
                        url: "{{ route('dashboard_kandang.viewHistoryEditPerencanaan') }}",
                        data: {
                            id_kandang: id_kandang,
                            tgl: tgl,
                        },
                        success: function(r) {
                            $('#hasilEditPerencanaan').html(r)
                            $('.select2').select2()
                        }
                    });
                })
            } 

            // perencanaan -------------------------------------
            var count = 1

            function toast(pesan) {
                Toastify({
                    text: pesan,
                    duration: 3000,
                    style: {
                        background: "#EAF7EE",
                        color: "#7F8B8B"
                    },
                    close: true,
                    avatar: "https://cdn-icons-png.flaticon.com/512/190/190411.png"
                }).showToast();
            }
            editPerencanaan('tambah_perencanaan', 'id_kandang', 'dashboard_kandang/load_perencanaan', 'load_perencanaan')

            function editPerencanaan(kelas, attr, link, load) {
                $(document).on('click', `.${kelas}`, function() {
                    var id = $(this).attr(`${attr}`)
                    $.ajax({
                        type: "GET",
                        url: `${link}/${id}`,
                        success: function(r) {
                            $(`#${load}`).html(r);

                            loadPakanPerencanaan()
                            loadObatPakan()
                            loadObatAir()
                            loadObatAyam()
                        }
                    });
                })
            }

            // tambah pakan perencanaan
            keyupKgPakanBox()
            keyupPersen()
            keyupPersenEdit()
            function keyupPersenEdit() {
                $(document).on('keyup', '.persenEdit', function() {
                    var detail = $(this).attr('kd');
                    var persen = $(this).val();
                    var pop = $("#getPopulasiEdit").val();
                    var gr = $("#grEdit").val();
                    // alert(`${detail} - ${persen} - ${pop} - ${gr}`)
                    var krng = $("#krngEdit").val();


                    var hasil = (parseFloat(persen) * parseFloat(gr) * parseFloat(pop)) / 100;
                    // alert(hasil);

                    $("#hasilEdit" + detail).val(hasil);

                    var total = 0;
                    $(".hasilEdit").each(function() {
                        total += parseFloat($(this).val());
                    });
                    // var kg = parseFloat(total) / (parseFloat(krng) * 1000)
                    var kg = Math.floor(parseFloat(total) / (parseFloat(krng) * 1000));


                    $('#totalEdit').val(total);
                    $('#krng_fEdit').val(kg);
                    var krng_f = $("#krng_fEdit").val();
                    var kg_sisa = ((parseFloat(total) / (parseFloat(krng) * 1000)) - parseFloat(krng_f)) *
                        10;
                    var number = kg_sisa.toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
                    $('#krng_sEdit').val(number);
                })

            }
            function keyupKgPakanBox() {
                $(document).on('keyup', '#krng', function() {
                    var krng = $(this).val()
                    var pop = $("#getPopulasi").val();
                    var gr = $("#gr").val();

                    var total = 0;
                    $(".hasil").each(function() {
                        total += parseFloat($(this).val());
                    });
                    var kg = Math.floor(parseFloat(total) / (parseFloat(krng) * 1000));
                    $('#total').val(total);
                    $('#krng_f').val(kg);
                    var krng_f = $("#krng_f").val();
                    var kg_sisa = ((parseFloat(total) / (parseFloat(krng) * 1000)) - parseFloat(krng_f)) *
                        10;
                    var number = kg_sisa.toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
                    $('#krng_s').val(number);
                })
            }

            function keyupPersen() {
                $(document).on('keyup', '.persen', function() {
                    var detail = $(this).attr('kd');
                    var persen = $(this).val();
                    var pop = $("#getPopulasi").val();
                    var gr = $("#gr").val();
                    // alert(`${detail} - ${persen} - ${pop} - ${gr}`)
                    var krng = $("#krng").val();

                    var hasil = (parseFloat(persen) * parseFloat(gr) * parseFloat(pop)) / 100;
                    // alert(hasil);

                    $("#hasil" + detail).val(hasil);

                    var total = 0;
                    $(".hasil").each(function() {
                        total += parseFloat($(this).val());
                    });
                    console.log(total)
                    // var kg = parseFloat(total) / (parseFloat(krng) * 1000)
                    var kg = Math.floor(parseFloat(total) / (parseFloat(krng) * 1000));

                    $('#total').val(total);
                    $('#krng_f').val(kg);
                    var krng_f = $("#krng_f").val();
                    var kg_sisa = ((parseFloat(total) / (parseFloat(krng) * 1000)) - parseFloat(krng_f)) *
                        10;
                    var number = kg_sisa.toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
                    $('#krng_s').val(number);
                })
            }

            function loadPakanPerencanaan() {
                $.ajax({
                    type: "GET",
                    url: "{{ route('dashboard_kandang.load_pakan_perencanaan') }}",
                    success: function(r) {
                        $("#load_pakan_perencanaan").html(r);
                        $('.select2-edit').select2({
                            dropdownParent: $(`#tambah_perencanaan .modal-content`)
                        });
                        plusRowPakan('tbhPakan', 'dashboard_kandang/tbh_pakan')
                    }
                });
            }

            function plusRowPakan(classPlus, url) {
                $(document).on("click", "." + classPlus, function() {
                    count += 1;
                    $.ajax({
                        url: `${url}?count=` + count,
                        type: "GET",
                        success: function(data) {
                            $("#" + classPlus).append(data);
                            $(".select2-pakan").select2({
                                dropdownParent: $(`#tambah_perencanaan .modal-content`)
                            });
                        },
                    });
                });

                $(document).on('click', '.remove_baris', function() {
                    var delete_row = $(this).attr("count");
                    $(".baris" + delete_row).remove();

                    var pop = $("#getPopulasi").val();
                    var gr = $("#gr").val();
                    var krng = $("#krng").val();

                    var total = 0;
                    $(".hasil").each(function() {
                        total += parseFloat($(this).val());
                    });
                    var kg = Math.floor(parseFloat(total) / (parseFloat(krng) * 1000));
                    $('#total').val(total);
                    $('#krng_f').val(kg);
                    var krng_f = $("#krng_f").val();
                    var kg_sisa = ((parseFloat(total) / (parseFloat(krng) * 1000)) - parseFloat(krng_f)) *
                        10;
                    var number = kg_sisa.toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
                    $('#krng_s').val(number);
                })
            }
            $(document).on("change", '.pakan_input', function() {
                var id_pakan = $(this).val()
                var count = $(this).attr('count')
                if (id_pakan == 'tambah') {
                    $("#tambah_pakan").modal('show')
                } else {
                    $.ajax({
                        type: "GET",
                        url: "{{ route('dashboard_kandang.get_stok_pakan') }}?id_pakan=" + id_pakan,
                        success: function(r) {
                            $(".get_stok_pakan" + count).val(r);
                        }
                    });
                }
            })
            $(document).on('submit', '#form_tambah_pakan', function(e) {
                e.preventDefault()
                var datas = $("#form_tambah_pakan").serialize()
                $.ajax({
                    type: "GET",
                    url: "{{ route('dashboard_kandang.save_tambah_pakan') }}",
                    data: datas,
                    success: function(response) {
                        toast('Berhasil tambah Pakan')
                        loadPakanPerencanaan()
                        $("#tambah_pakan").modal('hide')

                    }
                });
            })

            // tambah obat pakan
            function loadObatPakan() {
                $.ajax({
                    type: "GET",
                    url: "{{ route('dashboard_kandang.load_obat_pakan') }}",
                    success: function(r) {
                        $("#load_obat_pakan").html(r);
                        $('.select2-edit').select2({
                            dropdownParent: $(`#tambah_perencanaan .modal-content`)
                        });
                        plusRowObatPakan('tbhObatPakan', 'dashboard_kandang/tbh_obatPakan')
                    }
                });
            }

            function plusRowObatPakan(classPlus, url) {
                $(document).on("click", "." + classPlus, function() {
                    count += 1;
                    $.ajax({
                        url: `${url}?count=` + count,
                        type: "GET",
                        success: function(data) {
                            $("#" + classPlus).append(data);
                            $(".select2-pakan").select2({
                                dropdownParent: $(`#tambah_perencanaan .modal-content`)
                            });
                        },
                    });
                });

                $(document).on('click', '.remove_baris', function() {
                    var delete_row = $(this).attr("count");
                    $(".baris" + delete_row).remove();
                })
            }
            $(document).on("change", '.obat_pakan_input', function() {
                var id_produk = $(this).val()
                var count = $(this).attr('count')
                if (id_produk == 'tambah') {
                    $("#tambah_obat_pakan").modal('show')
                } else {
                    $.ajax({
                        type: "GET",
                        url: "{{ route('dashboard_kandang.get_stok_obat_pakan') }}?id_produk=" + id_produk,
                        dataType: 'json',
                        success: function(r) {
                            $(".get_dosis_satuan" + count).val(r.dosis_satuan);
                            $(".get_campuran_satuan" + count).val(r.campuran_satuan);
                        }
                    });
                }
            })
            $(document).on('submit', '#form_tambah_obat_pakan', function(e) {
                e.preventDefault()
                var datas = $("#form_tambah_obat_pakan").serialize()
                $.ajax({
                    type: "GET",
                    url: "{{ route('dashboard_kandang.save_tambah_obat_pakan') }}",
                    data: datas,
                    success: function(response) {
                        toast('Berhasil tambah Obat Pakan')
                        loadObatPakan()
                        $("#tambah_obat_pakan").modal('hide')

                    }
                });
            })

            function loadObatAir() {
                $.ajax({
                    type: "GET",
                    url: "{{ route('dashboard_kandang.load_obat_air') }}",
                    success: function(r) {
                        $("#load_obat_air").html(r);
                        $('.select2-edit').select2({
                            dropdownParent: $(`#tambah_perencanaan .modal-content`)
                        });
                        plusRowObatAir('tbhObatAir', 'dashboard_kandang/tbh_obatAir')
                    }
                });
            }

            function plusRowObatAir(classPlus, url) {
                $(document).on("click", "." + classPlus, function() {
                    count += 1;
                    $.ajax({
                        url: `${url}?count=` + count,
                        type: "GET",
                        success: function(data) {
                            $("#" + classPlus).append(data);
                            $(".select2-pakan").select2({
                                dropdownParent: $(`#tambah_perencanaan .modal-content`)
                            });
                        },
                    });
                });

                $(document).on('click', '.remove_baris', function() {
                    var delete_row = $(this).attr("count");
                    $(".baris" + delete_row).remove();
                })
            }
            $(document).on("change", '.obat_air_input', function() {
                var id_produk = $(this).val()
                var count = $(this).attr('count')
                if (id_produk == 'tambah') {
                    $("#tambah_obat_air").modal('show')
                } else {
                    $.ajax({
                        type: "GET",
                        url: "{{ route('dashboard_kandang.get_stok_obat_air') }}?id_produk=" + id_produk,
                        dataType: 'json',
                        success: function(r) {
                            $(".get_dosis_satuan_air" + count).val(r.dosis_satuan);
                            $(".get_campuran_satuan_air" + count).val(r.campuran_satuan);
                        }
                    });
                }
            })
            $(document).on('submit', '#form_tambah_obat_air', function(e) {
                e.preventDefault()
                var datas = $("#form_tambah_obat_air").serialize()
                $.ajax({
                    type: "GET",
                    url: "{{ route('dashboard_kandang.save_tambah_obat_air') }}",
                    data: datas,
                    success: function(response) {
                        toast('Berhasil tambah Obat Air')
                        loadObatAir()
                        $("#tambah_obat_air").modal('hide')

                    }
                });
            })

            // obat ayam
            function loadObatAyam() {
                $.ajax({
                    type: "GET",
                    url: "{{ route('dashboard_kandang.load_obat_ayam') }}",
                    success: function(r) {
                        $("#load_obat_ayam").html(r);
                        $('.select2-edit').select2({
                            dropdownParent: $(`#tambah_perencanaan .modal-content`)
                        });
                    }
                });
            }
            $(document).on("change", '.obat_ayam_input', function() {
                var id_produk = $(this).val()
                var count = $(this).attr('count')
                if (id_produk == 'tambah') {
                    $("#tambah_obat_ayam").modal('show')
                } else {
                    $.ajax({
                        type: "GET",
                        url: "{{ route('dashboard_kandang.get_stok_obat_ayam') }}?id_produk=" + id_produk,
                        success: function(r) {
                            $(".get_dosis_satuan_ayam").val(r);
                        }
                    });
                }
            })
            $(document).on('submit', '#form_tambah_obat_ayam', function(e) {
                e.preventDefault()
                var datas = $("#form_tambah_obat_ayam").serialize()
                $.ajax({
                    type: "GET",
                    url: "{{ route('dashboard_kandang.save_tambah_obat_ayam') }}",
                    data: datas,
                    success: function(response) {
                        toast('Berhasil tambah Obat Ayam')
                        loadObatAyam()
                        $("#tambah_obat_ayam").modal('hide')

                    }
                });
            })
            // end perencanaan -------------------------------------
            modalSelect2()

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
                    success: function(data) {
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
                    success: function(data) {

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
