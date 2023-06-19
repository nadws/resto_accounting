<x-theme.app title="{{$title}}" table="Y" sizeCard="10">

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
        <form action="{{route('stok_telur_mtd.store')}}" method="post" class="save_jurnal">
            @csrf
            <section class="row">

                <div class="col-lg-3">
                    <label for="">Tanggal</label>
                    <input type="date" class="form-control" name="tgl" value="{{date('Y-m-d')}}">
                </div>
                <div class="col-lg-3">
                    <label for="">Keterangan</label>
                    <input type="text" class="form-control" name="ket">
                    <input type="hidden" class="form-control" name="id_gudang" value="{{$id_gudang}}">
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
            </section>

    </x-slot>
    <x-slot name="cardFooter">
        <button type="submit" class="float-end btn btn-primary button-save">Simpan</button>
        <button class="float-end btn btn-primary btn_save_loading" type="button" disabled hidden>
            <span class="spinner-border spinner-border-sm " role="status" aria-hidden="true"></span>
            Loading...
        </button>
        <a href="{{route('stok_telur_mtd.index')}}" class="float-end btn btn-outline-primary me-2">Batal</a>
        </form>
    </x-slot>



    @section('scripts')
    <script>
        load_menu();
        function load_menu() {
            $.ajax({
                method: "GET",
                url: "/load_menu_telur",
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
                url: "/tbh_baris_telur?count=" + count,
                type: "Get",
                success: function (data) {
                    $("#tb_baris").append(data);
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