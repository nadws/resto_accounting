<x-theme.app title="{{ $title }}" table="Y" sizeCard="12">

    <x-slot name="cardHeader">
        <div class="col-lg-6">
            <h6 class="float-start mt-1">{{ $title }} Penjualan</h6>
        </div>

    </x-slot>


    <x-slot name="cardBody">
        <style>
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                color: #000000;
                line-height: 36px;
                /* font-size: 12px; */
                width: 170px;
            }

            .dhead {
                background-color: #435EBE !important;
                color: white;
            }
        </style>
        <form action="{{ route('dashboard_kandang.save_penjualan_umum') }}" method="post" class="save_jurnal">
            @csrf
            <section class="row">
                <div class="col-lg-12">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th width="5%" class="dhead">Tanggal</th>
                                <th width="9%" class="dhead">No Nota</th>
                                <th width="9%" class="dhead">Nota Manual</th>
                                <th width="10%" class="dhead">Pelanggan</th>
                                <th width="15%" class="dhead">Supir</th>
                                <th width="15%" class="dhead">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <input type="date" value="{{ date('Y-m-d') }}" class="form-control"
                                        name="tgl">
                                </td>
                                <td>
                                    <input readonly value="PAGL-{{ $no_nota }}" type="text" required
                                        class="form-control">
                                    <input value="{{ $no_nota }}" type="hidden" required class="form-control"
                                        name="no_nota">
                                </td>
                                <td>
                                    <input type="text" required class="form-control" name="nota_manual">
                                </td>
                                <td>
                                    <select required name="id_customer" class="form-control select2" id="">
                                        <option value="">- Pilih Customer -</option>
                                        @foreach ($customer as $d)
                                            <option value="{{ $d->id_customer }}">
                                                {{ $d->nm_customer }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="driver" class="form-control">
                                </td>
                                <td>
                                    <input type="text" name="ket" class="form-control">
                                </td>
                            </tr>
                        </tbody>


                    </table>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th width="20%" class="dhead">Produk</th>
                                <th width="5%" class="dhead">Qty</th>
                                <th width="10%" class="dhead text-end">Harga Satuan</th>
                                <th width="10%" class="dhead text-end">Total Rp</th>
                                <th width="5%" class="text-center dhead">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="id_produk[]" required class="form-control select2 produk-change"
                                        id="">
                                        <option value="">- Pilih Produk -</option>
                                        @foreach ($produk as $d)
                                            <option value="{{ $d->id_produk }}">{{ $d->nm_produk }}
                                                ({{ strtoupper($d->satuan->nm_satuan) }})
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input count="1" name="qty[]" value="0" type="text"
                                        class="form-control qty qty1">
                                </td>
                                <td>
                                    <input type="text" class="form-control dikanan setor-nohide text-end"
                                        value="Rp. 0" count="1">
                                    <input type="hidden" class="form-control dikanan setor-hide setor-hide1"
                                        value="" name="rp_satuan[]">
                                </td>
                                <td>
                                    <input readonly type="text" class="form-control dikanan ttlrp-nohide1 text-end"
                                        value="Rp. 0" count="1">
                                    <input type="hidden" class="form-control dikanan ttlrp-hide ttlrp-hide1"
                                        value="" name="total_rp[]">
                                </td>
                            </tr>
                        </tbody>
                        <tbody id="tbh_baris">
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="9">
                                    <button type="button" class="btn btn-block btn-lg tbh_baris"
                                        style="background-color: #F4F7F9; color: #8FA8BD; font-size: 14px; padding: 13px;">
                                        <i class="fas fa-plus"></i> Tambah Produk Baru
                                    </button>
                                </th>
                            </tr>
                        </tfoot>
                    </table>


                </div>
            </section>
    </x-slot>
    <x-slot name="cardFooter">
        <button type="submit" class="float-end btn btn-primary">Simpan</button>
        <button class="float-end btn btn-primary btn_save_loading" type="button" disabled hidden>
            <span class="spinner-border spinner-border-sm " role="status" aria-hidden="true"></span>
            Loading...
        </button>
        <a href="{{ route('dashboard_kandang.penjualan_umum') }}"
            class="float-end btn btn-outline-primary me-2">Batal</a>
        </form>
    </x-slot>



    @section('scripts')
        <script>
            $('.select2').select2()
            var count = 3;
            plusRow(count, 'tbh_baris', 'tbh_add')

            $(document).on("keyup", ".setor-nohide", function() {
                var count = $(this).attr("count");
                var input = $(this).val();
                var qty = $('.qty' + count).val()

                input = input.replace(/[^\d\,]/g, "");
                input = input.replace(".", ",");
                input = input.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
                if (input === "") {
                    $(this).val("");
                    $('.setor-hide' + count).val(0)
                } else {
                    $(this).val("Rp " + input);
                    input = input.replaceAll(".", "");
                    input2 = input.replace(",", ".");
                    var ttl_rp = parseFloat(input) * parseFloat(qty)
                    $(".ttlrp-nohide" + count).val(ttl_rp.toLocaleString("id-ID", {
                        style: "currency",
                        currency: "IDR",
                    }));
                    $(".ttlrp-hide" + count).val(ttl_rp);
                    $('.setor-hide' + count).val(input2)
                }


                var total_rpAtas = 0;
                $(".ttlrp-hide").each(function() {
                    total_rpAtas += parseFloat($(this).val());
                });

                var totalRupiah = total_rpAtas.toLocaleString("id-ID", {
                    style: "currency",
                    currency: "IDR",
                });
                $(".total_kredit").text(totalRupiah);

                var total_debit = 0;
                $(".debit").each(function() {
                    total_debit += parseFloat($(this).val());
                });
                var total_kredit = 0;
                $(".kredit").each(function() {
                    total_kredit += parseFloat($(this).val());
                });

                var selisih = total_rpAtas - total_debit - total_kredit;
                var selisih_total = selisih.toLocaleString("id-ID", {
                    style: "currency",
                    currency: "IDR",
                });
                $(".total").text(selisih_total);

                if (selisih === 0) {
                    $(".cselisih").css("color", "green");
                    $(".button-save").removeAttr("hidden");
                } else {
                    $(".cselisih").css("color", "red");
                    $(".button-save").attr("hidden", true);
                }
                $(".selisih").text(selisih_total);
            });
        </script>
    @endsection
</x-theme.app>
