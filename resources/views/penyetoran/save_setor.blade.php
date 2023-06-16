<table class="table table-bordered">
    <thead>
        <tr>
            <th class="dhead">Tanggal</th>
            <th class="dhead">Nota</th>
            <th class="dhead">Pembayaran</th>
            <th class="dhead">Total Rp</th>
        </tr>
    </thead>
    <tbody>
        @php
            $ttlNominal = 0;
        @endphp
        <input type="hidden" name="nota_setor" value="{{ $nota }}">
        @foreach ($datas as $d)
            @php
                $ttlNominal += $d->nominal;
            @endphp
            <input type="hidden" name="no_nota[]" value="{{ $d->no_nota }}">
            <input type="hidden" name="urutan[]" value="{{ $d->urutan }}">
            <input type="hidden" name="id_akun[]" value="{{ $d->id_akun }}">
            <input type="hidden" name="nominal[]" value="{{ $d->nominal }}">
            <input type="hidden" name="id_jurnal[]" value="{{ $d->id_jurnal }}">
            <tr>
                <td>{{ tanggal($d->tgl) }}</td>
                <td>{{ $d->no_nota }}</td>
                <td>{{ $d->nm_akun }}</td>
                <td>Rp. {{ number_format($d->nominal, 0) }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th class="text-center" colspan="3">TOTAL</th>
            <th>Rp. {{ number_format($ttlNominal, 0) }}</th>
        </tr>
    </tfoot>
</table>
<div class="row">
    <div class="col-lg-3">
        <div class="form-group">
            <label for="">Tanggal</label>
            <input type="date" value="{{ date('Y-m-d') }}" name="tgl_setor" class="form-control">
        </div>
    </div>
    <div class="col-lg-5">
        <div class="form-group">
            <label for="">Akun Setor</label>
            <select required name="akun_setor" class="form-control select2-edit" id="">
                <option value="">- Pilih Akun -</option>
                @foreach ($akun as $d)
                    <option value="{{ $d->id_akun }}">{{ $d->nm_akun }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-group">
            <label for="">Keterangan</label>
            <input type="text" name="ket" class="form-control">
        </div>
    </div>
</div>
