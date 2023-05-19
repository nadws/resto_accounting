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
                                <th width="10%">No Penjualan</th>
                                <th width="15%" style="text-align: right">Total Rp</th>
                                <th width="15%" style="text-align: right">Bayar</th>
                                <th width="5%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($no_order as $no => $p)
                                @php
                                    $getRow = DB::table('invoice_pi')
                                        ->where('no_nota', $p)
                                        ->first();
                                @endphp
                                <tr>
                                    <td><input type="text" readonly class="form-control" name="no_nota[]"
                                            value="{{ $getRow->no_nota }}"></td>
                                    <input type="hidden" readonly class="form-control" name="total_rp[]"
                                        value="{{ $getRow->total_rp }}">
                                    <td><input type="text" readonly class="form-control" style="text-align:right"
                                            value="Rp. {{ number_format($getRow->total_rp, 0) }}"></td>
                                    <td>
                                        <input type="text" class="form-control dikanan rp-nohide text-end"
                                            value="Rp {{ number_format($getRow->total_rp, 2, '.', '.') }}"
                                            count="{{ $no + 1 }}">
                                        <input type="hidden"
                                            class="form-control dikanan rp-hide rp-hide{{ $no + 1 }}"
                                            value="{{ $getRow->total_rp }}" name="bayar[]">

                                    </td>
                                    <td></td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="1"></td>
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
                                    <label for="">Jumlah Setor</label>
                                    <input type="text" class="form-control dikanan rp-nohide text-end" value=""
                                        count="{{ $no + 1 }}">
                                    <input type="hidden"
                                        class="form-control dikanan rp-hide rp-hide{{ $no + 1 }}" value=""
                                        name="setor[]">
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

                </div>
            </section>
    </x-slot>
    <x-slot name="cardFooter">
        <button type="submit" class="float-end btn btn-primary button-save">Simpan</button>
        <button class="float-end btn btn-primary btn_save_loading" type="button" disabled hidden>
            <span class="spinner-border spinner-border-sm " role="status" aria-hidden="true"></span>
            Loading...
        </button>
        <a href="{{ route('jurnal') }}" class="float-end btn btn-outline-primary me-2">Batal</a>
        </form>
    </x-slot>



    @section('scripts')
        <script>
            var count = 3;
            plusRow(count, 'tbh_baris', 'tbh_baris')
            convertRp('rp-nohide', 'rp-hide')
        </script>
    @endsection
</x-theme.app>
