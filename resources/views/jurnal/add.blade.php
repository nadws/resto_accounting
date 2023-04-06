<form action="{{route('save_jurnal')}}" method="post" class="save_jurnal">

    <x-theme.app title="{{$title}}" table="Y" sizeCard="12">
        <x-slot name="cardHeader">
            <div class="row justify-content-end">
                <div class="col-lg-2">

                </div>
            </div>

        </x-slot>


        <x-slot name="cardBody">
            <section class="row">
                <div class="col-lg-3">
                    <label for="">Tanggal</label>
                    <input type="date" class="form-control" name="tgl" value="{{date('Y-m-d')}}">
                </div>
                <div class="col-lg-3">
                    <label for="">No Nota</label>
                    <input type="text" class="form-control" name="no_nota" value="KS-{{$max}}">
                </div>
                <div class="col-lg-12">
                    <hr style="border: 1px solid black">
                </div>
                <div class="col-lg-12">
                    <div id="load_menu"></div>
                </div>
                <div class="col-lg-6">
                    <div class="row">
                        <x-theme.toggle name="Pilihan Lainnya">

                        </x-theme.toggle>
                        <div class="col-lg-12"></div>
                        <div class="col-lg-6 pilihan_l">
                            <label for="">No Dokumen</label>
                            <input type="text" class="form-control inp-lain" name="no_dokumen">
                        </div>
                        <div class="col-lg-6 pilihan_l">
                            <label for="">Tanggal Dokumen</label>
                            <input type="date" class="form-control inp-lain" name="tgl_dokumen">
                        </div>

                    </div>
                </div>
                <div class="col-lg-6">
                    <hr style="border: 1px solid blue">
                    <table class="" width="100%">
                        <tr>
                            <td width="20%">Total</td>
                            <td width="40%" class="total" style="text-align: right;">Rp.0</td>
                            <td width="40%" class="total_kredit" style="text-align: right;">Rp.0</td>
                        </tr>
                        <tr>
                            <td class="cselisih" colspan="2">Selisih</td>
                            <td style="text-align: right;" class="selisih cselisih">Rp.0</td>
                        </tr>
                    </table>

                </div>
            </section>
        </x-slot>
        <x-slot name="cardFooter">
            <button type="submit" class="float-end btn btn-primary button-save" hidden>Simpan</button>
            <button class="float-end btn btn-primary btn_save_loading" type="button" disabled hidden>
                <span class="spinner-border spinner-border-sm " role="status" aria-hidden="true"></span>
                Loading...
            </button>
            <a href="{{route('jurnal')}}" class="float-end btn btn-outline-primary me-2">Batal</a>
        </x-slot>


        @section('scripts')
        <script src="/js/jurnal.js"></script>
        @endsection
    </x-theme.app>
</form>