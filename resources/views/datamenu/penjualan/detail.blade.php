<div class="row">
    <div class="col-lg-12">
        @php
            $get = $history[0];
            $nm_satuan = ' ' . strtoupper($get->nm_satuan);
        @endphp
        <table class="table">
            <tr>
                <th width="10">Tanggal</th>
                <td width="10">:</td>
                <th>{{ tanggal($get->tgl) }}</th>
            </tr>
            <tr>
                <th width="10">Bahan</th>
                <td width="10">:</td>
                <th><h5>{{ ucwords($get->nm_bahan) }}</h5></th>
            </tr>
            <tr>
                <th width="10">Total</th>
                <td width="10">:</td>
                <th><h6>{{ number_format($get->ttl,0) . $nm_satuan }}</h6></th>
            </tr>

        </table>

    </div>
    <hr>
    <div class="col-lg-12">
        <table class="table table-striped table-bordered" id="table1">
            <thead>
                <tr>
                    <th class="dhead">#</th>
                    <th class="dhead">Nama Menu</th>
                    <th class="dhead text-center">Terjual</th>
                    <th class="dhead text-center">Qty <br> Resep</th>
                    <th class="dhead text-center">Qty <br> Terpakai</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($history as $no => $d)
                    <tr>
                        <td>{{ $no + 1 }}</td>
                        <td>{{ $d->nm_menu }}</td>
                        <td align="right">{{ number_format($d->terjual,0) }}</td>
                        <td align="right">{{ number_format($d->qty,0) . $nm_satuan }}</td>
                        <td align="right">{{ number_format($d->kredit,0) . $nm_satuan  }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
