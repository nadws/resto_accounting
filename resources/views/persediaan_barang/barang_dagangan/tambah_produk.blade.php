<form action="{{ route('barang_dagangan.create') }}" method="post" enctype="multipart/form-data">
    @csrf
    <x-theme.modal size="modal-lg" title="Tambah Baru" idModal="tambah">
        <input type="hidden" name="url" value="{{ request()->route()->getName() }}">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-group">
                    <label for="">Image <span class="text-warning text-xs">Ukuran harus dibawah
                            1MB</span></label>
                    <input type="file" class="form-control" id="image" name="img"
                        accept="image/*">
                </div>
            </div>
            <div class="col-lg-12">
                <div id="image-preview"></div>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <label for="">Nama Produk</label>
                    <input required type="text" name="nm_produk" class="form-control">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="">Kode Produk</label>
                    <input type="hidden" name="kd_produk" value="{{ $kd_produk }}">
                    <input required value="P-{{ kode($kd_produk) }}" readonly type="text"
                        class="form-control">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="">Satuan</label>
                    <select required name="satuan_id" class="form-control select2" id="">
                        <option value="">- Pilih Satuan -</option>
                        @foreach ($satuan as $d)
                            <option value="{{ $d->id_satuan }}">{{ $d->nm_satuan }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-lg-12 mb-2">
                <div class="form-group">
                    <label for="">Gudang</label>
                    <select required name="gudang_id" class="form-control select2 tambah-gudang-select" id="">
                        <option value="">- Pilih Gudang -</option>
                        @foreach ($gudang as $d)
                            <option value="{{ $d->id_gudang }}">{{ $d->nm_gudang }}</option>
                        @endforeach
                        <option value="tambah">+ Gudang</option>
                    </select>
                </div>
            </div>
            <x-theme.toggle name="kontrol stok" />
        </div>
    </x-theme.modal>
</form>