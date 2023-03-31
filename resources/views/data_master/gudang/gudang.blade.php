<x-theme.app title="{{ $title }}" table="Y" sizeCard="8">
    <x-slot name="cardHeader">
        <a class="btn btn-primary float-end" href="#" data-bs-toggle="modal" data-bs-target="#tambah-gudang"><i
                class="fas fa-plus"></i> Tambah</a>

    </x-slot>
    <x-slot name="cardBody">

        <section class="row">
            <table class="table" width="100%" id="table1">
                <thead>
                    <tr>
                        <th width="5">#</th>
                        <th width="15">Kode</th>
                        <th width="150">Nama</th>
                        <th width="9">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($gudang as $no => $d)
                        <tr>
                            <td>{{ $no + 1 }}</td>
                            <td>{{ ucwords($d->kd_gudang) }}</td>
                            <td>{{ ucwords($d->nm_gudang) }}</td>
                            <td>
                                <div class="btn-group dropstart mb-1">
                                    <span class="btn btn-lg" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v text-primary"></i>
                                    </span>
                                    <div class="dropdown-menu">
                                        <a data-bs-toggle="modal" data-bs-target="#edit-gudang"
                                            class="dropdown-item text-primary edit" id_gudang="{{ $d->id_gudang }}"
                                            href="#"><i class="me-2 fas fa-pen"></i>
                                            Edit</a>
                                        <a class="dropdown-item text-danger" id_gudang="{{ $d->id_gudang }}"
                                            onclick="return confirm('Yakin dihapus ?')" href="{{ route('gudang.delete', $d->id_gudang) }}"><i class="me-2 fas fa-trash"></i> Delete</a>
                                    </div>
                                </div>

                            </td>
                        </tr>
                    @endforeach


                </tbody>
            </table>
        </section>

        <form action="{{ route('gudang.create') }}" method="post">
            @csrf
            <x-theme.modal size="modal-lg" title="Tambah Baru" idModal="tambah-gudang">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="">Kode Gudang</label>
                            <input required type="text" name="kd_gudang" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="form-group">
                            <label for="">Nama Gudang</label>
                            <input required type="text" name="nm_gudang" class="form-control">
                        </div>
                    </div>
                </div>
            </x-theme.modal>
        </form>

        <form action="{{ route('gudang.edit') }}" method="post">
            @csrf
            <x-theme.modal size="modal-lg" title="Tambah Baru" idModal="edit-gudang">
                <div id="load-edit"></div>
            </x-theme.modal>
        </form>


    </x-slot>

    @section('scripts')
        <script>
            $(document).ready(function() {
                edit('edit', 'id_gudang', 'gudang/edit', 'load-edit')
            });
        </script>
    @endsection
</x-theme.app>
