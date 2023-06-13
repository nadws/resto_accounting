<x-theme.app title="{{ $title }}" table="Y" sizeCard="10">
    <x-slot name="cardHeader">
        <h6 class="float-start">Akses</h6>
        <x-theme.button modal="Y" idModal="tambah" icon="fa-plus" addClass="float-end" teks="Tambah" />
    </x-slot>

    <x-slot name="cardBody">
        <div x-data="{
            permissionButton: [],
            urutan: '',
            route: '',
            isi: '',
            id_navbar: '',
            nama: '',
            showDetail: function(id) {
              
        
                axios.get(`/akses/detail/${id}`)
                    .then(response => {
                        this.id_navbar = response.data.id_navbar
                        this.urutan = response.data.urutan
                        this.nama = response.data.nama
                        this.route = response.data.route
                        this.isi = response.data.isi

                        console.log(response.data.urutan)
                        $('#detail').modal('show');
                    })
            }
        }">
            <section class="row">
                <table class="table" id="table1">
                    <thead>
                        <tr>
                            <th width="5">#</th>
                            <th>Nama</th>
                            <th>Route</th>
                            <th>Isi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($permissionHalaman as $no => $d)
                            <tr>
                                <td>{{ $d->urutan }}</td>
                                <td style="cursor:pointer; color:blue"
                                    @click="showDetail({{ $d->id_navbar }})">
                                    {{ $d->nama }}</td>
                                <td>{{ $d->route }}</td>
                                <td>{{ $d->isi }}</td>
                                <td><a class="btn btn-sm btn-danger" onclick="return confirm('dihapus ?')" href="{{ route('akses.navbar_delete', $d->id_navbar) }}">hapus</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>

            {{-- tambah suplier --}}
            <form action="{{ route('akses.add_menu') }}" method="post">
                @csrf
                <input type="hidden" name="navbar" value="1">
                <x-theme.modal title="Tambah Baru" idModal="tambah">
                    <div class="row">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="">Urutan</label>
                                <input required type="text" name="urutan[]" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="">Nama</label>
                                <input required type="text" name="nama[]" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">Route</label>
                                <input required type="text" name="route[]" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="">Isi</label>
                                <input required type="text" name="isi[]" class="form-control">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <x-theme.multiple-input>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="">Urutan</label>
                                <input required type="text" name="urutan[]" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="">Nama</label>
                                <input required type="text" name="nama[]" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">Route</label>
                                <input required type="text" name="route[]" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="">Isi</label>
                                <input required type="text" name="isi[]" class="form-control">
                            </div>
                        </div>
                    </x-theme.multiple-input>

                </x-theme.modal>
            </form>
            {{-- ------ --}}

            {{-- edit --}}
            <form action="{{ route('akses.add_menu') }}" method="post">
                @csrf
                <input type="hidden" name="navbar" value="1">
                <input type="hidden" name="navbar_edit" value="1">
                <x-theme.modal title="Tambah Baru" idModal="detail">

                    <div class="row">
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="">Urutan</label>
                            <input x-bind:value="urutan" required type="text" name="urutan[]" class="form-control">
                            <input x-bind:value="id_navbar" required type="hidden" name="id_navbar[]" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="">Nama</label>
                            <input required x-bind:value="nama" type="text" name="nama[]" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="">Route</label>
                            <input required type="text" x-bind:value="route" name="route[]" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="">Isi</label>
                            <textarea class="form-control" name="isi[]" id="" cols="5" rows="10" x-text="isi"></textarea>
                        </div>
                    </div>
                </div>

                </x-theme.modal>
            </form>
            {{-- end edit --}}

        </div>

    </x-slot>
</x-theme.app>
