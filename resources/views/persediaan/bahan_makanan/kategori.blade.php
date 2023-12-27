<x-theme.app title="{{ $title }}" table="Y" sizeCard="6">
    <x-slot name="cardHeader">
        <h6 class="float-start mt-1">{{ $title }}</h6>
        <x-theme.button modal="Y" idModal="tambah" icon="fa-plus" addClass="float-end" teks="Tambah" />
    </x-slot>

    <x-slot name="cardBody">
        <section class="row">
            <table class="table" id="table1">
                <thead>
                    <tr>
                        <th width="5">#</th>
                        <th>Nama Satuan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kategori as $no => $d)
                        <tr>
                            <td>{{ $no + 1 }}</td>
                            <td>{{ ucwords($d->nm_kategori) }}</td>
                            <td align="center">
                                <a href="#" id_atk="{{ $d->id_kategori_bahan }}"
                                    class="btn btn-primary btn-sm edit"><i class="fas fa-pen"></i></a>
                                <a onclick="return confirm('Yakin dihapus ?')"
                                    href="{{ route('bahan.kategori_hapus', $d->id_kategori_bahan) }}"
                                    class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>

        {{-- ALL MODAL --}}
        <form action="{{ route('bahan.kategori_create') }}" method="post">
            @csrf
            <x-theme.modal title="Tambah Kategori" idModal="tambah">
                <x-theme.multiple-input>
                    <div class="col-lg-10">
                        <div class="form-group">
                            <label for="">Kategori</label>
                            <input type="text" name="nm_kategori[]" class="form-control">
                        </div>
                    </div>
                </x-theme.multiple-input>
            </x-theme.modal>
        </form>

    </x-slot>

</x-theme.app>
