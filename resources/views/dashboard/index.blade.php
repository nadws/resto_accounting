<x-theme.app title="{{ $title }}" table="T">
    <x-slot name="slot">

        <div class="row">
            <div class="col-lg-8 mb-2">
                <h6>Dashboard</h6>
            </div>
            <div class="col-lg-6">
                <div id="load_akun"></div>
            </div>
            <div class="col-lg-6">
                <div id="load_neraca"></div>
            </div>
        </div>
    </x-slot>

    @section('scripts')
        <script>
            load_neraca();
            load_akun();

            function load_neraca() {
                $.ajax({
                    type: "get",
                    url: "{{ route('neraca.index') }}",
                    success: function(response) {
                        $("#load_neraca").html(response);
                    }
                });
            }

            function load_akun() {
                $.ajax({
                    type: "get",
                    url: "{{ route('akun.index') }}",
                    success: function(response) {
                        $("#load_akun").html(response);
                        $('#table_akun').DataTable({
                            "paging": true,
                            "pageLength": 10,
                            "lengthChange": true,
                            "stateSave": true,
                            "searching": true,
                        });
                    }
                });
            }
        </script>
    @endsection
</x-theme.app>
