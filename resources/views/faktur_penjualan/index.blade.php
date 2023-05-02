<x-theme.app title="{{ $title }}" table="Y" sizeCard="12">
    <x-slot name="cardHeader">
        <div class="row justify-content-end">
            <div class="col-lg-6">

                <x-theme.button modal="Y" idModal="view" icon="fa-filter" addClass="float-end" teks="" />
            </div>
        </div>
    </x-slot>
    <x-slot name="cardBody">
        <section class="row">

        </section>







    </x-slot>
    @section('scripts')
    <script>

    </script>
    @endsection
</x-theme.app>