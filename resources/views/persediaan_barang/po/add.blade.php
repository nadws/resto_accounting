<x-theme.app title="{{ $title }}" table="Y" sizeCard="12">

    <x-slot name="cardHeader">
        <div class="row justify-content-end">
            <div class="col-lg-2">

            </div>
        </div>

    </x-slot>


    <x-slot name="cardBody">
        <form action="#" method="post" id="save_po">
            @csrf
            <div id="load-view-add"></div>

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
            load_menu()

            function load_menu() {
                $.ajax({
                    type: "GET",
                    url: "{{ route('po.load_view_add') }}",
                    success: function(r) {
                        $("#load-view-add").html(r);
                        $(".select2").select2({})
                    }
                });
            }
            convertRp('rp-nohide', 'rp-hide')

            var count = 3;
            plusRow(count, 'tbh_baris', 'tbh_baris')
            aksiBtn('#save_po')
        </script>
    @endsection
</x-theme.app>
