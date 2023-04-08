<x-theme.app title="{{ $title }}" table="Y" sizeCard="12">

    <x-slot name="cardHeader">

    </x-slot>


    <x-slot name="cardBody">
        <form action="{{ route('stok_masuk.store') }}" method="post" id="save_stok_masuk">
            @csrf            
            <div id="load_menu"></div>
    </x-slot>
    <x-slot name="cardFooter">
        <button type="submit" class="float-end btn btn-primary button-save">Simpan</button>
        <button class="float-end btn btn-primary btn_save_loading" type="button" disabled hidden>
            <span class="spinner-border spinner-border-sm " role="status" aria-hidden="true"></span>
            Loading...
        </button>
        <a href="{{ route('jurnal') }}" class="float-end btn btn-outline-primary me-2">Batal</a>
        </form>
        <form id="form_add_produk">
            @csrf
            <x-theme.modal size="modal-lg" title="Tambah Baru" idModal="tambah_add">
                <div class="row float-end">
                    <div class="col-lg-12">
                        <label for="">Pencarian : </label>
                        <input type="text" id="pencarian" class="form-control">
                    </div>
                </div>
                <table class="table" id="tableProduk">
                    <thead>
                        <tr>
                            <th width="8%"><input id="checkAll" type="checkbox" class="form-check"></th>
                            <th width="8%">#</th>
                            <th>Nama</th>
                            <th>Satuan</th>
                            <th>Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                            
                        @php
                            $id_produk = [];
                            $produks = \App\Models\Stok::with('produk.satuan')->where('no_nota', request()->get('no_nota'))->get();
                            foreach($produks as $p) {
                                $id_produk[] = $p->id_produk;
                            }
                        @endphp
                        @foreach ($allProduk as $no => $p)
                            <input type="hidden" name="no_nota_add" value="{{ request()->get('no_nota') }}">
                            <tr>
                                <td><input name="id_produk[]" {{in_array($p->id_produk,$id_produk) ? 'checked' : ''}} value="{{ $p->id_produk }}" id="for{{ $no + 1 }}"
                                        type="checkbox" class="checkbox checkItem"></td>
                                <td>{{ $no + 1 }}</td>
                                <td><label style="font-size: 16px;" class="form-check-label"
                                        for="for{{ $no + 1 }}">{{ ucwords($p->nm_produk) }}</label></td>
                                <td>{{ $p->satuan->nm_satuan }}</td>
                                <td>1</td>
                            </tr>
                        @endforeach
                        {{-- @if ($addProduk->count() < 1)
                            
                        @else
                        <tr>
                            <td colspan="5" align="center"><span><em>Data produk kosong</em></span></td>
                        </tr>
                        @endif --}}
                    </tbody>
                </table>
            </x-theme.modal>
        </form>
    </x-slot>


    @section('scripts')
        <script>
            load_menu()
            function load_menu() {
                $.ajax({
                    type: "GET",
                    url: "{{ route('stok_masuk.load_menu') }}?no_nota={{ request()->get('no_nota') }}",
                    success: function(r) {
                        $("#load_menu").html(r);
                    }
                });
            }

            
            inputChecked('checkAll', 'checkItem')
            pencarian('pencarian', 'tableProduk')
            aksiBtn('#save_stok_masuk')

            $(document).on('submit', '#form_add_produk', function(e) {
                e.preventDefault();
                var datas = $("#form_add_produk").serialize()
                $.ajax({
                    type: "GET",
                    url: "{{ route('stok_masuk.create_add') }}",
                    data: datas,
                    success: function(response) {
                        load_menu()
                        $("#tambah_add").modal('hide')
                    }
                });
            })
        </script>
    @endsection
</x-theme.app>
