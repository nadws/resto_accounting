<x-theme.app title="{{ $title }}" table="Y" sizeCard="12">
    <x-slot name="cardHeader">
        <div class="row justify-content-end">
            <div class="col-lg-2">
                <div class="btn-group dropstart float-end mb-1">
                    <a class="btn btn-primary float-end" href="#" data-bs-toggle="modal" data-bs-target="#tambah"><i
                        class="fas fa-plus"></i> Tambah</a>
                </div>
            </div>
        </div>

    </x-slot>
    <x-slot name="cardBody">

        <section class="row">
            <table class="table" id="table1">
                <thead>
                    <tr>
                        <th width="5%">ID</th>
                                            <th>Nama Permission</th>
                    </tr>
                </thead>
                <tbody>
                    

                </tbody>
            </table>
        </section>


        {{-- tambah produk --}}
        <form action="{{ route('produk.create') }}" method="post" enctype="multipart/form-data">
            @csrf
            <x-theme.modal size="modal-lg" title="Tambah Baru" idModal="tambah">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="">Nama Permission</label>
                            <input required type="text" name="nm_permission" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="">Url</label>
                            <input required type="text" name="url" class="form-control">
                        </div>
                    </div>
                </div>
                <x-theme.multiple-input>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="">Nama Butoon & Icon</label>
                            <input type="text" name="nm_button[]" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for="">Jenis</label>
                            <select name="jenis[]" id="" class="form-control">
                                <option value="">- Pilih Jenis -</option>
                                <option value="create">Create</option>
                                <option value="read">Read</option>
                                <option value="update">Update</option>
                                <option value="delete">Delete</option>
                            </select>
                        </div>
                    </div>
                </x-theme.multiple-input>
            </x-theme.modal>
        </form>
        {{-- ------ --}}

        {{-- edit produk --}}
        
    </x-slot>

</x-theme.app>
