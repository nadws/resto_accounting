<x-theme.app title="{{ $title }}" table="Y" sizeCard="12">

    <x-slot name="cardHeader">
        <div class="col-lg-6">
            <h3 class="float-start mt-1">{{ $title }}</h3>
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
        <form action="{{ route('jual.piutang') }}" method="post" class="save_jurnal">
            @csrf
            <section class="row">
                <div class="col-lg-12">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th width="5%">Tanggal</th>
                                <th width="10%">No Penjualan</th>
                                <th width="15%">Keterangan</th>
                                <th width="15%" style="text-align: right">Total Rp</th>
                                <th width="5%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <input type="date" value="{{ date('Y-m-d') }}" class="form-control"
                                        name="tgl[]">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="no_penjualan[]">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="ket[]">
                                </td>
                                <td>
                                    <input type="text" class="form-control dikanan setor-nohide text-end"
                                        value="Rp. 0" count="1">
                                    <input type="hidden" class="form-control dikanan setor-hide setor-hide1"
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
        <a href="{{ route('jual.index') }}" class="float-end btn btn-outline-primary me-2">Batal</a>
        </form>
    </x-slot>



    @section('scripts')
        <script>
            var count = 3;
            plusRow(count, 'tbh_baris', 'tbh_add')
            convertRp('setor-nohide', 'setor-hide')
        </script>
    @endsection
</x-theme.app>
