<x-theme.app title="{{ $title }}" table="Y" sizeCard="12">
    <x-slot name="cardHeader">
        <a class="btn btn-primary float-end" href="#" data-bs-toggle="modal" data-bs-target="#tambah"><i
            class="fas fa-plus"></i> Tambah</a>
    </x-slot>
    <x-slot name="cardBody">

        <section class="row">
            <table class="table" id="table1">
                <thead>
                    <tr>
                        <th width="5">#</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Satuan</th>
                        <th>Qty</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- @foreach ($produk as $no => $d)
                        <tr>
                            <td>{{ $no + 1 }}</td>
                            <td>P-{{ kode($d->kd_produk) }}</td>
                            <td>{{ ucwords($d->nm_produk) }}</td>
                            <td>
                                {{ ucwords($d->satuan->nm_satuan) }}
                            </td>
                            <td>10 Kg</td>
                            <td align="center">
                                <div class="btn-group dropstart mb-1">
                                    <span class="btn btn-lg" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v text-primary"></i>
                                    </span>
                                    <div class="dropdown-menu">
                                        <a id_produk="{{$d->id_produk}}" data-bs-toggle="modal" data-bs-target="#edit" class="dropdown-item text-primary edit" href="#"><i
                                                class="me-2 fas fa-pen"></i>
                                            Edit</a>
                                        <a class="dropdown-item text-danger" onclick="return confirm('Yakin dihapus ?')" href="{{ route('produk.delete', $d->id_produk) }}"><i
                                                class="me-2 fas fa-trash"></i> Delete</a>
                                        <a class="dropdown-item text-info" href="#"><i
                                                class="me-2 fas fa-search"></i>
                                            Detail</a>
                                    </div>
                                </div>

                            </td>
                        </tr>
                    @endforeach --}}

                </tbody>
            </table>
        </section>

        {{-- create --}}
        <form action="{{ route('gudang.create') }}" method="post">
            @csrf
            <x-theme.modal size="modal-lg" title="Tambah Baru" idModal="tambah">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <input type="hidden" name="url" value="produk">
                            <input type="hidden" name="segment" value="{{ Request::segment(2) }}">
                            <label for="">Kode Gudang</label>
                            <input type="text" name="kd_gudang" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="form-group">
                            <label for="">Nama Gudang</label>
                            <input type="text" name="nm_gudang" class="form-control">
                        </div>
                    </div>
                </div>
            </x-theme.modal>
        </form>
        {{-- ------ --}}
    </x-slot>

    {{-- @section('scripts')
        <script>
            $(document).ready(function() {
                $(".select-gudang").change(function(e) {
                    e.preventDefault();
                    var gudang_id = $(this).val()
                    document.location.href = `/produk/${gudang_id}`
                });

                // edit
                edit('edit', 'id_produk', 'produk/edit', 'load-edit')
            });
        </script>
    @endsection --}}
</x-theme.app>
