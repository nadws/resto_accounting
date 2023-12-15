<x-theme.app title="{{ $title }}" table="Y" sizeCard="8">

    <x-slot name="cardHeader">
        <div class="row justify-content-end">
            <div class="col-lg-6">
                <h6 class="float-start mt-1">{{ $title }} {{ $kategori }}</h6>
            </div>
            <div class="col-lg-6">
                {{-- <a href="{{ route('controlflow') }}" class="btn btn-primary float-end"><i class="fas fa-home"></i></a> --}}
            </div>

        </div>

    </x-slot>
    <style>
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #000000;
            line-height: 36px;
            /* font-size: 12px; */
            width: 150px;

        }
    </style>

    <x-slot name="cardBody">
        <table width="100%" cellpadding="10px">
            <tr>
                <th style="background-color: white;" width="10%">Tanggal</th>
                <th style="background-color: white;" width="2%">:</th>
                <th style="background-color: white;">{{ date('d-m-Y', strtotime($head_jurnal->tgl)) }}</th>
                <th style="background-color: white;" width="10%">No Nota</th>
                <th style="background-color: white;" width="2%">:</th>
                <th style="background-color: white;">{{ $head_jurnal->no_nota }}</th>
            </tr>
            <tr>
                <th style="background-color: white; " width="10%">Post Center</th>
                <th style="background-color: white; " width="2%">:</th>
                <th style="background-color: white; ">{{ $head_jurnal->nm_post }}</th>
            </tr>
        </table>
        <br>
        <br>

        <style>
            .dhead {
                background-color: #435EBE !important;
                color: white;
            }

            .dborder {
                border-color: #435EBE
            }
        </style>
        <table class="table table-hover table-bordered dborder">
            <thead>
                <tr>
                    <th class="dhead" width="5">#</th>
                    <th class="dhead">Akun</th>
                    <th class="dhead">Keterangan</th>
                    <th class="dhead" style="text-align: right">Debit</th>
                    <th class="dhead" style="text-align: right">Kredit</th>
                    <th class="dhead" style="text-align: right">Admin</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($jurnal as $no => $a)
                    <tr>
                        <td>{{ $no + 1 }}</td>
                        <td>{{ $a->nm_akun }}</td>
                        <td>{{ $a->ket }}</td>
                        <td align="right">{{ number_format($a->debit, 0) }}</td>
                        <td align="right">{{ number_format($a->kredit, 0) }}</td>
                        <td>{{ $a->admin }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </x-slot>
    <x-slot name="cardFooter">
        @if ($kategori != 'pullet')
            <a href="#" class="btn btn-primary float-end" {{ $kategori == 'umum' ? 'hidden' : '' }}
                data-bs-toggle="modal" data-bs-target="#tambah">Tambahkan Ke
                {{ $kategori }}</a>
        @endif
        <a href="{{ empty($pembelian) ? route('jurnal.index', ['id_buku' => '13']) : route('jurnal.index', ['id_buku' => '10']) }}"
            class="float-end btn btn-outline-primary me-2">Kembali</a>
        <form
            action="{{ $kategori == 'aktiva' ? route('jurnal.save_aktiva') : ($kategori == 'peralatan' ? route('peralatan.save_aktiva') : route('jurnal.save_atk_pembalik')) }}"
            method="post" class="save_jurnal">
            @csrf
            <x-theme.modal title="Tambah {{ $kategori }}" idModal="tambah" size="modal-lg-max">
                <div class="row">
                    @if ($kategori == 'aktiva')
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="dhead" width="15%">Kelompok</th>
                                    <th class="dhead" width="15%">Nama Aktiva</th>
                                    <th class="dhead" width="14%">Tanggal Perolehan</th>
                                    <th class="dhead" width="14%">Nilai Perolehan</th>
                                    <th class="dhead" width="10%">Nilai/tahun (%)</th>
                                    <th class="dhead" width="10%" style="text-align: center">Umur</th>
                                    <th class="dhead" width="14%">Penyusutan Perbulan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="baris1">
                                    <td>
                                        <select name="id_kelompok[]" id=""
                                            class="select2 pilih_kelompok_{{ $kategori }} pilih_kelompok1"
                                            count='1'>
                                            <option value="">Pilih Kelompok</option>
                                            @foreach ($kelompok as $k)
                                                <option value="{{ $k->id_kelompok }}">{{ $k->nm_kelompok }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="text" name="nm_aktiva[]" class="form-control "
                                            value="{{ empty($pembelian) ? $head_jurnal->nm_post : $head_jurnal->ket }}"
                                            readonly></td>
                                    <td><input type="date" name="tgl[]" class="form-control"
                                            value="{{ $head_jurnal->tgl }}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control nilai_perolehan nilai_perolehan1"
                                            count='1' value="Rp. {{ number_format($head_jurnal->debit, 0) }}"
                                            readonly>
                                        <input type="hidden" name="h_perolehan[]"
                                            class="form-control  nilai_perolehan_biasa1"
                                            value="{{ $head_jurnal->debit }}">
                                    </td>
                                    <td>
                                        <p class="nilai_persen1 text-center"></p>
                                        <input type="hidden" class="inputnilai_persen1">
                                    </td>
                                    <td>
                                        <p class="umur1 text-center"></p>
                                    </td>
                                    <td>
                                        <p class="susut_bulan1 text-center"></p>
                                    </td>

                                </tr>


                            </tbody>
                        </table>
                    @elseif($kategori == 'peralatan')
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="dhead" width="15%">Kelompok</th>
                                    <th class="dhead" width="15%">Nama Peralatan</th>
                                    <th class="dhead" width="14%">Tanggal Perolehan</th>
                                    <th class="dhead" width="14%">Nilai Perolehan</th>
                                    <th class="dhead" width="10%" style="text-align: center">Umur</th>
                                    <th class="dhead" width="14%">Penyusutan Perbulan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="baris1">
                                    <td>
                                        <select name="id_kelompok[]" id=""
                                            class="select2 pilih_kelompok_{{ $kategori }} pilih_kelompok1"
                                            count='1'>
                                            <option value="">Pilih Kelompok</option>
                                            @foreach ($kelompok as $k)
                                                <option value="{{ $k->id_kelompok }}">{{ $k->nm_kelompok }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="text" name="nm_aktiva[]" class="form-control "
                                            value="{{ empty($pembelian) ? $head_jurnal->nm_post : $head_jurnal->ket }}">
                                    </td>
                                    <td><input type="date" name="tgl[]" class="form-control"
                                            value="{{ $head_jurnal->tgl }}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control nilai_perolehan nilai_perolehan1"
                                            count='1' value="Rp. {{ number_format($head_jurnal->debit, 0) }}">
                                        <input type="hidden" name="h_perolehan[]"
                                            class="form-control  nilai_perolehan_biasa1"
                                            value="{{ $head_jurnal->debit }}">
                                    </td>

                                    <td>
                                        <p class="umur1 text-center"></p>
                                    </td>
                                    <input type="hidden" class="periode1">
                                    <input type="hidden" class="umurInput1">

                                    <td>
                                        <p class="susut_bulan1 text-center"></p>
                                    </td>

                                </tr>


                            </tbody>
                        </table>
                    @else
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="dhead" width="15%">Tanggal</th>
                                    <th class="dhead" width="15%">Nama Atk</th>
                                    <th class="dhead" width="10%">Cfm</th>
                                    <th class="dhead" width="15%">Kategori</th>
                                    <th class="dhead" width="10%">Stok</th>
                                    <th class="dhead" width="13%">Satuan</th>
                                    <th class="dhead" width="14%">Total Rp</th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td>
                                        <input name="tgl" type="date" value="{{$head_jurnal->tgl}}" class="form-control">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="nm_atk"
                                            value="{{ empty($pembelian) ? $head_jurnal->nm_post : $head_jurnal->ket }}"
                                            readonly>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="cfm">
                                    </td>
                                    <td>
                                        <select name="id_kategori" id="" class="select2">
                                            <option value="">Pilih Kategori</option>
                                            @php
                                                $kategori = DB::table('kategori_atk')->get();

                                            @endphp
                                            @foreach ($kategori as $k)
                                                <option value="{{ $k->id_kategori }}">{{ $k->nm_kategori }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="stok">
                                    </td>
                                    <td>
                                        <select name="id_satuan" id="" class="select2">
                                            <option value="">Pilih Satuan</option>
                                            @foreach ($satuan as $k)
                                                <option value="{{ $k->id_satuan }}">{{ $k->nm_satuan }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control text-end"
                                            value="Rp. {{ number_format($head_jurnal->debit, 0) }}" readonly>
                                        <input type="hidden" name="total_rp" class="form-control"
                                            value="{{ $head_jurnal->debit }}">
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    @endif

                </div>
            </x-theme.modal>
        </form>

    </x-slot>
    @section('scripts')
        <script>
            $(document).ready(function() {
                $(document).on("change", ".pilih_kelompok_aktiva", function() {
                    var count = $(this).attr("count");
                    var id_kelompok = $('.pilih_kelompok' + count).val();
                    var nilai = $('.nilai_perolehan_biasa' + count).val()

                    $.ajax({
                        type: "GET",
                        url: "{{ route('jurnal.get_data_kelompok') }}?id_kelompok=" + id_kelompok,
                        dataType: "json",
                        success: function(data) {
                            $('.nilai_persen' + count).text(data['nilai_persen'] * 100 + ' %');
                            $('.inputnilai_persen' + count).val(data['nilai_persen']);
                            $('.umur' + count).text(data['tahun'] + ' Tahun');

                            var tarif = $('.inputnilai_persen' + count).val();
                            var susut_bulan = (parseFloat(nilai) * parseFloat(tarif)) / 12;

                            var susut_rupiah = susut_bulan.toLocaleString("id-ID", {
                                style: "currency",
                                currency: "IDR",
                            });

                            if (nilai === '') {
                                $('.susut_bulan' + count).text('Rp.0');

                            } else {
                                $('.susut_bulan' + count).text(susut_rupiah);

                            }

                        }
                    });
                });

                $(document).on("change", ".pilih_kelompok_peralatan", function() {
                    var count = $(this).attr("count");
                    var id_kelompok = $('.pilih_kelompok' + count).val();
                    var nilai = $('.nilai_perolehan_biasa' + count).val()

                    $.ajax({
                        type: "GET",
                        url: "{{ route('peralatan.get_data_kelompok') }}",
                        data: {
                            id_kelompok: id_kelompok
                        },
                        dataType: "json",
                        success: function(data) {
                            $('.nilai_persen' + count).text(data['nilai_persen'] * 100 + ' %');
                            $('.inputnilai_persen' + count).val(data['nilai_persen']);
                            $('.umur' + count).text(data['tahun'] + ' ' + data['periode']);
                            $(".periode" + count).val(data['periode']);
                            $(".umurInput" + count).val(data['tahun']);

                            var bulan_bagi = data['periode'] === 'Bulan' ? parseFloat(data[
                                'tahun']) : parseFloat(data['tahun']) * 12;

                            var susut_bulan = parseFloat(nilai) / parseFloat(bulan_bagi);
                            var susut_rupiah = susut_bulan.toLocaleString("id-ID", {
                                style: "currency",
                                currency: "IDR",
                            });

                            if (nilai === '') {
                                $('.susut_bulan' + count).text('Rp.0');

                            } else {
                                $('.susut_bulan' + count).text(susut_rupiah);

                            }

                        }
                    });
                });
                aksiBtn("form");
            });
        </script>
    @endsection


</x-theme.app>
