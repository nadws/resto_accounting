<x-theme.app title="{{ $title }}" table="Y" sizeCard="12">

    <x-slot name="cardHeader">
        <div class="col-lg-6">
            <h6 class="float-start mt-1">{{ $title }} {{ $edit->no_nota }}</h6>
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
        <form action="{{ route('jual.edit_save_penjualan') }}" method="post" class="save_jurnal">
            @csrf
            <section class="row">
                <div class="col-lg-12">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th width="5%" class="dhead">Tanggal</th>
                                <th width="10%" class="dhead">No Penjualan</th>
                                <th width="15%" class="dhead">Keterangan</th>
                                <th width="15%" class="dhead" style="text-align: right">Total Rp</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <input type="date" value="{{ $edit->tgl }}" class="form-control"
                                        name="tgl">
                                </td>
                                <td>
                                    <input value="{{ $edit->id_invoice_bk }}" type="hidden" class="form-control" name="id_invoice_bk">
                                    <input value="{{ $edit->no_penjualan }}" type="text" class="form-control" name="no_penjualan">
                                </td>
                                <td>
                                    <input value="{{ $edit->ket }}" type="text" class="form-control" name="ket">
                                </td>
                                <td>
                                    <input type="text" class="form-control dikanan setor-nohide text-end"
                                        value="Rp. {{ $edit->total_rp }}" count="1">
                                    <input value="{{ $edit->total_rp }}" type="hidden" class="form-control dikanan setor-hide setor-hide1"
                                        value="" name="total_rp">
                                </td>
                            </tr>
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
