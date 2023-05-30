<x-theme.app title="{{ $title }}" table="Y" sizeCard="12">
    <x-slot name="cardHeader">
        <div class="row justify-content-end">
            <h6>Penyesuaian Atk & Peralatan</h6>

        </div>
    </x-slot>
    <x-slot name="cardBody">
        <form action="{{ route('penyesuaian.save_aktiva') }}" method="post" class="save_jurnal">
            @csrf
            <div class="row mb-4">
                <div class="col-lg-12">
                    <ul class="nav nav-pills float-start">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->route()->getName() == 'penyesuaian.aktiva'? 'active': '' }}"
                                aria-current="page" href="{{ route('penyesuaian.aktiva') }}">Aktiva</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Peralatan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->route()->getName() == 'penyesuaian.atk'? 'active': '' }}"
                                href="{{ route('penyesuaian.atk') }}">Atk</a>
                        </li>
                    </ul>

                </div>
                <div class="col-lg-12">
                    <hr style="border: 2px solid #435EBE">
                </div>
            </div>

            <section class="row">
                {{--    @php
                    $total = 0;
                @endphp
                @foreach ($aktiva as $a)
                    @php
                        $total += $a->biaya_depresiasi;
                    @endphp
                @endforeach
                --}}
                <div class="col-lg-12">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="dhead">Bulan</th>
                                <th class="dhead">No Nota</th>
                                <th class="dhead">Akun Debit</th>
                                <th class="dhead">Debit</th>
                                <th class="dhead">Akun Kredit</th>
                                <th class="dhead">Kredit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <input type="text" class="form-control"
                                        value="{{ date('F Y', strtotime($tgl)) }}" readonly>
                                    <input type="hidden" class="form-control" name="tgl"
                                        value="{{ date('Y-m-d', strtotime($tgl)) }}">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="no_nota"
                                        value="JP-ATK-{{ $nota }}">

                                </td>
                                <td>
                                    <input type="text" readonly
                                        value="{{ ucwords(strtolower($akunBiaya->nm_akun)) }}" class="form-control">
                                    <input type="hidden" name="id_akun_debit" readonly
                                        value="{{ $akunBiaya->id_akun }}" class="form-control">
                                </td>
                                <td>
                                    <input type="text" class="form-control text-end total" readonly value="Rp 0">
                                    <input type="hidden" class="total_biasa" name="debit_kredit" value="0">
                                </td>
                                <td>
                                    <input type="text" readonly value="{{ ucwords(strtolower($akunAtk->nm_akun)) }}"
                                        class="form-control">
                                    <input type="hidden" name="id_akun_kredit" readonly
                                        value="{{ $akunAtk->id_akun }}" class="form-control">
                                </td>
                                <td>
                                    <input type="text" class="form-control text-end total" readonly value="Rp 0">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            

                <div class="col-lg-12">
                    <hr style="border: 1px solid #435EBE">
                </div>

                <div class="col-lg-12">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="dhead" width="15%">Tanggal Perolehan</th>
                                <th class="dhead" width="20%">Barang</th>
                                <th class="dhead" width="8%" style="text-align: center">Stok Sisa</th>
                                <th class="dhead" width="13%" style="text-align: right">Harga Satuan</th>
                                <th class="dhead" width="13%" style="text-align: right">Total</th>
                                <th class="dhead" width="10%">Stok Aktual</th>
                                <th class="dhead" width="13%" style="text-align: right">Total Opname</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($atk as $no => $d)
                                @php
                                    $sisa = $d->debit - $d->kredit;
                                @endphp
                                <tr>
                                    <td>{{ $d->tgl1 }}</td>
                                    <td>{{ ucwords($d->nm_produk) }}</td>
                                    <td align="center">{{ $sisa }}</td>
                                    <td align="right">Rp. </td>
                                    <td align="right">Rp. 200.000</td>
                                    <td>
                                        <input type="text" class="form-control">
                                    </td>
                                    <td align="right">
                                        Rp. 300.000
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        {{-- <tbody>
                                @foreach ($aktiva as $no => $a)
                                    @if (round($a->h_perolehan - $a->beban, 0) <= '0')
                                        @php continue; @endphp
                                    @else
                                    @endif
                                    <tr>
                                        <td>{{ date('d-m-Y', strtotime($a->tgl)) }}</td>
                                        <td>{{ $a->nm_aktiva }}</td>
                                        <td>{{ number_format($a->h_perolehan, 0) }}</td>
                                        <td>{{ number_format($a->h_perolehan - $a->beban, 0) }} </td>
                                        <td>
                                            <input type="text" class="form-control beban beban{{ $no + 1 }}"
                                                count="{{ $no + 1 }}"
                                                value="Rp {{ number_format($a->biaya_depresiasi, 2, ',', '.') }}">
    
                                            <input type="hidden" name="b_penyusutan[]"
                                                class="beban_biasa beban_biasa{{ $no + 1 }}"
                                                value="{{ round($a->biaya_depresiasi, 2) }}">
                                            <input type="hidden" name="id_aktiva[]" value="{{ $a->id_aktiva }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody> --}}
                    </table>
                </div>
            </section>
    </x-slot>
    <x-slot name="cardFooter">
        <button type="submit" class="float-end btn btn-primary button-save">Simpan</button>
        <button class="float-end btn btn-primary btn_save_loading" type="button" disabled hidden>
            <span class="spinner-border spinner-border-sm " role="status" aria-hidden="true"></span>
            Loading...
        </button>
        <a href="{{ route('penyesuaian.aktiva') }}" class="float-end btn btn-outline-primary me-2">Batal</a>
        {{-- <a href="{{route('jurnal')}}" class="float-end btn btn-outline-primary me-2">Batal</a> --}}
        </form>
    </x-slot>
    @section('scripts')
        <script>
            $(document).ready(function() {
                $(document).on("keyup", ".beban", function() {
                    var count = $(this).attr("count");
                    var input = $(this).val();
                    input = input.replace(/[^\d\,]/g, "");
                    input = input.replace(".", ",");
                    input = input.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");

                    if (input === "") {
                        $(this).val("");
                        $('.beban_biasa' + count).val(0)
                    } else {
                        $(this).val("Rp " + input);
                        input = input.replaceAll(".", "");
                        input2 = input.replace(",", ".");
                        $('.beban_biasa' + count).val(input2)

                    }
                    var total_debit = 0;
                    $(".beban_biasa").each(function() {
                        total_debit += parseFloat($(this).val());
                    });

                    var totalRupiah = total_debit.toLocaleString("id-ID", {
                        style: "currency",
                        currency: "IDR",
                    });

                    console.log(totalRupiah);
                    var debit = $(".total").val(totalRupiah);
                    var debit_biasa = $(".total_biasa").val(total_debit);
                });
                aksiBtn("form");

            });
        </script>
    @endsection
</x-theme.app>
