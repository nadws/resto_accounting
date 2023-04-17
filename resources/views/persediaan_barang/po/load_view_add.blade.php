<style>
    .dikanan {
        text-align: right
    }
</style>
<section class="row">
    <div class="col-lg-3">
        <label for="">Tanggal</label>
        <input type="date" class="form-control" name="tgl" value="{{ date('Y-m-d') }}">
    </div>
    <div class="col-lg-3">
        <label for="">No Po</label>
        <input type="text" class="form-control" readonly name="no_po" value="12">
    </div>
    <div class="col-lg-12">
        <hr style="border: 1px solid black">
    </div>
    <div class="col-lg-12">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th width="30%">Produk / Bahan</th>
                    <th width="9%" class="dikanan">Jumlah</th>
                    <th width="7%">Satuan Ukur</th>
                    <th width="15%" class="dikanan">Harga Satuan</th>
                    <th width="5%"></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <select required name="id_produk[]" class="form-control select2" id="">
                            <option value="">- Pilih Produk -</option>
                            @foreach ($produk as $p)
                                <option value="{{ $p->id_produk }}">{{ $p->nm_produk }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="number" name="jumlah[]" min="0" class="form-control dikanan">
                    </td>
                    <td>
                        <select required name="id_satuan[]" class="form-control select2" id="">
                            <option value="">- Pilih Satuan -</option>
                            @foreach ($satuan as $s)
                                <option value="{{ $s->id_satuan }}">{{ strtoupper($s->nm_satuan) }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control dikanan rp-nohide text-end" value="Rp 0"
                            count="2">
                        <input type="hidden" class="form-control dikanan rp-hide rp-hide2" value="0"
                            name="rp_satuan[]">
                    </td>
                    <td style="vertical-align: top;">
                        <button type="button" class="btn rounded-pill remove_baris" count="2"><i
                                class="fas fa-trash text-danger"></i>
                        </button>
                    </td>
                </tr>
                {{-- @foreach ($produk as $d)
                    <input type="hidden" name="id_produk[]" value="{{ $d->id_produk }}">
                    <input type="hidden" name="jml_sebelumnya[]" value="{{ $d->jml_sebelumnya }}">
                    <input type="hidden" name="jml_sesudahnya[]" value="{{ $d->jml_sesudahnya }}">
                    <tr>
                        <td style="vertical-align: top;">{{ $d->produk->nm_produk }}</td>
                        <td style="vertical-align: top;">{{ $d->produk->satuan->nm_satuan }}</td>
                        <td style="vertical-align: top;" align="center">{{ $d->jml_sesudahnya }}</td>
                        <td style="vertical-align: top;" align="center"><input name="debit[]" style="text-align:right;" type="text" class="form-control"></td>
                        <td style="vertical-align: top;" align="center">0</td>
                    </tr>
                @endforeach --}}

            </tbody>
            <tbody id="tbh_baris">

            </tbody>
            <tfoot>
                <tr>
                    <th colspan="7">
                        <button type="button" class="btn btn-block btn-lg tbh_baris"
                            style="background-color: #F4F7F9; color: #8FA8BD; font-size: 14px; padding: 13px;">
                            <i class="fas fa-plus"></i> Tambah Baris Baru

                        </button>
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>
</section>
