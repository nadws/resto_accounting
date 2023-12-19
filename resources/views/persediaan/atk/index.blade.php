<x-theme.app title="{{ $title }}" table="Y" sizeCard="10">
    <x-slot name="cardHeader">
        <div class="row">
            <div class="col-lg-12">
                @include('persediaan.atk.nav')

            </div>
            <div class="col-lg-12">
                <hr>
            </div>
        </div>
        <h6 class="float-start">{{ $title }}</h6>
        <div class="row justify-content-end">
            <div class="col-lg-6">
                <x-theme.button modal="Y" idModal="tambah" href="#" icon="fa-plus" addClass="float-end"
                    teks="Buat Baru" />
            </div>

        </div>

    </x-slot>

    <x-slot name="cardBody">

        <section class="row">
            <table class="table" id="table1">
                <thead>
                    <tr>
                        <th width="5">#</th>
                        <th>CFM</th>
                        <th>Kategori</th>
                        <th>Nama</th>
                        <th class="text-end">Qty</th>
                        <th>Satuan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($atk as $no => $d)
                        <tr>
                            <td>{{ $no + 1 }}</td>
                            <td>{{ $d->cfm }}</td>
                            <td>{{ $d->nm_kategori }}</td>
                            <td>{{ ucwords($d->nm_atk) }}</td>
                            <td class="text-end">{{ empty($d->stok) ? '0' : $d->stok }}</td>
                            <td>{{ $d->nm_satuan }}</td>

                            <td align="center">
                                <a href="#" id_atk="{{ $d->id_atk }}" class="btn btn-warning btn-sm edit"><i
                                        class="fas fa-edit"></i></a>
                                <a href="" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </section>
        <form action="{{ route('atk.save') }}" method="post" enctype="multipart/form-data">
            @csrf
            <x-theme.modal title="Pilih Tahun" idModal="tambah" size="modal-lg">
                <div class="row">
                    <div class="col-lg-2">
                        <label for="">CFM</label>
                        <input type="text" class="form-control" name="cfm">
                    </div>
                    <div class="col-lg-4">
                        <label for="">Kategori</label>
                        <select name="kategori_id" id="" class="select2">
                            <option value="">Pilih Kategori</option>
                            @foreach ($kategori as $k)
                                <option value="{{ $k->id_kategori }}">{{ $k->nm_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <label for="">Nama Atk</label>
                        <input type="text" class="form-control" name="nm_atk">
                    </div>
                    <div class="col-lg-2">
                        <label for="">Satuan</label>
                        <select name="satuan_id" id="" class="select2">
                            <option value="">Pilih Satuan</option>
                            @foreach ($satuan as $s)
                                <option value="{{ $s->id_satuan }}">{{ $s->nm_satuan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4 mt-2">
                        <label for="">Upload Foto</label>
                        <input type="file" class="form-control" name="foto">
                    </div>
                    <div class="col-lg-4 mt-2">
                        <label for="">Kontrol Stok</label>
                        <br>
                        <input type="radio" class="mt-2" name="kontrol_stok" id="" value="Y"> Iya
                        &nbsp;
                        <input type="radio" class="mt-2" name="kontrol_stok" id="" value="T"> Tidak
                    </div>
                </div>

            </x-theme.modal>
        </form>

        @section('scripts')
            <script>
                $(document).ready(function() {

                    $(document).on("click", '.edit', function(e) {
                        e.preventDefault();
                        var id_atk = $(this).attr('id_atk')
                    })
                });
            </script>
        @endsection
    </x-slot>


</x-theme.app>
