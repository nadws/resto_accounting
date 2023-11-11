<x-theme.app title="No Table" table="T">
    <x-slot name="slot">

        <div class="row">
            <div class="col-lg-12 mb-2">
                <h6>Dashboard</h6>
            </div>
            <div class="col-lg-8">
                <div id="load_neraca"></div>
            </div>
        </div>
    </x-slot>

    @section('scripts')
        <script>
            load_neraca();

            function load_neraca() {
                $.ajax({
                    type: "get",
                    url: "{{ route('neraca.index') }}",
                    success: function(response) {
                        $("#load_neraca").html(response);
                    }
                });
            }
        </script>
    @endsection
</x-theme.app>
