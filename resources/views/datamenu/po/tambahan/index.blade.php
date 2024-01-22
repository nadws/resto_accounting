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
        <div x-data="{ tampil: false }">
            <button class="btn btn-sm btn-primary mb-2" @click="tampil = ! tampil">View Detail <i
                    class="fas fa-eye"></i></button>
            <div class="row" x-show="tampil">
                @include('datamenu.po.components.tbl_item')
            </div>
          
            <div class="row" x-show="tampil">
                @php
                    $total = $poDetail->sub_total - $poDetail->potongan + $poDetail->biaya + $poDetail->ttl_pajak;
                    $sisaTagihan = $total - $bayarSum->ttlBayar;
                @endphp
                @include('datamenu.po.components.tbl_sub', [
                    'sisaTagihan' => $sisaTagihan,
                    'total' => $total,
                ])
            </div>
        </div>
        <form action="{{ route('po.create_biaya_tambahan') }}" method="post" class="save_jurnal">
            @csrf
            <section class="row">
                <table class="table">
                    <thead>
                        <tr>
                            <th width="130" class="dhead">Tgl Transaksi</th>
                            <th width="130" class="dhead">No Nota</th>
                            <th width="130" class="dhead">Nota Manual</th>
                            <th class="dhead">Ekspedisi</th>
                            <th width="150" class="dhead">No Resi</th>
                            <th class="dhead">Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <td>
                            <input type="date" name="tgl" class="form-control" value="{{ date('Y-m-d') }}">
                        </td>
                        <td>
                            <input type="hidden" name="no_nota" readonly value="{{ $no_nota }}"
                                class="form-control">
                            <input type="text" name="" readonly value="PO-{{ $no_nota }}"
                                class="form-control">
                        </td>
                        <td>

                            <input required type="text" name="nota_manual" class="form-control">
                        </td>
                        <td>
                            <div id="load_selectEkspedisi"></div>
                        </td>
                        <td>
                            <input type="text" name="no_resi" placeholder="No resi" class="form-control">
                        </td>
                        <td>
                            <input type="text" name="catatan" placeholder="tambahkan catatan jika ada"
                                class="form-control">
                        </td>
                    </tbody>
                </table>

                <div class="row justify-content-end">
                    <div class="col-4 text-start">
                        <table class="table table-hover" x-data="{
                            bTambahan: true,
                        }">
                            @php
                                $totalDibayar = $poDetail->sub_total - $poDetail->potongan + $poDetail->biaya + $poDetail->ttl_pajak;
                            @endphp
                            <tr>
                                <th><u>Total Sudah Terbayar</u></th>
                                <th>
                                    <input type="hidden" value="{{ $totalDibayar }}" class="sudahDibayarValue">
                                    <h5 class="text-end"><u><em
                                                class="sudahDibayar ">{{ number_format($totalDibayar, 2) }}</em></u></h5>
                                </th>
                            </tr>
                            <tr>
                                <th>Tambah Pembayaran</th>
                                <th>

                                </th>
                            </tr>

                            <tr>
                                <th>
                                    <select name="id_akun" class="select22">
                                        <option value="">Pilih Akun</option>
                                        @foreach ($akunPembayaran as $d)
                                            <option value="{{ $d->id_akun }}">{{ strtoupper($d->nm_akun) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </th>
                                <td align="right">
                                    <input style="width:130px" value="0" name="tbhBayarRp" type="text"
                                        x-mask:dynamic="$money($input)"
                                        class="form-control text-end selectAll tbhBayarKeyup">
                                </td>
                            </tr>

                            <tr>
                                <th><u>Grand Total</u></th>
                                <th>
                                    <input type="hidden" value="{{ $totalDibayar }}" class="grandTotalValue">
                                    <h5 class="text-end"><u><em
                                                class="grandTotal ">{{ number_format($totalDibayar, 2) }}</em></u></h5>
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
        <form id="form_tbh_ekspedisi">
            <x-theme.modal title="Tambah Ekspedisi" idModal="modal_tbh_ekspedisi">
                <div class="form-group">
                    <label for="">Nama Ekspedisi</label>
                    <input type="text" class="form-control nm_ekspedisi">
                </div>
            </x-theme.modal>
        </form>



    </x-slot>
    @section('scripts')
        <script>
            $('.select22').select2();
            loadSelectEkspedisi()

            function loadSelectEkspedisi() {
                $.ajax({
                    type: "GET",
                    url: "{{ route('po.load_selectEkspedisi') }}",
                    success: function(r) {
                        $('#load_selectEkspedisi').html(r);
                        $('.select23').select2()

                    }
                });
            }
            $(document).on('change', '.selectEkspedisi', function() {
                const nilai = $(this).val()
                if (nilai == 'tambah') {
                    $('#modal_tbh_ekspedisi').modal('show')
                    $('#form_tbh_ekspedisi').submit(function(e) {
                        e.preventDefault();
                        const nm_ekspedisi = $('.nm_ekspedisi').val()
                        $.ajax({
                            type: "GET",
                            url: "{{ route('ekspedisi.create') }}",
                            data: {
                                nm_ekspedisi: nm_ekspedisi
                            },
                            success: function(r) {
                                loadSelectEkspedisi()
                                $('#modal_tbh_ekspedisi').modal('hide')
                            }
                        });
                    });
                }
            })
            $(document).on('keyup', '.tbhBayarKeyup', function() {
                const tambahanBiaya = parseFloat($(this).val().replace(/,/g, ''));
                var grandTotal = parseFloat($('.sudahDibayarValue').val())  
                grandTotal = tambahanBiaya + grandTotal

                var grandTotalFormat = formatInd(grandTotal);
                $('.grandTotal').text(grandTotalFormat);
                $('.grandTotalValue').val(grandTotal);

            })

            function formatInd(isi) {
                return isi.toLocaleString("id-ID", {
                    style: "currency",
                    currency: "IDR",
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 2
                });

            }
        </script>
    @endsection
</x-theme.app>
