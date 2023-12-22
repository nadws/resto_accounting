<x-theme.app title="{{ $title }}" table="Y" sizeCard="10">
    <x-slot name="cardHeader">
        <div class="row">
            <div class="col-lg-12">
                @include('persediaan.bahan_makanan.nav')

            </div>
            <div class="col-lg-12">
                <hr>
            </div>
        </div>
        <h6 class="float-start">{{ $title }}</h6>
        <div class="row justify-content-end">
            <div class="col-lg-6">
                <x-theme.button href="{{route('bahan.stok_add')}}" icon="fa-plus" addClass="float-end"
                    teks="Buat Baru" />
            </div>

        </div>

    </x-slot>

    <x-slot name="cardBody">

        <section class="row">
            <table class="table" id="table1">
                <thead>
                    <tr>
                        <th width="5">#</th>
                        <th>Tanggal</th>
                        <th>No Nota</th>
                        <th class="text-center">Qty</th>
                        <th>Status</th>
                    </tr>
                </thead>

               
            </table>


    </x-slot>
    @section('scripts')
        <script>
            
        </script>
    @endsection

</x-theme.app>
