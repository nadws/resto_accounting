<x-theme.app title="{{ $title }}" table="Y" sizeCard="8">
    <x-slot name="cardHeader">
        <x-theme.button modal="Y" idModal="tambah" icon="fa-plus" addClass="float-end" teks="Tambah" />
    </x-slot>

    <x-slot name="cardBody">
        <section class="row">

            
            <table class="table" id="table1">
                <thead>
                    <tr>
                        <th width="5">#</th>
                        <th>NPWP</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>1</td>
                        <td>1</td>
                        <td>1</td>
                        <td>1</td>
                        <td>
                            <x-theme.button hapus="Y" href="#" icon="fa-trash" addClass="float-end"
                                teks="" variant="danger" />
                            <x-theme.button modal="Y" idModal="edit-modal" icon="fa-pen"
                                addClass="me-1 float-end edit-btn" teks="" data="url=#" />
                        </td>
                    </tr>

                </tbody>
            </table>
        </section>
        {{-- tambah produk --}}
        <form action="{{ route('produk.create') }}" method="post" enctype="multipart/form-data">
            @csrf
            <x-theme.modal title="Tambah Baru" idModal="tambah">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="">Nama</label>
                            <input type="text" name="nama" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="">Telepon</label>
                            <input type="text" name="telepon" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="">Npwp</label>
                            <input type="text" name="npwp" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="">Unggah dokumen</label>
                            <input type="file" class="form-control" id="image" accept="image/*">
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div id="image-preview" class="float-end"></div>
                    </div>
                </div>
            </x-theme.modal>
        </form>
        {{-- ------ --}}

        {{-- edit produk --}}
        <form action="{{ route('produk.edit') }}" method="post" enctype="multipart/form-data">
            @csrf
            <x-theme.modal size="modal-lg" title="Edit Produk" idModal="edit">
                <div id="load-edit"></div>
            </x-theme.modal>
        </form>
    </x-slot>
    @section('scripts')
        <script>
            $(document).ready(function() {
                
            });
        </script>
    @endsection
</x-theme.app>
