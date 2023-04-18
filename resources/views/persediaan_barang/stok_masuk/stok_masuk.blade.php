<x-theme.app title="{{ $title }}" table="Y" sizeCard="12">
    <x-slot name="cardHeader">
        <a class="btn btn-primary float-end" href="#" data-bs-toggle="modal" data-bs-target="#tambah"><i
                class="fas fa-plus"></i> Tambah</a>
    </x-slot>
    <x-slot name="cardBody">

        <section class="row">
            <table class="table table-hover" id="tableScroll">
                <thead>
                    <tr>
                        <th width="5">#</th>
                        <th class="text-center">Tanggal</th>
                        <th>No Nota</th>
                        <th>Status</th>
                        <th class="text-center">Jumlah Barang</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stok as $no => $d)
                        <tr>
                            <td>{{ $no + 1 }}</td>
                            <td align="center">{{ tanggal($d->tgl) }}</td>
                            <td><a href="{{ route('stok_masuk.add', ['no_nota' => $d->no_nota]) }}">{{ $d->no_nota }}</a></td>
                            <td>{{ $d->jenis }}</td>
                            <td align="center">{{ $d->debit }}</td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </section>

        {{-- create --}}
        <form action="{{ route('stok_masuk.create') }}" method="post">
            @csrf
            <x-theme.modal size="modal-lg" title="Tambah Baru" idModal="tambah">
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
                        @foreach ($produk as $no => $p)
                            <tr>
                                <td><input name="id_produk[]" value="{{ $p->id_produk }}" id="for{{ $no + 1 }}"
                                        type="checkbox" class="checkbox checkItem"></td>
                                <td>{{ $no + 1 }}</td>
                                <td><label style="font-size: 16px;" class="form-check-label"
                                        for="for{{ $no + 1 }}">{{ ucwords($p->nm_produk) }}</label></td>
                                <td>{{ $p->satuan->nm_satuan }}</td>
                                <td>1</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </x-theme.modal>
        </form>
        {{-- ------ --}}
    </x-slot>

    @section('scripts')
        <script>
            $(document).ready(function() {
                inputChecked('checkAll', 'checkItem')
                

                pencarian('pencarian', 'tableProduk')
            });
        </script>
    @endsection
</x-theme.app>
