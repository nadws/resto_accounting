<x-theme.app title="{{$title}}" table="Y" sizeCard="12">
    <x-slot name="cardHeader">
        <div class="row justify-content-end">
            <div class="col-lg-4">
                <select name="example" class="form-control select2 float-end" id="">
                    <option value="" selected>All Werehouse</option>
                    <option value="" >Takemori</option>
                    <option value="" >Soondobu</option>
                </select>
            </div>
            <div class="col-lg-2">
                <x-theme.button modal="Y" idModal="tambah" icon="fa-plus" addClass="float-end" teks="Tambah" />
            </div>
        </div>
        
    </x-slot>

    <x-slot name="cardBody">
        <section class="row">
            <table class="table" id="table1">
                <thead>
                    <tr>
                        <th width="5">#</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Harga Jual</th>
                        <th>Qty</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>A-01</td>
                        <td>Ban</td>
                        <td>Umum</td>
                        <td>IDR {{ number_format(100000,0) }}</td>
                        <td>10 Kg</td>
                        <td>Aktif</td>
                        <td>
                            <div class="btn-group dropstart mb-1">
                                <span
                                class="btn btn-lg"
                                  data-bs-toggle="dropdown"
                                >
                                <i class="fas fa-ellipsis-v text-primary"></i>
                                </span>
                                <div class="dropdown-menu">
                                  <a class="dropdown-item text-primary" href="#"><i class="me-2 fas fa-pen"></i> Edit</a>
                                  <a class="dropdown-item text-danger" href="#"><i class="me-2 fas fa-trash"></i> Delete</a>
                                  <a class="dropdown-item text-info" href="#"><i class="me-2 fas fa-search"></i> Detail</a>
                                </div>
                              </div>
      
                        </td>
                    </tr>

                </tbody>
            </table>
        </section>

        <x-theme.modal size="modal-lg" title="Tambah Baru" idModal="tambah">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="">Nama Produk</label>
                        <input type="text" name="nm_produk" class="form-control">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="">Kode Produk</label>
                        <input type="text" name="kd_produk" class="form-control">
                    </div>
                </div>
                <div class="col-lg-6">
                    <label for="">Satuan</label>
                    <select name="satuan_id" class="form-control select2" id="">
                        <option value="">- Pilih Satuan -</option>
                    </select>
                </div>
            </div>
        </x-theme.modal>
    </x-slot>
</x-theme.app>