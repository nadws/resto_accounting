<section class="row">
    <div class="col-lg-2">
        <label for="">Tanggal</label>
        <input type="date" class="form-control" name="tgl" value="{{ date('Y-m-d') }}">
    </div>
    <div class="col-lg-2">
        <label for="">No Nota</label>
        <input type="text" class="form-control" readonly name="no_nota" value="{{ $no_nota }}">
    </div>

    <div class="col-lg-3">
        <label for="">Pilih Gudang</label>
        <select {{ $status == 'selesai' ? 'disabled' : '' }} required name="gudang_id" class="form-control select2"
            id="">
            <option value="">- Pilih Gudang -</option>
            @foreach ($gudang as $d)
                <option value="{{ $d->id_gudang }}">{{ $d->nm_gudang }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-lg-4">
        <label for="">Keterangan</label>
        <input type="text" class="form-control" name="ket" placeholder="Keterangan">
    </div>
    <div class="col-lg-12">
        <hr style="border: 1px solid black">
    </div>
    <div class="col-lg-12">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th width="20%">Produk</th>
                    <th width="9%">Satuan</th>
                    <th width="6%">Stok Sebelumnya</th>
                    <th width="6%">Stok Masuk</th>
                    <th width="10%">Harga Satuan</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($produk as $i => $d)
                    <input type="hidden" name="id_produk[]" value="{{ $d->id_produk }}">
                    <input type="hidden" name="jml_sebelumnya[]" value="{{ $d->ttlDebit - $d->ttlKredit }}">
                    <tr>
                        <td style="vertical-align: top;">{{ $d->nm_produk }}</td>
                        <td style="vertical-align: top;">{{ $d->nm_satuan }}</td>
                        <td style="vertical-align: top;" align="center">{{ $d->ttlDebit - $d->ttlKredit }}</td>
                        <td style="vertical-align: top;" align="center">
                            <input required name="debit[]" style="text-align:right;" value="{{ $d->debit ?? 0 }}"
                                {{ $status == 'selesai' ? 'readonly' : '' }} type="text"
                                class="form-control debit-keyup">
                        </td>
                        <td style="vertical-align: top;" align="center">
                            <input type="text" class="form-control dikanan rp-nohide text-end" value="Rp 0"
                                count="2">
                            <input type="hidden" class="form-control dikanan rp-hide rp-hide2" value="0"
                                name="rp_satuan[]">
                        </td>
                    </tr>
                @endforeach

            </tbody>
            @if ($status != 'selesai')
                <tfoot>
                    <tr>
                        <th colspan="7">
                            <a class="btn btn-primary btn-block" href="#" data-bs-toggle="modal"
                                data-bs-target="#tambah_add"><i class="fas fa-plus"></i> Tambah </a>
                        </th>
                    </tr>
                </tfoot>
            @endif

        </table>
    </div>
</section>
