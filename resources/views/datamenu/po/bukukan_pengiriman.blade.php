<input type="hidden" name="no_nota" value="{{ $no_nota }}">
<h5>Biaya Pengiriman</h5>
<h6>{{ ucwords($transaksi->nm_transaksi) }}: {{ ucwords($transaksi->nm_suplier) }}</h5>
    <div class="row">
        <div class="col-lg-12">
            <table class="table  table-bordered table-hover" id="tblBukukasdan">
                <thead>
                    <tr>
                        <th class="dhead">Tgl</th>
                        <th class="dhead">Nama</th>
                        <th class="dhead">Pilih Akun</th>
                        <th class="dhead">Sub Akun</th>
                        <th class="dhead text-end">Debit</th>
                        <th class="dhead text-end">Kredit</th>
                    </tr>
                </thead>
                @php

                @endphp
                @php
                    $bTambahan = DB::table('po_biaya_tambahan as a')
                        ->join('tb_ekspedisi as b', 'a.id_ekspedisi', 'b.id_ekspedisi')
                        ->join('akun as c', 'a.id_akun', 'c.id_akun')
                        ->where('a.no_nota', $poDetail->no_nota)
                        ->first();
                    $totalKredit = $bTambahan->ttl_rp_biaya;
                    $totalDebit = $bTambahan->ttl_rp_biaya;
                @endphp
                <tbody>
                    <tr>
                        <td>{{ tanggal($poDetail->tgl) }}</td>
                        <td>Sub Total</td>
                        <td>
                            <input type="hidden" name="id_akun[]" value="42">
                            <input type="hidden" name="debit[]" value="{{ $bTambahan->ttl_rp_biaya }}">
                            <input type="hidden" name="kredit[]" value="0">
                            <select disabled name="id_akun_debit[]" id="" class="selectAkun">
                                @foreach ($akun as $d)
                                    <option {{ $d->id_akun == 42 ? 'selected' : '' }} value="{{ $d->id_akun }}">
                                        {{ $d->nm_akun }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select name="id_post_center[]" id="" class="selectAkun">
                                @foreach ($postCenter as $d)
                                    <option value="{{ $d->id_post_center }}">{{ $d->nm_post }}</option>
                                @endforeach
                            </select>


                        </td>
                        <td align="right">{{ number_format($bTambahan->ttl_rp_biaya, 2) }}</td>
                        <td align="right">{{ number_format(0, 2) }}</td>
                    </tr>

                    <tr>
                        <td>{{ tanggal($poDetail->tgl) }}</td>
                        <td>{{ $bTambahan->nm_akun }}</td>
                        <td>
                            <input type="text" readonly value="{{ strtoupper($bTambahan->nm_akun) }}"
                                class="form-control">
                            <input type="hidden" name="id_akun[]" readonly value="{{ $bTambahan->id_akun }}">
                            <input type="hidden" name="debit[]" value="0">

                            <input type="hidden" name="kredit[]" value="{{ $bTambahan->ttl_rp_biaya }}">
                            <input type="hidden" name="id_post_center[]">

                        </td>
                        <td></td>

                        <td align="right">{{ number_format(0, 2) }}</td>
                        <td align="right">{{ number_format($bTambahan->ttl_rp_biaya, 2) }}</td>
                    </tr>

                </tbody>
                <tfoot class="dhead text-white">
                    <tr>
                        <th colspan="2" class="text-center">Total</th>
                        <th></th>
                        <td></td>

                        <th class="text-end">
                            <input type="hidden" name="totalKredit" value="{{ $totalKredit }}">
                            <input type="hidden" name="totalDebit" value="{{ $totalDebit }}">
                            <h6 class="text-white">{{ number_format($totalKredit, 2) }}</h6>
                        </th>
                        <th class="text-end">
                            <h6 class="text-white">{{ number_format($totalDebit, 2) }}</h6>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3">
            <div class="form-group">
                <label for="">Nota Jurnal Pengiriman</label>
                <input type="hidden" name="nota_jurnal" value="{{ $nota_jurnal }}" class="form-control">
                <input type="text" readonly name="" value="JU-{{ $nota_jurnal }}" class="form-control">
            </div>
        </div>
        <div class="col-lg-3">
            <div class="form-group">
                <label for="">Nota Manual</label>
                <input type="text" name="nota_manual" class="form-control">
            </div>
        </div>
        <div class="col-lg-3">
            <div class="form-group">
                <label for="">Tgl Jurnal</label>
                <input type="date" name="tgl_jurnal" value="{{ date('Y-m-d') }}" class="form-control">
            </div>
        </div>
        <div class="col-lg-3">
            <div class="form-group">
                <label for="">Keterangan</label>
                <input type="text" name="ket" value="" placeholder="Keterangan jurnal"
                    class="form-control">
            </div>
        </div>
    </div>
