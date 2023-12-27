<div class="row">
    <div class="col-sm-4 ol-md-6 col-xs-12 mb-2">
        <label for="">Masukkan Gambar</label>
        <input type="file" class="dropify" data-height="150" name="image" placeholder="Image">
    </div>
    <div class="col-lg-8">
        <div class="row">
            <div class="col-lg-6 mb-2">
                <label for="">
                    <dt>Kategori</dt>
                </label>
                <input type="hidden" name="id_menu" value="{{ $menu->id_menu }}">
                <select name="id_kategori" id="" class="form-control selectedit">
                    <option value="">-Pilih Kategori-</option>
                    @foreach ($kategori as $m)
                        <option value="{{ $m->kd_kategori }}"
                            {{ $m->kd_kategori == $menu->id_kategori ? 'selected' : '' }}>
                            {{ $m->kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3">
                <label for="">
                    <dt>Level Point</dt>
                </label>
                <select name="id_handicap" id="" class="form-control selectedit">
                    <option value="">-Pilih Level-</option>
                    @foreach ($handicap as $m)
                        <option value="{{ $m->id_handicap }}"
                            {{ $m->id_handicap == $menu->id_handicap ? 'selected' : '' }}>{{ $m->handicap }}
                            ({{ $m->point }} Point)
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3 mb-2">
                <label for="">
                    <dt>Kode Menu</dt>
                </label>
                <input readonly type="text" name="kd_menu" class="form-control" placeholder="Kode Menu"
                    value="{{ $menu->kd_menu }}">
            </div>
            <div class="col-lg-6 mb-2">
                <label for="">
                    <dt>Nama Menu</dt>
                </label>
                <input type="text" name="nm_menu" class="form-control" placeholder="Nama Menu"
                    value="{{ $menu->nm_menu }}">
            </div>
            <div class="col-lg-2 mb-2">
                <label for="">
                    <dt>Tipe</dt>
                </label>
                <Select class="form-control selectedit" name="tipe">
                    <option value="">-Pilih tipe-</option>
                    <option value="food" {{ $menu->tipe == 'food' ? 'Selected' : '' }}>Food</option>
                    <option value="drink" {{ $menu->tipe == 'drink' ? 'Selected' : '' }}>Drink</option>
                </Select>
            </div>
            <div class="col-lg-4 mb-2">
                <label for="">
                    <dt>Station</dt>
                </label>
                <Select class="form-control selectedit" name="id_station">
                    <option value="">-Pilih station-</option>
                    @foreach ($st as $s)
                        <option value="{{ $s->id_station }}"
                            {{ $s->id_station == $menu->id_station ? 'selected' : '' }}>{{ $s->nm_station }}
                        </option>
                    @endforeach
                </Select>
            </div>

            <div class="col-lg-5 mb-2">
                <label for="">
                    <dt>Distribusi</dt>
                </label>
                <input type="hidden" name="id_distribusi[]" value="1">
                <input type="text" class="form-control" value="DINE-IN / TAKEWAY" readonly>
            </div>
            <div class="col-lg-5 mb-2">
                <label for="">
                    <dt>Harga</dt>
                </label>

                <input type="text" name="harga[]" class="form-control" placeholder="Harga"
                    value="{{ empty($harga1->harga) ? 0 : $harga1->harga }}">
            </div>
            <div class="col-lg-5 mb-2">
                <input type="hidden" name="id_distribusi[]" value="2">
                <input type="text" class="form-control" value="GOJEK" readonly>
            </div>
            <div class="col-lg-5 mb-2">
                <input type="text" name="harga[]" class="form-control" placeholder="Harga"
                    value="{{ empty($harga2->harga) ? 0 : $harga2->harga }}">
            </div>
        </div>

    </div>
    <div class="col-lg-12">
        <hr>
    </div>
    <div class="col-lg-6">
        <h6>Resep</h6>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th width="50">Bahan</th>
                    <th width="10">Qty</th>
                    <th width="10">Satuan</th>
                    <th width="5">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($resep as $no => $r)
                    <tr class="baris{{ $no + 1 }}">
                        <td>
                            <select name="id_bahan[]" id="" class="selectedit id_bahan" count="1">
                                <option value="">Pilih Bahan</option>
                                @foreach ($bahan as $b)
                                    <option value="{{ $b->id_list_bahan }}"
                                        {{ $r->id_bahan == $b->id_list_bahan ? 'selected' : '' }}>{{ $b->nm_bahan }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="text" class="form-control" name="qty[]" value="{{ $r->qty }}">
                        </td>
                        <td><input type="text" class="form-control nm_bahan1" value="{{ $r->nm_satuan }}"
                                readonly>
                        </td>
                        <td class="text-center"><button type="button" class="btn btn-rounded remove_baris"
                                count="1"><i class="fas fa-trash text-danger"></i></button>
                        </td>
                    </tr>
                @endforeach
                <tr>
            <tbody class="load_tambah_resep"></tbody>
            </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4"><button type="button"
                            class="btn btn-primary btn-block tambah_baris_resep">Tambah
                            Baris</button>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
