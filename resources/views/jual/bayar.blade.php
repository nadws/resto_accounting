<x-theme.app title="{{ $title }}" table="Y" sizeCard="12">

    <x-slot name="cardHeader">
        <div class="row justify-content-end">
            <div class="col-lg-2">

            </div>
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
        </style>
        <form action="{{ route('jual.create') }}" method="post" class="save_jurnal">
            @csrf

            <section class="row">

                <div class="col-lg-3 col-6">
                    <label for="">Tanggal</label>
                    <input type="date" name="tgl_bayar" class="form-control" value="{{ date('Y-m-d') }}" readonly>
                </div>
                <div class="col-lg-3 col-6">
                    <label for="">No Pembayaran</label>
                    <input type="text" class="form-control" value="PBYR-{{ $no_pembayaran }}" readonly>
                    <input type="hidden" class="form-control" name="no_pembayaran" value="{{ $no_pembayaran }}"
                        readonly>
                </div>

                <div class="col-lg-12">
                    <hr style="border: 1px solid black">
                </div>
                <div class="col-lg-12">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th width="5%">No Penjualan</th>
                                <th width="15%" style="text-align: right">Total Rp</th>
                                <th width="15%" style="text-align: right">Bayar</th>
                                <th width="5%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $ttlKredit = 0;
                            @endphp
                            @foreach ($no_order as $no => $p)
                                @php
                                    $getRow = DB::selectOne("SELECT a.* FROM invoice_pi as a
                                        WHERE a.no_nota = '$p'
                                        ORDER BY a.id_invoice_bk ASC");
                                    
                                    $getRowKredit = DB::selectOne("SELECT a.no_nota, a.no_penjualan, a.status,(a.total_rp - c.kredit) as total_rp,a.tgl, c.kredit, c.debit FROM `invoice_pi` as a
                                    LEFT JOIN (
                                        SELECT b.no_nota,b.nota_jurnal, SUM(debit) as debit, SUM(kredit) as kredit FROM bayar_pi as b
                                        GROUP BY b.nota_jurnal
                                    ) c ON c.nota_jurnal = a.no_nota
                                    WHERE a.no_nota = '$p' ORDER BY a.id_invoice_bk ASC;");
                                    $kredit = $getRow->total_rp - $getRowKredit->kredit;
                                    $ttlKredit += $kredit;
                                    
                                @endphp
                                <tr>
                                    <input type="hidden" readonly class="form-control" name="total_rp[]"
                                        value="{{ $kredit }}">
                                    <td>
                                        <input type="hidden" class="form-control" name="no_nota[]"
                                            value="{{ $getRow->no_nota }}">
                                        <input type="text" readonly class="form-control" name="no_penjualan[]"
                                            value="{{ $getRow->no_penjualan }}">
                                    </td>

                                    <td>
                                        <input type="text" readonly class="form-control" style="text-align:right"
                                            value="Rp. {{ number_format($kredit, 0) }}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control dikanan rp-nohide text-end"
                                            value="Rp {{ number_format($kredit, 2, '.', '.') }}"
                                            count="{{ $no + 1 }}">
                                        <input type="hidden"
                                            class="form-control dikanan rp-hide rp-hide{{ $no + 1 }}"
                                            value="{{ $kredit }}" name="bayar[]">

                                    </td>
                                    <td></td>
                                </tr>
                            @endforeach
                            <tr>
                                <td>
                                    <label for="">Pilih Akun</label>
                                    <select name="id_akun[]" class="form-control select2" id="select2">
                                        <option value="">- Pilih Akun -</option>
                                        @foreach ($akun as $d)
                                            <option value="{{ $d->id_akun }}">{{ ucwords($d->nm_akun) }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <label for="">Debit</label>
                                    <input type="text" class="form-control dikanan setor-nohide text-end"
                                        value="Rp. 0" count="{{ $no + 1 }}">
                                    <input type="hidden"
                                        class="form-control dikanan setor-hide setor-hide{{ $no + 1 }}"
                                        value="" name="debit[]">
                                </td>
                                <td>
                                    <label for="">Kredit</label>
                                    <input type="text" class="form-control dikanan kredit-nohide text-end"
                                        value="Rp 0" count="{{ $no + 1 }}">
                                    <input type="hidden"
                                        class="form-control dikanan kredit-hide kredit-hide{{ $no + 1 }}"
                                        value="" name="kredit[]">
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
                                        <i class="fas fa-plus"></i> Tambah Pembayaran Baru
                                    </button>
                                </th>
                            </tr>
                        </tfoot>

                    </table>
                    <div class="col-lg-6 float-end">

                        <hr style="border: 1px solid blue">
                        <table class="" width="100%">

                            <tr>

                                <td width="20%">Total</td>
                                <input type="hidden" class="total_hutangTetap" value="{{ $ttlKredit }}">
                                <td width="40%" class="total" style="text-align: right;">
                                    Rp.{{ number_format($ttlKredit, 2) }}
                                </td>
                                <td width="40%" class="total_kredit" style="text-align: right;">
                                    Rp. {{ number_format($ttlKredit, 2) }}
                                </td>

                            </tr>
                            <tr>
                                <td class="cselisih" colspan="2">Sisa Hutang</td>
                                <td style="text-align: right;" class="selisih cselisih">Rp.
                                    0</td>
                            </tr>
                        </table>

                    </div>
                </div>
            </section>
    </x-slot>
    <x-slot name="cardFooter">
        <button type="submit" class="float-end btn btn-primary button-save">Simpan</button>
        <button class="float-end btn btn-primary btn_save_loading" type="button" disabled hidden>
            <span class="spinner-border spinner-border-sm " role="status" aria-hidden="true"></span>
            Loading...
        </button>
        <a href="{{ route('jual.index') }}" class="float-end btn btn-outline-primary me-2">Batal</a>
        </form>
    </x-slot>



    @section('scripts')
        <script>
            var count = 3;
            plusRow(count, 'tbh_baris', 'tbh_baris')
            convertRpSelisih('rp-nohide', 'rp-hide')
            convertRpSelisih('setor-nohide', 'setor-hide')
            convertRpSelisih('kredit-nohide', 'kredit-hide')
        </script>
    @endsection
</x-theme.app>
