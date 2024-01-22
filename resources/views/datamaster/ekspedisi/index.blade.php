<x-theme.app title="{{ $title }}" table="Y" sizeCard="6">
    <x-slot name="cardHeader">
        <h5 class="float-start mt-1">{{ $title }}</h5>
        <x-theme.button modal="Y" idModal="modal_tbh_ekspedisi" icon="fa-plus" addClass="float-end" teks="Tambah" />
    </x-slot>

    <x-slot name="cardBody">
        <section class="row">
            <table class="table" id="table1">
                <thead>
                    <tr>
                        <th width="5">#</th>
                        <th>Nama Ekspedisi</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ekspedisi as $no => $d)
                        <tr>
                            <td>{{ $no + 1 }}</td>
                            <td>{{ ucwords($d->nm_ekspedisi) }}</td>
                            <td align="center">
                                <a class="btn btn-sm btn-primary edit" nama="{{$d->nm_ekspedisi}}" id="{{$d->id_ekspedisi}}"><i class="fas fa-pen"></i></a>
                                <a href="{{ route('ekspedisi.delete', $d->id_ekspedisi) }}" class="btn btn-sm btn-danger" ><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>

        {{-- ALL MODAL --}}

        <form action="{{ route('ekspedisi.create') }}">
            <x-theme.modal title="Tambah Ekspedisi" idModal="modal_tbh_ekspedisi">
                <div class="form-group">
                    <label for="">Nama Ekspedisi</label>
                    <input type="text" class="form-control" name="nm_ekspedisi">
                    <input type="hidden" name="route" value="1">
                </div>
            </x-theme.modal>
        </form>

        <form action="{{ route('ekspedisi.update') }}" method="post">
            @csrf
            <x-theme.modal title="Edit Ekspedisi" idModal="modal_edit">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="">Nama Ekspedisi</label>
                            <input type="text" name="nm_ekspedisi" class="form-control nm_ekspedisi">
                            <input type="hidden" name="id_ekspedisi" class="form-control id">
                        </div>
                    </div>
                </div>
            </x-theme.modal>
        </form>

    </x-slot>

    @section('scripts')
        <script>
            $(document).on('click', '.edit', function(e){
                e.preventDefault();
                $('#modal_edit').modal('show')
                const nm_ekspedisi = $(this).attr('nama')
                const id = $(this).attr('id')

                $(".nm_ekspedisi").val(nm_ekspedisi);
                $(".id").val(id);
            })
        </script>
    @endsection
</x-theme.app>
