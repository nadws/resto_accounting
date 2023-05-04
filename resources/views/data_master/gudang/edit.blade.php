
        <div class="row">
            <div class="col-lg-2">
                <div class="form-group">
                    <input type="hidden" name="id_gudang" value="{{ $gudang->id_gudang }}">
                    <label for="">Kode Gudang</label>
                    <input required type="text" value="{{ $gudang->kd_gudang }}" name="kd_gudang" class="form-control">
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="">Kategori Persediaan</label>
                    <select required name="kategori_id" class="form-control select2-edit" id="">
                        <option value="">- Pilih Kategori -</option>
                        <option {{$gudang->kategori_id == 1 ? 'selected' : ''}} value="1">Atk & Peralatan</option>
                        <option {{$gudang->kategori_id == 2 ? 'selected' : ''}} value="2">Bahan Baku</option>
                        <option {{$gudang->kategori_id == 3 ? 'selected' : ''}} value="3">Barang Dagangan</option>
                    </select>

                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="">Nama Gudang</label>
                    <input required type="text" value="{{ $gudang->nm_gudang }}" name="nm_gudang" class="form-control">
                </div>
            </div>
        </div>