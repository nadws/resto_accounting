<x-theme.app title="{{ $title }}" table="Y" sizeCard="12">
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
        <section class="row">
            <style>
                .abu {
                    background-color: #d3d3d3 !important;
                    color: rgb(37, 37, 37);
                }

                .putih {
                    background-color: #f6e6e6 !important;
                    color: rgb(37, 37, 37);
                }
            </style>
            <table class="table table-bordered table-hover " id="table">
                <thead>
                    <tr>
                        <th colspan="2"></th>
                        <th colspan="3" class="text-center abu">Populasi</th>
                        <th colspan="5" class="text-center abu">Telur</th>
                        <th class="putih">Pupuk</th>
                        <th colspan="2" class="text-center abu">pakan</th>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <th width="15%">Kandang</th>
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

                    </tr>
                </thead>
                <tbody>
                    @foreach ($kandang as $no => $d)
                        <tr>
                            <td>{{ tanggal(date('Y-m-d')) }}</td>
                            <td>{{ $d->nm_kandang }}</td>
                            <td>82 / 91%</td>
                            <td>2321</td>

                            {{-- mati dan jual --}}
                            <td>1 / 2</td>
                            {{-- end mati dan jual --}}

                            {{-- telur --}}

                            @php
                                $telur = DB::table('telur_produk')->get();
                                $ttlKg = 0;
                            @endphp
                            @foreach ($telur as $t)
                                @php
                                    $tgl = date('Y-m-d');
                                    $stok = DB::selectOne("SELECT * FROM stok_telur as a WHERE a.id_kandang = '$d->id_kandang' AND a.tgl = '$tgl' AND a.id_telur = '$t->id_produk_telur'");
                                    $ttlKg += $stok->kg ?? 0;
                                @endphp

                                <td data-bs-toggle="modal" id_kandang="{{ $d->id_kandang }}"
                                    nm_kandang="{{ $d->nm_kandang }}" class="tambah_telur"
                                    data-bs-target="#tambah_telur">
                                    {{ $stok->pcs ?? 0 }}
                                </td>
                            @endforeach
                            <td>2</td>

                            {{-- end telur --}}

                            <td>150</td>
                            <td>65</td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </section>
        @include('dashboard_kandang.modal.tambah_kandang')
        @include('dashboard_kandang.modal.tambah_telur')
    </x-slot>
    @section('js')
        <script>
            modalSelect2()

            function modalSelect2() {
                $('.select2-kandang').select2({
                    dropdownParent: $('#tambah_kandang .modal-content')
                });
            }

            $(document).on('click', '.tambah_telur', function() {
                var id_kandang = $(this).attr('id_kandang')
                var nm_kandang = $(this).attr('nm_kandang')

                $("#id_kandang_tambah").val(id_kandang);
                $("#nm_kandang_tambah").val(nm_kandang);
            })

            $(document).on("keyup", ".pcs", function() {
                var count = $(this).attr('count');
                var pcs = $('.pcs' + count).val()
                console.log()
                var ikat = parseFloat(pcs) / 180;
                $('.ikat' + count).val(ikat.toFixed(1));
            });

            $(document).on("keyup", ".rak", function() {
                var count = $(this).attr('count');
                var rak = $('.rak' + count).val()
                console.log(rak)
                var potongan = (parseFloat(rak) * 100) / ;
                // $('.ikat' + count).val(ikat.toFixed(1));
            });
        </script>
    @endsection
</x-theme.app>
