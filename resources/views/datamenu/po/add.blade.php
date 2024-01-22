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
        <form action="{{ route('po.create') }}" method="post" class="save_jurnal">
            @csrf
            <section class="row">
                <table class="table">
                    <thead>
                        <tr>
                            <th width="130" class="dhead">Tgl Transaksi</th>
                            <th width="130" class="dhead">No Nota</th>
                            <th width="130" class="dhead">Nota Manual</th>
                            <th class="dhead">Suplier</th>
                            <th class="dhead">Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <td>
                            <input type="date" name="tgl" class="form-control" value="{{ date('Y-m-d') }}">
                        </td>
                        <td>
                            <input type="hidden" name="no_nota" readonly value="{{ $no_po }}"
                                class="form-control">
                            <input type="text" name="" readonly value="PO-{{ $no_po }}"
                                class="form-control">
                        </td>
                        <td>
                           
                            <input required type="text" name="nota_manual" 
                                class="form-control">
                        </td>
                        <td>
                            <select required name="id_suplier" id="" class="select22">
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
                            <th width="120" class="dhead text-end">Qty</th>
                            <th class="dhead">Satuan</th>
                            <th class="dhead text-end" width="90">Diskon (%)</th>
                            <th class="dhead text-end" width="90">Pajak (%)</th>
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
                                <select required name="id_bahan[]" class="select22">
                                    <option value="">Pilih Bahan</option>
                                    @foreach ($bahan as $b)
                                        <option value="{{ $b->id_list_bahan }}">{{ strtoupper($b->nm_bahan) }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input required x-mask:dynamic="$money($input)" type="text"
                                    class="form-control text-end selectAll qtyKeyup qtyKeyup1" count="1"
                                    placeholder="qty" name="qty[]">
                            </td>
                            <td>
                                <select required name="id_satuan_beli[]" class="select22">
                                    <option value="">Pilih Satuan Beli</option>
                                    @foreach ($satuan as $s)
                                        <option value="{{ $s->id_satuan }}">{{ strtoupper($s->nm_satuan) }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <style>
                                input::-webkit-outer-spin-button,
                                input::-webkit-inner-spin-button {
                                    -webkit-appearance: none;
                                    margin: 0;
                                }

                                /* Firefox */
                                input[type=number] {
                                    -moz-appearance: textfield;
                                }
                            </style>
                            <td>
                                <input name="persen[]" type="number"
                                    class="form-control selectAll text-end persenKeyup persenKeyup1" count="1"
                                    x-mask:dynamic="$money($input)" max="100">
                            </td>
                            <td>
                                <input type="hidden" class="pajakValue pajakValue1">
                                <input name="pajak[]" type="number"
                                    class="form-control pajak selectAll text-end pajakKeyup pajakKeyup1" count="1"
                                    x-mask:dynamic="$money($input)" max="100">
                            </td>
                            <td>
                                <input required x-mask:dynamic="$money($input)" type="text"
                                    class="form-control selectAll text-end rpSatuanKeyup rpSatuanKeyup1" count="1"
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
                            diskon: false,
                            uangMuka: false,
                            bSelisih: false,
                        }">
                            <tr>
                                <th>Sub Total</th>
                                <th>
                                    <input type="hidden" class="subTotalValue">
                                    <h6 class="subTotal text-end">0</h6>
                                </th>
                            </tr>

                            <tr @click.prevent="diskon = ! diskon">
                                <td colspan="2">
                                    <a href="#"><i class="fas fa-plus"></i> Tambahan Diskon</a>
                                </td>
                            </tr>
                            <tr x-show="diskon">
                                <td align="right">
                                    <input value="persen" type="hidden" class="btn-check-value">
                                    <input value="persen" type="radio" class="btn-check btn-sm" name="bDiskonTipe"
                                        id="primary-outlined1" autocomplete="off" checked="">
                                    <label class="btn btn-outline-primary btn-sm" for="primary-outlined1">%</label>

                                    <input value="rp" type="radio" class="btn-check btn-sm" name="bDiskonTipe"
                                        id="primary-outlined2" autocomplete="off">
                                    <label class="btn btn-outline-primary btn-sm" for="primary-outlined2">Rp</label>
                                </td>
                                <td align="right">
                                    <input style="width:130px" name="bDiskon" type="text"
                                        x-mask:dynamic="$money($input)"
                                        class="form-control text-end selectAll bDiskonKeyup" value="0">
                                </td>

                            </tr>
                            <tr x-show="diskon">
                                <td>Potongan Diskon</td>
                                <td align="right">
                                    <input type="hidden" name="bPotonganDiskon" class="potonganDiskonValue">
                                    <h6 class="potonganDiskon">-</h6>
                                </td>
                            </tr>

                            <tr @click.prevent="bPengiriman = ! bPengiriman">
                                <td colspan="2">
                                    <a href="#"><i class="fas fa-plus"></i> Biaya Pengiriman</a>
                                </td>
                            </tr>
                            <tr x-show="bPengiriman">
                                <td>
                                    <select name="id_akun_pembayaran" id="" class="select22">
                                        <option value="">Pilih Akun</option>
                                        @foreach ($akunPembayaran as $d)
                                            <option value="{{ $d->id_akun }}">{{ strtoupper($d->nm_akun) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td align="right">
                                    <input style="width:130px" name="biaya" type="text"
                                        x-mask:dynamic="$money($input)"
                                        class="form-control text-end selectAll bPengirimanKeyup" value="0">
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Pajak
                                </th>
                                <td align="right">
                                    <input type="hidden" name="pajakSum" class="pajakSumValue">
                                    <h6 class="pajakSum text-end">0</h6>
                                </td>
                            </tr>
                            <tr>
                                <th>Total</th>
                                <th>
                                    <input type="hidden" class="grandTotalValue">
                                    <h6 class="grandTotal text-end">0</h6>
                                </th>
                            </tr>


                            <tr @click.prevent="uangMuka = ! uangMuka">
                                <td colspan="2">
                                    <a href="#"><i class="fas fa-plus"></i> Uang Muka</a>
                                </td>
                            </tr>
                            <tr x-show="uangMuka">
                                <th>
                                    <select name="id_akun" id="" class="select22">
                                        <option value="">Pilih Akun</option>
                                        @foreach ($akunPembayaran as $d)
                                            <option value="{{ $d->id_akun }}">{{ strtoupper($d->nm_akun) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </th>
                                <td align="right">
                                    <input style="width:130px" name="uangMuka" type="text"
                                        x-mask:dynamic="$money($input)"
                                        class="form-control text-end selectAll uangMukaKeyup" value="0">
                                </td>
                            </tr>
                            <tr @click.prevent="bSelisih = ! bSelisih">
                                <td colspan="2">
                                    <a href="#"><i class="fas fa-plus"></i> Selisih Rp</a>
                                </td>
                            </tr>
                            <tr x-show="bSelisih">
                                <th>
                                    <select name="id_akun_selisih" id="" class="select22">
                                        <option value="">Pilih Akun</option>
                                        @foreach ($akunPembayaran as $d)
                                            <option value="{{ $d->id_akun }}">{{ strtoupper($d->nm_akun) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </th>
                                <td align="right">
                                    <input style="width:130px" value="0" name="selisihRp" type="text"
                                        x-mask:dynamic="$money($input)"
                                        class="form-control text-end selectAll selisihKeyup">
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

            function plusRowTbh(count, classPlus, url) {
                $(document).on("click", "." + classPlus, function() {
                    count = count + 1;
                    $.ajax({
                        url: `${url}?count=` + count,
                        type: "GET",
                        success: function(data) {
                            $("#" + classPlus).append(data);
                            $('.select-tbh').select2()
                        },
                    });
                });

                $(document).on('click', '.remove_baris', function() {
                    var delete_row = $(this).attr("count");
                    $(".baris" + delete_row).remove();

                    recalculateTotal()
                })

            }
            plusRowTbh(1, 'tbh_baris', 'tbh_baris')

            function formatInd(isi) {
                return isi.toLocaleString("id-ID", {
                    style: "currency",
                    currency: "IDR",
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 2
                });

            }

            function updateTotalRp(count) {
                const qty = $(`.qtyKeyup${count}`).val().replace(/,/g, '')
                const persen = $(`.persenKeyup${count}`).val().replace(/,/g, '')
                const pajak = $(`.pajakKeyup${count}`).val().replace(/,/g, '')
                const rpSatuan = $(`.rpSatuanKeyup${count}`).val().replace(/,/g, '')
                var ttlRp = persen === 0 ? rpSatuan * qty : (rpSatuan * qty) - (((rpSatuan * qty) * persen) / 100);
                var pajakRp = pajak === 0 ? 0 : (ttlRp * pajak) / 100

                $(`.totalRpKeyup${count}`).val(ttlRp);
                $(`.pajakValue${count}`).val(pajakRp);

                var sum = 0
                $('.ttlRp').each(function() {
                    sum += parseFloat($(this).val(), 10) || 0;
                })
                var sumFormat = formatInd(sum)

                $('.subTotal').text(sumFormat)
                $('.subTotalValue').val(sum)

                console.log(qty + ' ' + rpSatuan + ' ' + ttlRp)
                var pajakRpSum = 0
                $('.pajakValue').each(function() {
                    pajakRpSum += parseFloat($(this).val(), 10) || 0;
                })
                var pajakRpSumFormat = formatInd(pajakRpSum)

                $('.pajakSum').text(pajakRpSumFormat)
                $('.pajakSumValue').val(pajakRpSum)
            }

            function recalculateTotal() {
                var sum = 0;
                var totalPajak = 0;
                // Iterasi melalui setiap elemen dengan kelas ttlRp dan menghitung ulang sum
                $('.ttlRp').each(function() {
                    sum += parseFloat($(this).val(), 10) || 0;
                });
                $('.pajakValue').each(function() {
                    totalPajak += parseFloat($(this).val(), 10) || 0;
                });

                // Menetapkan ulang nilai sum
                var sumFormat = formatInd(sum);
                $('.subTotal').text(sumFormat);
                $('.subTotalValue').val(sum);

                var totalPajakFormat = formatInd(totalPajak);
                $('.pajakSum').text(totalPajakFormat);
                $('.pajakSumValue').val(totalPajak);

                updateBiayaDanUangMuka();
            }

            function updateTotal(displayClass, valueClass, total) {
                const totalFormat = formatInd(total);
                $(`.${displayClass}`).text(totalFormat);
                $(`.${valueClass}`).val(total);
            }
            $(document).on('click', '.btn-check', function() {
                var nil = $(this).val()
                $(".btn-check-value").val(nil);
            })

            function updateBiayaDanUangMuka() {
                const bPengiriman = parseFloat($('.bPengirimanKeyup').val().replace(/,/g, ''));
                const tipeDiskon = $('.btn-check-value').val()
                const bDiskon = parseFloat($('.bDiskonKeyup').val().replace(/,/g, ''));
                const uangMuka = parseFloat($('.uangMukaKeyup').val().replace(/,/g, ''));
                const selisih = parseFloat($('.selisihKeyup').val().replace(/,/g, '')) || 0;
                var subTotal = parseFloat($('.subTotalValue').val());
                var pajakSum = parseFloat($('.pajakSumValue').val());
                var diskonPotongan = tipeDiskon === 'persen' ? parseFloat(((subTotal * bDiskon) / 100)) : bDiskon
                if (tipeDiskon === 'persen') {
                    subTotal = subTotal - diskonPotongan
                } else {
                    subTotal = subTotal - bDiskon
                }

                const grandTotal = subTotal + bPengiriman + pajakSum;
                const sisaTagihan = grandTotal - uangMuka + selisih;

                updateTotal('potonganDiskon', 'potonganDiskonValue', diskonPotongan);
                updateTotal('grandTotal', 'grandTotalValue', grandTotal);
                updateTotal('sisaTagihan', 'sisaTagihanValue', sisaTagihan);
            }

            $(document).on('keyup', '.qtyKeyup, .rpSatuanKeyup, .persenKeyup, .pajakKeyup', function() {
                const count = $(this).attr('count');
                updateTotalRp(count);
                updateBiayaDanUangMuka();
            });

            $(document).on('keyup', '.bPengirimanKeyup, .uangMukaKeyup, .bDiskonKeyup, .selisihKeyup', function(){
                updateBiayaDanUangMuka()
            });
        </script>
    @endsection
</x-theme.app>
