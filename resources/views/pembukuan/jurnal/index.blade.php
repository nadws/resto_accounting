<x-theme.app cont="container-fluid" title="{{ $title }}" table="Y" sizeCard="12">
    <x-slot name="cardHeader">
        <div class="row justify-content-end">
            <div class="col-lg-6">
                <h6 class="float-start mt-1">{{ $title }} : {{ tanggal($tgl1) }}
                    ~ {{ tanggal($tgl2) }}</h6>
            </div>

            <div class="col-lg-6">
                <x-theme.button modal="T"
                href="{{ $id_buku != '13' ? route('jurnal.add', ['id_buku' => $id_buku]) : route('add_balik_aktiva', ['id_buku' => $id_buku]) }}"
                icon="fa-plus" addClass="float-end" teks="Buat Baru" />
            </div>
            <div class="col-lg-12">
                <hr style="border: 2px solid #435EBE">
            </div>
            @include('pembukuan.jurnal.nav')
        </div>
    </x-slot>
    <x-slot name="cardBody">
        <section class="row">
            <table class="table table-hover" id="table1">
                
            </table>
        </section>


    </x-slot>

</x-theme.app>
