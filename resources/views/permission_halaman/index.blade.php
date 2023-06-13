<x-theme.app title="{{ $title }}" table="Y" sizeCard="6">
    <x-slot name="cardHeader">
        <h6 class="float-start">Akses</h6>
        <x-theme.button modal="Y" idModal="tambah" icon="fa-plus" addClass="float-end" teks="Tambah" />
    </x-slot>

    <x-slot name="cardBody">
        <div x-data="{
            permissionButton: [],
            namaPermission: '',
            idPermission: '',
            showDetail: function(id, namaPermission) {
                this.namaPermission = namaPermission
                this.idPermission = id
        
                axios.get(`/akses/${id}`)
                    .then(response => {
                        this.permissionButton = response.data
                        console.log(response.data)
                        $('#detail').modal('show');
                    })
            }
        }">
            <section class="row">
                <table class="table" id="table1">
                    <thead>
                        <tr>
                            <th width="5">#</th>
                            <th>Nama Permission</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($permissionHalaman as $no => $d)
                            <tr>
                                <td>{{ $d->id_permission }}</td>
                                <td style="cursor:pointer"
                                    @click="showDetail({{ $d->id_permission }}, '{{ $d->nm_permission }}')">
                                    {{ $d->nm_permission }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>

            {{-- tambah suplier --}}
            <form action="{{ route('akses.add_menu') }}" method="post">
                @csrf
                <x-theme.modal title="Tambah Baru" idModal="tambah">
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

            {{-- edit --}}
            <form action="{{ route('akses.add_menu') }}" method="post">
                @csrf
                <x-theme.modal title="Tambah Baru" idModal="detail">
                    <h5 x-text="namaPermission" class="text-center"></h5>
                    <input type="hidden" name="detail" value="Y">
                    <input type="hidden" name="id_permission_gudang" x-bind:value="idPermission">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>List Button</th>
                                <th>Jenis</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(d, i) in permissionButton">

                                <tr>
                                    <td x-text="d.id_permission_button"></td>
                                    <td>
                                        <input type="text" name="nm_button_detail[]"
                                            x-bind:value="d.nm_permission_button" class="form-control">
                                        <input type="hidden" name="id_permission_button[]"
                                            x-bind:value="d.id_permission_button" class="form-control">
                                    </td>
                                    <td>
                                        <select name="jenis[]" id="" class="form-control">
                                            <option x-bind:selected="d.jenis === 'create'" value="create">Create
                                            </option>
                                            <option x-bind:selected="d.jenis === 'read'" value="read">Read</option>
                                            <option x-bind:selected="d.jenis === 'update'" value="update">Update
                                            </option>
                                            <option x-bind:selected="d.jenis === 'delete'" value="delete">Delete
                                            </option>
                                        </select>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                    <x-theme.multiple-input>
                        <input type="hidden" name="tambah_row" value="Y">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">Nama Butoon & Icon</label>
                                <input type="text" name="nm_button_row[]" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="">Jenis</label>
                                <select name="jenis_row[]" id="" class="form-control">
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
            {{-- end edit --}}

        </div>

    </x-slot>
</x-theme.app>
