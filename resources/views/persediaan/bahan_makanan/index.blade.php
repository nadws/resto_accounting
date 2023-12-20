<x-theme.app title="{{ $title }}" table="Y" sizeCard="10">
    <x-slot name="cardHeader">
        <div class="row">
            <div class="col-lg-12">
                @include('persediaan.bahan_makanan.nav')

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
                        <th>Nama Bahan</th>
                        <th>Kategori</th>
                        <th class="text-center">Stok</th>
                        <th>Satuan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
   
                <tbody>
                    @foreach ($bahan as $i => $d)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td>{{ $d->nm_bahan }}</td>
                            <td>{{ $d->nm_kategori }}</td>
                            <td>{{ number_format($d->stok,0) }}</td>
                            <td>{{ $d->nm_satuan }}</td>
                            <td align="center">
                                <a href="#" id_list_bahan="{{ $d->id_list_bahan }}" class="btn btn-primary btn-sm edit"><i
                                        class="fas fa-pen"></i></a>
                                <a href="" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
      
            <form action="{{ route('bahan.save') }}" method="post" enctype="multipart/form-data">
                @csrf
                <x-theme.modal title="Pilih Tahun" idModal="tambah" size="modal-lg">
                    <div class="row">
                        <div class="col-lg-4">
                            <label for="">Nama Bahan</label>
                            <input type="text" class="form-control" name="nm_bahan">
                        </div>
                        <div class="col-lg-4">
                            <label for="">Kategori</label>
                            <select name="kategori_id" id="" class="select2">
                                <option value="">Pilih Kategori</option>
                                @foreach ($kategori as $k)
                                <option value="{{ $k->id_kategori_bahan }}">{{ strtoupper($k->nm_kategori) }}</option>
                            @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <label for="">Satuan</label>
                            <select name="satuan_id" id="" class="select2">
                                <option value="">Pilih Satuan</option>
                                @foreach ($satuan as $s)
                                <option value="{{ $s->id_satuan }}">{{ strtoupper($s->nm_satuan)}}</option>
                            @endforeach
                            </select>
                        </div>
                   
                    </div>
    
                </x-theme.modal>
            </form>
     
    </x-slot>


</x-theme.app>
