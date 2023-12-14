<x-theme.app title="{{ $title }}" table="Y" sizeCard="10">
    <x-slot name="cardHeader">
        <div class="row">
            <div class="col-lg-12">
                @include('persediaan.atk.nav')

            </div>
            <div class="col-lg-12">
                <hr>
            </div>
        </div>
        <h6 class="float-start mt-1">Atk {{ $title }}
        </h6>
        <div class="row justify-content-end">
            <div class="col-lg-6">
                <x-theme.button href="{{ route('atk.tmbh_stok') }}" icon="fa-plus" addClass="float-end"
                    teks="Buat Baru" />
            </div>
        </div>



    </x-slot>
    <x-slot name="cardBody">

        <section class="row">
            <table class="table table-hover" id="table1">
                <thead>
                    <tr>
                        <th width="2">#</th>
                        <th class="text-center">Tanggal</th>
                        <th>No Nota</th>
                        <th class="text-center">Jumlah Barang</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoice as $no => $d)
                        <tr>
                            <td>{{ $no + 1 }}</td>
                            <td align="center">{{ tanggal($d->tgl) }}</td>
                            <td>{{ $d->invoice }}</td>
                            <td align="center">{{ $d->stok }}</td>
                            <td>
                                <div class="btn btn-sm btn-success">
                                    Selesai
                                </div>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </section>




    </x-slot>


</x-theme.app>
