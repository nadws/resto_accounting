<x-theme.app title="{{ $title }}" table="Y" sizeCard="12">

    <x-slot name="cardHeader">
        <div class="row justify-content-end">

            <div class="col-lg-6">
                <h6 class="float-start mt-1">{{ $title }} </h6>
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
        <form action="{{ route('jurnal.create') }}" method="post" class="save_jurnal">
            @csrf
            <section class="row">
                <table class="table">
                    <thead>
                        <tr>
                            <th width="130" class="dhead">Tgl Transaksi</th>
                            <th width="130" class="dhead">No Nota</th>
                            <th class="dhead">Suplier</th>
                            <th class="dhead">Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <td>
                            <input type="date" name="tgl" class="form-control" value="{{ date('Y-m-d') }}">
                        </td>
                        <td>
                            <input type="text" name="no_nota" readonly value="PO-{{ $no_po }}"
                                class="form-control">
                        </td>
                        <td>
                            <select name="id_suplier" id="" class="select22">
                                <option value="">Pilih suplier</option>
                                @foreach ($suplier as $s)
                                    <option value="{{ $s->id_suplier }}">{{ strtoupper($s->nm_suplier) }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="text" name="catatan" placeholder="tambahkan catatan jika ada"
                                class="form-control">
                        </td>
                    </tbody>
                </table>

                <table class="table" x-data="{}">
                    <thead>
                        <tr>
                            <th class="dhead">No</th>
                            <th width="250" class="dhead">Bahan</th>
                            <th width="80" class="dhead text-end">Qty</th>
                            <th class="dhead">Satuan</th>
                            <th width="150" class="dhead text-end">Rp Satuan</th>
                            <th width="190" class="dhead text-end">Total Rp</th>
                            <th class="dhead">Keterangan</th>
                            <th class="dhead">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>
                                <select name="id_bahan[]" class="select22">
                                    <option value="">Pilih Bahan</option>
                                    @foreach ($bahan as $b)
                                        <option value="{{ $b->id_list_bahan }}">{{ strtoupper($b->nm_bahan) }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input x-mask:dynamic="$money($input)" type="text"
                                    class="form-control text-end qtyKeyup qtyKeyup1" count="1" placeholder="qty"
                                    name="qty[]">
                            </td>
                            <td>
                                <select name="id_satuan_beli[]" class="select22">
                                    <option value="">Pilih Satuan Beli</option>
                                    @foreach ($satuan as $s)
                                        <option value="{{ $s->id_satuan }}">{{ strtoupper($s->nm_satuan) }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input x-mask:dynamic="$money($input)" type="text"
                                    class="form-control text-end rpSatuanKeyup rpSatuanKeyup1" count="1"
                                    placeholder="rp satuan" name="rp_satuan[]">
                            </td>
                            <td>
                                <input readonly x-mask:dynamic="$money($input)" type="text"
                                    class="form-control text-end ttlRp totalRpKeyup1" placeholder="total rp"
                                    name="ttl_rp[]">
                            </td>
                            <td>
                                <input type="text" placeholder="keterangan" class="form-control" name="ket[]">
                            </td>
                            <td>

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
                                    <i class="fas fa-plus"></i> Tambah Baris Baru

                                </button>
                            </th>
                        </tr>
                    </tfoot>
                </table>

                <div class="row justify-content-end">
                    <div class="col-4 text-start">
                        <table class="table table-hover" x-data="{
                            bPengiriman: false,
                            uangMuka: false,
                        }">
                            <tr>
                                <th>Sub Total</th>
                                <th>
                                    <input type="hidden" class="subTotalValue">
                                    <h6 class="subTotal text-end">0</h6>
                                </th>
                            </tr>

                            <tr>
                                <td colspan="2">
                                    <a href="#" @click.prevent="bPengiriman = ! bPengiriman"><i
                                            class="fas fa-plus"></i> Biaya Pengiriman</a>
                                </td>
                            </tr>
                            <tr x-show="bPengiriman">
                                <th>
                                    BIaya Pengiriman
                                </th>
                                <td align="right">
                                    <input style="width:130px" type="text" x-mask:dynamic="$money($input)"
                                        class="form-control text-end selectAll bPengirimanKeyup" value="0">
                                </td>
                            </tr>
                            <tr>
                                <th>Total</th>
                                <th>
                                    <input type="hidden" class="grandTotalValue">
                                    <h6 class="grandTotal text-end">0</h6>
                                </th>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <a href="#" @click.prevent="uangMuka = ! uangMuka"><i
                                            class="fas fa-plus"></i> Uang Muka</a>
                                </td>
                            </tr>
                            <tr x-show="uangMuka">
                                <th>
                                    <select name="id_akun" id="" class="select22">
                                        <option value="">Pilih Akun</option>
                                        @foreach ($akunPembayaran as $d)
                                            <option value="{{ $d->id_akun }}">{{ strtoupper($d->nm_akun) }}</option>
                                        @endforeach
                                    </select>
                                </th>
                                <td align="right">
                                    <input style="width:130px" type="text" x-mask:dynamic="$money($input)"
                                        class="form-control text-end selectAll uangMukaKeyup" value="0">
                                </td>
                            </tr>
                            <tr>
                                <th><u>Sisa Tagihan</u></th>
                                <th>
                                    <input type="hidden" class="sisaTagihanValue">
                                    <h5 class="text-end"><u><em class="sisaTagihan ">0</em></u></h5>
                                </th>
                            </tr>
                        </table>
                    </div>
                </div>
            </section>

    </x-slot>
    <x-slot name="cardFooter">
        <div class="alert_saldo">
            <button type="submit" class="float-end btn btn-primary">Simpan</button>
        </div>

        <a href="{{ route('po.index') }}" class="float-end btn btn-outline-primary me-2">Batal</a>
        </form>




    </x-slot>
    @section('scripts')
        <script>
            $('.select22').select2();

            plusRow(1, 'tbh_baris', 'tbh_baris')

            function formatInd(isi) {
                return isi.toLocaleString("id-ID", {
                    style: "currency",
                    currency: "IDR",
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 2
                });

            }

            function updateTotalRp(count) {
                const qty = $(`.qtyKeyup${count}`).val().replace(',', '')
                const rpSatuan = $(`.rpSatuanKeyup${count}`).val().replace(',', '')
                const ttlRp = rpSatuan * qty;
                console.log(qty + ' ' + rpSatuan + ' ' + ttlRp)
                $(`.totalRpKeyup${count}`).val(ttlRp);

                var sum = 0
                $('.ttlRp').each(function() {
                    sum += parseFloat($(this).val(), 10) || 0;
                })
                var sumFormat = formatInd(sum)

                $('.subTotal').text(sumFormat)
                $('.subTotalValue').val(sum)
            }

            function updateTotal(displayClass, valueClass, total) {
                const totalFormat = formatInd(total);
                $(`.${displayClass}`).text(totalFormat);
                $(`.${valueClass}`).val(total);
            }

            function updateBiayaDanUangMuka() {
                const bPengiriman = parseFloat($('.bPengirimanKeyup').val().replace(',', ''));
                const uangMuka = parseFloat($('.uangMukaKeyup').val().replace(',', ''));
                const subTotal = parseFloat($('.subTotalValue').val());
                const grandTotal = subTotal + bPengiriman;
                const sisaTagihan = grandTotal - uangMuka;

                updateTotal('grandTotal', 'grandTotalValue', grandTotal);
                updateTotal('sisaTagihan', 'sisaTagihanValue', sisaTagihan);
            }

            $(document).on('keyup', '.qtyKeyup, .rpSatuanKeyup', function() {
                const count = $(this).attr('count');
                updateTotalRp(count);
                updateBiayaDanUangMuka();
            });

            $(document).on('keyup', '.bPengirimanKeyup, .uangMukaKeyup', updateBiayaDanUangMuka);



            $(document).on('click', '.selectAll', function() {
                this.select()
            })
        </script>
    @endsection
</x-theme.app>
