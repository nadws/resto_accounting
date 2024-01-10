<x-theme.app title="{{ $title }}" table="Y" sizeCard="8">
    <x-slot name="cardHeader">
        <div class="row">
            <div class="col-lg-12">
                @include('datamenu.penjualan.nav', ['tgl1' => $tgl1, 'tgl2' => $tgl2])
            </div>
            <div class="col-lg-12">
                <hr>
            </div>
        </div>
        <h6 class="float-start">{{ $title }}</h6>
   

    </x-slot>

    <x-slot name="cardBody">
        <section class="row">
            <div class="col-lg-12">
                <table class="table table-striped" id="table1">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tanggal </th>
                            <th>Station</th>
                            <th>Nama Menu</th>
                            <th>Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($datas as $no => $d)
                            <tr>
                                <td>{{ $no + 1 }}</td>
                                <td>{{ tanggal($d->tgl) }}</td>
                                <td>{{ $d->nm_station }}</td>
                                <td>{{ $d->nm_menu }}</td>
                                <td>{{ $d->qty }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
        @section('scripts')
        @endsection
    </x-slot>


</x-theme.app>
