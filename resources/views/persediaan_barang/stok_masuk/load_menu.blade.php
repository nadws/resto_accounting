<section class="row">
    <div class="col-lg-3">
        <label for="">Tanggal</label>
        <input type="date" class="form-control" name="tgl" value="{{date('Y-m-d')}}">
    </div>
    <div class="col-lg-3">
        <label for="">No Nota</label>
        <input type="text" class="form-control" readonly name="no_nota" value="{{ $no_nota }}">
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
                    <th width="10%">Stok Sebelumnya</th>
                    <th width="10%">Stok Masuk</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($produk as $i => $d)

                    <input type="hidden" name="id_produk[]" value="{{ $d->id_produk }}">
                    <input type="hidden" name="jml_sebelumnya[]" value="{{ $d->debit - $d->kredit }}">
                    <tr>
                        <td style="vertical-align: top;">{{ $d->nm_produk }}</td>
                        <td style="vertical-align: top;">{{ $d->nm_satuan }}</td>
                        <td style="vertical-align: top;" align="center">{{ $d->debit - $d->kredit }}</td>
                        <td style="vertical-align: top;" align="center"><input name="debit[]" style="text-align:right;" type="text" class="form-control debit-keyup{{$d->id_stok_produk}}"></td>
                    </tr>
                @endforeach
                
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="7">
                        <a class="btn btn-primary btn-block" href="#" data-bs-toggle="modal" data-bs-target="#tambah_add"><i
                            class="fas fa-plus"></i> Tambah </a>
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>
</section>

