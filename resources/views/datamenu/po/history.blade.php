<x-theme.app title="{{ $title }}" table="Y" sizeCard="9">
    <x-slot name="cardHeader">
        <div class="row">
            <div class="col-lg-12">
                @include('datamenu.po.nav', ['tgl1' => $tgl1, 'tgl2' => $tgl2])
            </div>
            <div class="col-lg-12">
                <hr>
            </div>
        </div>
        <h6 class="float-start">{{ $title }}</h6>
   

    </x-slot>


    <x-slot name="cardBody">
        <section class="row">
            <div class="col">
            <table class="table" id="table1">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tgl Transaksi</th>
                        <th>Nama Transaksi</th>
                        <th>Akun Pembayaran</th>
                        <th>Suplier</th>
                        <th class="text-end">Total Rp</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaksi as $i => $d)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ tanggal($d->tgl_transaksi) }}</td>
                        <td><a target="_blank" href="{{ route('po.transaksi_print', $d->no_nota) }}">{{ ucwords($d->nm_transaksi) }}: {{ ucwords($d->nm_suplier) }}</a></td>
                        <td>{{ strtoupper($d->nm_akun) }}</td>
                        <td>{{ $d->nm_suplier }}</td>
                        <td align="right">{{ number_format($d->jumlah,0) }}</td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
        </section>

    </x-slot>
</x-theme.app>
