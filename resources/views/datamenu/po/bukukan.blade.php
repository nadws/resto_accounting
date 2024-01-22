<input type="hidden" name="no_nota" value="{{ $no_nota }}">
<h6>{{ ucwords($transaksi->nm_transaksi) }}: {{ ucwords($transaksi->nm_suplier) }}</h5>
    <div x-data="{ tampil: false }">
        <button type="button" class="btn btn-sm btn-primary mb-2" @click="tampil = ! tampil">View Detail <i
                class="fas fa-eye"></i></button>
        <div class="row" x-show="tampil">
            @include('datamenu.po.components.tbl_item')
        </div>

        <div class="row" x-show="tampil">
            @php
                $total = $poDetail->sub_total - $poDetail->potongan + $poDetail->biaya + $poDetail->ttl_pajak;
                $sisaTagihan = $total - $bayarSum->ttlBayar;
            @endphp
            @include('datamenu.po.components.tbl_sub', [
                'sisaTagihan' => $sisaTagihan,
                'total' => $total,
            ])
        </div>
    </div>
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
                    $totalKredit = $poDetail->ttl_pajak + $poDetail->sub_total + $poDetail->biaya;
                    $totalDebit = 0;
                @endphp
                <tbody>
                    <tr>
                        <td>{{ tanggal($poDetail->tgl) }}</td>
                        <td>Sub Total</td>
                        <td>
                            <input type="hidden" name="id_akun[]" value="42">
                            <input type="hidden" name="debit[]" value="{{ $poDetail->sub_total }}">
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
                        <td align="right">{{ number_format($poDetail->sub_total, 2) }}</td>
                        <td align="right">{{ number_format(0, 2) }}</td>
                    </tr>
                    @if ($poDetail->biaya)
                        <tr>
                            <td>{{ tanggal($poDetail->tgl) }}</td>
                            <td>Biaya Pengiriman</td>
                            <td>
                                <input type="hidden" name="debit[]" value="{{ $poDetail->biaya }}">
                                <input type="hidden" name="id_post_center[]">
                                <input type="hidden" name="kredit[]" value="0">

                                <select name="id_akun[]" id="" class="selectAkun">

                                    @foreach ($akun as $d)
                                        <option value="{{ $d->id_akun }}">{{ $d->nm_akun }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td></td>

                            <td align="right">{{ number_format($poDetail->biaya, 2) }}</td>
                            <td align="right">{{ number_format(0, 2) }}</td>
                        </tr>
                    @endif
                    @if ($poDetail->ttl_pajak)
                        <tr>
                            <td>{{ tanggal($poDetail->tgl) }}</td>
                            <td>Pajak</td>
                            <td>
                                <input type="text" readonly value="PAJAK" class="form-control">
                                <input type="hidden" name="id_akun[]" readonly value="41">
                            <input type="hidden" name="kredit[]" value="0">

                                <input type="hidden" name="debit[]" value="{{ $poDetail->ttl_pajak }}">
                                <input type="hidden" name="id_post_center[]">

                            </td>
                            <td></td>

                            <td align="right">{{ number_format($poDetail->ttl_pajak, 2) }}</td>
                            <td align="right">{{ number_format(0, 2) }}</td>
                        </tr>
                    @endif
                </tbody>
                <tbody>


                    @foreach ($cekSudahPernahBayar as $i => $d)
                        @php
                            $totalKredit += 0;
                            $totalDebit += $d->jumlah;
                        @endphp
                        <tr class="">
                            <td>{{ tanggal($d->tgl_transaksi) }}</td>
                            <td>
                                {{ $d->nm_akun }}
                            </td>
                            <td>
                                <input type="hidden" name="id_akun[]" value="{{ $d->id_akun }}">
                                <input type="hidden" name="debit[]" value="{{ 0 }}">
                                <input type="hidden" name="kredit[]" value="{{ $d->jumlah }}">
                                <input type="hidden" name="id_post_center[]" >
                                <input type="text" readonly class="form-control" value="{{ $d->nm_akun }}">
                                {{-- <select name="id_akun_debit[]" id="" class="selectAkun">
                                    @foreach ($akun as $a)
                                        <option {{$d->id_akun == $a->id_akun ? 'selected' : ''}} value="{{ $a->id_akun }}">{{ $a->nm_akun }}</option>
                                    @endforeach
                                </select> --}}
                            </td>
                            <td></td>

                            <td align="right">0</td>
                            <td align="right">{{ number_format($d->jumlah, 2) }}</td>
                        </tr>
                    @endforeach
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
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3">
            <div class="form-group">
                <label for="">Nota Jurnal</label>
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
