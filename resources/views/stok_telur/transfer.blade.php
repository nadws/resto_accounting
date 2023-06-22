<x-theme.app title="{{$title}}" table="Y" sizeCard="11">

    <x-slot name="cardHeader">
        <div class="row justify-content-end">

            <div class="col-lg-6">
                <h6 class="float-start mt-1">{{ $title }}</h6>
            </div>
            <div class="col-lg-6">

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
        <form action="{{route('dashboard_kandang.save_transfer')}}" method="post" class="save_jurnal">
            @csrf
            <section class="row">
                <div class="col-lg-12">
                    <table class="table">
                        <tr>
                            <th class="dhead">Tanggal</th>
                            <th class="dhead">Dari Gudang</th>
                            <th class="dhead">Ke Gudang</th>
                        </tr>
                        <tr>
                            <td>
                        <input type="date" class="form-control" name="tgl" value="{{date('Y-m-d')}}">
                            </td>
                            <td>
                                <input type="text" class="form-control" value="Gudang {{$gudang->nm_gudang}}" readonly>
                        <input type="hidden" class="id_gudang_dari" name="id_gudang_dari"
                            value="{{$gudang->id_gudang_telur}}" readonly>
                            </td>
                            <td>
                                <select required name="id_gudang" id="" class="select">
                                    <option value="">--Pilih Gudang--</option>
                                    @foreach ($gudang_telur as $g)
                                    <option {{$g->id_gudang_telur == 2 ? 'selected' : ''}} value="{{$g->id_gudang_telur}}">Gudang {{$g->nm_gudang}}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>
                
                <div class="col-lg-12">
                    <hr style="border: 1px solid black">
                </div>
                <div class="col-lg-12">
                    <div id="load_menu"></div>
                </div>
                <div class="col-lg-6">
                    <div class="row">
                        {{-- <x-theme.toggle name="Pilihan Lainnya">

                        </x-theme.toggle>
                        <div class="col-lg-12"></div>
                        <div class="col-lg-6 pilihan_l">
                            <label for="">No Dokumen</label>
                            <input type="text" class="form-control inp-lain" name="no_dokumen">
                        </div>
                        <div class="col-lg-6 pilihan_l">
                            <label for="">Tanggal Dokumen</label>
                            <input type="date" class="form-control inp-lain" name="tgl_dokumen">
                        </div> --}}

                    </div>
                </div>
                {{-- <div class="col-lg-6">
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

                </div> --}}
            </section>

    </x-slot>
    <x-slot name="cardFooter">
        <button type="submit" class="float-end btn btn-primary button-save">Simpan</button>
        <button class="float-end btn btn-primary btn_save_loading" type="button" disabled hidden>
            <span class="spinner-border spinner-border-sm " role="status" aria-hidden="true"></span>
            Loading...
        </button>
        <a href="{{route('stok_telur')}}" class="float-end btn btn-outline-primary me-2">Batal</a>
        </form>
    </x-slot>



    @section('scripts')
    <script>
        load_menu();
        function load_menu() {
            $.ajax({
                method: "GET",
                url: "/load_transfer_telur",
                dataType: "html",
                success: function (hasil) {
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
        var count = 2;
        $(document).on("click", ".tbh_baris", function () {
            count = count + 1;
            $.ajax({
                url: "/tbh_baris_transfer?count=" + count,
                type: "Get",
                success: function (data) {
                    $("#tb_baris").append(data);
                    $(".select").select2();
                },
            });
        });
        var id_gudang = $('.id_gudang_dari').val();
        
        $(document).on("change", ".pilih_telur", function () {
            var count = $(this).attr('count');
            var id_telur = $('.pilih_telur' + count).val();
            
           
            $.ajax({
                url: "/get_stok_telur?id_telur="+ id_telur+'&id_gudang_telur=' + id_gudang,
                type: "Get",
                dataType: "json",
                success: function (data) {
                    $(".pcs_telur" + count).text(data['pcs']);
                    $(".kg_telur" + count).text(data['kg']);
                    $(".select").select2();
                },
            });
        });
        $(document).on("click", ".remove_baris", function () {
        var delete_row = $(this).attr("count");
        $(".baris" + delete_row).remove();
        });
        aksiBtn("form");
        $(document).on("keyup", ".pcs", function () {
            var count = $(this).attr('count');
            var pcs = $('.pcs'+ count).val()
            var ikat = parseFloat(pcs) / 180;
            $('.ikat'+ count).text(ikat.toFixed(1));
        });
    </script>

    @endsection
</x-theme.app>