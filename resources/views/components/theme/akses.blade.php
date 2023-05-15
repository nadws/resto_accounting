{{-- modal setting --}}
@if (auth()->user()->posisi_id == 1)
    <x-theme.button modal="Y" idModal="akses" icon="fas fa-cog" addClass="float-end" teks="" />
@endif
{{-- end modal setting --}}