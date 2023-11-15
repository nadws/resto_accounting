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
            <input type="hidden" name="id_buku" value="{{ $id_buku }}">
            <section class="row">
                <div class="col-lg-3">
                    <label for="">Tanggal</label>
                    <input type="date" class="form-control" name="tgl" value="{{ date('Y-m-d') }}">
                </div>
                <div class="col-lg-3">
                    <label for="">No Urut Jurnal Umum</label>
                    <input type="text" class="form-control" name="no_nota" value="JU-{{ $max }}" readonly>
                </div>
                @if ($id_buku == '12')
                    <div class="col-lg-3">
                        <label for="">Proyek</label>
                        <select name="id_proyek" id="select2" class="proyek proyek_berjalan">

                        </select>
                    </div>
                @endif

                <div class="col-lg-3">
                    <label for="">Suplier</label>
                    <select name="id_suplier" class="select2suplier form-control">
                        <option value="">- Pilih Suplier -</option>
                        @foreach ($suplier as $p)
                            <option value="{{ $p->id_suplier }}">{{ $p->nm_suplier }}</option>
                        @endforeach
                    </select>
                </div>


                <div class="col-lg-12">
                    <hr style="border: 1px solid black">
                </div>
                <div class="col-lg-12">
                    <div id="load_menu"></div>
                </div>
                <div class="col-lg-6">
                    <div class="row">

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
        <a href="{{ route('jurnal.index') }}" class="float-end btn btn-outline-primary me-2">Batal</a>
        </form>


        <x-theme.modal title="Tambah Proyek" idModal="tambah">
            <div class="row">
                <div class="col-lg-3">
                    <div class="form-group">
                        <label for="">Kode Proyek</label>
                        <input type="text" class="form-control" name="kd_proyek">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="">Tanggal Proyek</label>
                        <input type="date" class="form-control " name="tgl">
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="form-group">
                        <label for="">Nama Proyek</label>
                        <input type="text" class="form-control" name="nm_proyek">
                    </div>
                </div>

            </div>
        </x-theme.modal>

    </x-slot>
    @section('scripts')
        <script>
            load_menu()
            function load_menu() {
                var urlParams = new URLSearchParams(window.location.search);
                var id_akun = urlParams.get('id_akun');


                if (id_akun) {
                    $.ajax({
                        method: "GET",
                        url: "{{route('jurnal.load_menu')}}",
                        dataType: "html",
                        data: {
                            id_akun: id_akun
                        },
                        success: function(hasil) {
                            $("#load_menu").html(hasil);
                            $('.select').select2({
                                language: {
                                    searching: function() {
                                        $('.select2-search__field').focus();
                                    }
                                }
                            });

                        },
                    });
                } else {
                    var defaultIdAkun = 'default_value';
                    $.ajax({
                        method: "GET",
                        url: "{{route('jurnal.load_menu')}}",
                        dataType: "html",
                        data: {
                            id_akun: defaultIdAkun
                        },
                        success: function(hasil) {
                            $("#load_menu").html(hasil);
                            $('.select').select2({
                                language: {
                                    searching: function() {
                                        $('.select2-search__field').focus();
                                    }
                                }
                            });

                        },
                    });
                }



            }
        </script>
    @endsection
</x-theme.app>
