<div class="row">
    <div class="col-lg-12">
        <table class="table">
            <tr>
                <td width="10">Tanggal</td>
                <td width="10">:</td>
                <th>{{ tanggal($history[0]->tgl) }}</th>
            </tr>
            <tr>
                <td width="10">Bahan</td>
                <td width="10">:</td>
                <th>{{ ucwords($history[0]->nm_bahan) }}</th>
            </tr>
            @php
                
            @endphp
            <tr>
                <td width="10">Total</td>
                <td width="10">:</td>
                <th>{{ number_format($history[0]->ttl,0) }}</th>
            </tr>

        </table>

    </div>
    <hr>
    <div class="col-lg-12">
        <table class="table table-striped" id="table1">
            <thead>
                <tr>
                    <th class="dhead">#</th>
                    <th class="dhead">Nama Menu</th>
                    <th class="dhead">Qty</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($history as $no => $d)
                    <tr>
                        <td>{{ $no + 1 }}</td>
                        <td>{{ $d->nm_menu }}</td>

                        <td>{{ $d->kredit }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
