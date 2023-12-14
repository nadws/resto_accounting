<x-theme.app title="{{ $title }}" table="Y" sizeCard="12">
    <x-slot name="cardHeader">
        <div class="col-lg-6">
            <h6 class="float-start mt-1">{{ $title }} {{ tanggal($tgl) }}</h6>
        </div>
        <div class="row justify-content-end">


        </div>
        {{-- <div class="row justify-content-end">
            <x-theme.button modal="T" href="/jual/export?tgl={{$tgl}}" icon="fa-file-excel"
                addClass="float-end float-end btn btn-success me-2" teks="Export" />
        </div> --}}
    </x-slot>
    <x-slot name="cardBody">
        <form action="{{ route('jurnalpenyesuaian.save_atk') }}" method="post" class="save_jurnal">
            @csrf
            <div class="row mb-4">
                <div class="col-lg-12">
                    <ul class="nav nav-pills float-start">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->route()->getName() == 'jurnalpenyesuaian.aktiva'? 'active': '' }}"
                                aria-current="page" href="{{ route('jurnalpenyesuaian.aktiva') }}">Aktiva</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link  {{ request()->route()->getName() == 'jurnalpenyesuaian.peralatan'? 'active': '' }}"
                                href="{{ route('jurnalpenyesuaian.peralatan') }}">Peralatan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link  {{ request()->route()->getName() == 'jurnalpenyesuaian.atk'? 'active': '' }}"
                                href="{{ route('jurnalpenyesuaian.atk') }}">Atk</a>
                        </li>
                    </ul>

                </div>
                <div class="col-lg-12">
                    <hr style="border: 2px solid #435EBE">
                </div>
            </div>

            <section class="row">
                <div class="col-lg-12">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="dhead" width="17%">Bulan</th>
                                <th class="dhead" width="13%">No Nota</th>
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
                                        value="JPATK-{{ $nota }}">
                                    <input type="hidden" class="form-control" name="urutan"
                                        value="{{ $nota }}">
                                </td>
                                <td>
                                    {{ ucwords(strtolower($akunBiaya->nm_akun)) }}
                                    <input type="hidden" name="id_akun_debit" readonly
                                        value="{{ $akunBiaya->id_akun }}" class="form-control">
                                </td>
                                <td>
                                    <input type="text" class="form-control text-end totalFormat" readonly
                                        value="Rp 0">
                                    <input type="hidden" class="total" name="debit_kredit" value="0">
                                </td>
                                <td>
                                    {{ ucwords(strtolower($akunAtk->nm_akun)) }}

                                    <input type="hidden" name="id_akun_kredit" readonly
                                        value="{{ $akunAtk->id_akun }}" class="form-control">
                                </td>
                                <td>
                                    <input type="text" class="form-control text-end totalFormat" readonly
                                        value="Rp 0">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-lg-12">
                    <hr style="border: 1px solid #435EBE">
                </div>
                <div class="col-lg-12">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="dhead" width="20%">Barang</th>
                                <th class="dhead text-end" width="8%">Stok Sisa</th>
                                <th class="dhead text-end" width="13%">Harga Satuan</th>
                                <th class="dhead text-end" width="13%">Total</th>
                                <th class="dhead" width="10%">Stok Aktual</th>
                                <th class="dhead text-end" width="8%">Selisih</th>
                                <th class="dhead text-end" width="15%">Total Opname</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($atk as $no => $d)
                                @php
                                    $sisa = $d->stok_sisa;
                                    $rp_satuan = $d->rp_satuan;
                                    $ttl = $d->stok_sisa * $d->rp_satuan;
                                @endphp
                                <input type="hidden" name="id_atk[]" value="{{ $d->id_atk }}">
                                <input type="hidden" name="sisa[]" class="sisa{{ $no }}"
                                    value="{{ $sisa }}">
                                <input type="hidden" name="rp_satuan[]" class="rp_satuan{{ $no }}"
                                    value="{{ $d->rp_satuan }}">
                                <input type="hidden" name="ttl[]" value="{{ $ttl }}"
                                    class="ttl{{ $no }}">
                                <tr>
                                    <td>{{ ucwords($d->nm_atk) }}</td>
                                    <td align="right">{{ $d->stok_sisa }}</td>
                                    <td align="right">Rp. {{ number_format($d->rp_satuan, 0) }}</td>
                                    <td align="right">Rp. {{ number_format($d->stok_sisa * $d->rp_satuan, 0) }}</td>
                                    <td>
                                        <input type="text" class="form-control stok_aktual text-end"
                                            name="fisik[]" row="{{ $no }}" value="{{ $d->stok_sisa }}">
                                    </td>
                                    <td align="right" class="selisihFisik{{ $no }}">0
                                    </td>
                                    <td>
                                        <input value="Rp. 0" style="text-align: right" readonly type="text"
                                            class="form-control ttl_opnameFormat{{ $no }}">

                                        <input value="0" type="hidden" name="ttl_opname[]"
                                            class="form-control ttl_opname{{ $no }} ttl_opname">
                                    </td>
                                </tr>
                                <tr class="pering pering{{ $no }}">
                                    <td colspan="8">
                                        <p class="text-danger text-center">Jika terjadi penambahan barang harap isi di
                                            stok
                                            atk terlebih
                                            dahulu</p>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
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
        <a href="{{ route('jurnalpenyesuaian.index') }}" class="float-end btn btn-outline-primary me-2">Batal</a>
        {{-- <a href="{{route('jurnal')}}" class="float-end btn btn-outline-primary me-2">Batal</a> --}}
        </form>
    </x-slot>
    @section('scripts')
        <script>
            $(document).ready(function() {
                $('.pering').hide()
                $(".select-gudang").change(function(e) {
                    e.preventDefault();
                    var gudang_id = $(this).val()
                    document.location.href = `/penyesuaian/atk/${gudang_id}`
                });

                $(document).on('keyup', '.stok_aktual', function() {
                    var row, isi, ttl, ttlOpname, selisih

                    row = $(this).attr('row')
                    isi = $(this).val()
                    ttl = $(".ttl" + row).val();
                    sisa = $(".sisa" + row).val();
                    rp_satuan = $(".rp_satuan" + row).val();
                    selisih_tes = parseFloat(sisa) - parseFloat(isi);
                    selisih = $(".selisih" + row).val(selisih_tes);
                    ttlOpname = parseFloat(selisih_tes * rp_satuan)
                    ttlOpnameFormat = parseFloat(selisih_tes * rp_satuan)

                    $(".ttl_opnameFormat" + row).val('Rp. ' + ttlOpnameFormat.toLocaleString());
                    $(".ttl_opname" + row).val(ttlOpname);
                    $(".selisihFisik" + row).text(selisih_tes);

                    if (selisih_tes < 0) {
                        $(".button-save").hide();
                        $(".pering" + row).show();
                    } else {
                        $(".button-save").show();
                        $(".pering" + row).hide();
                    }

                    var total = 0;
                    $('.ttl_opname').each(function() {
                        var value = parseFloat($(this).val());
                        if (!isNaN(value)) {
                            total += value;
                        }
                    })
                    $(".total").val(total);
                    $(".totalFormat").val('Rp. ' + total.toLocaleString());
                });

                $("form").on("keypress", function(e) {
                    if (e.which === 13) {
                        e.preventDefault();
                        return false;
                    }
                });

            });
        </script>
    @endsection
</x-theme.app>
