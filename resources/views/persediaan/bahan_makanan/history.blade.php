<x-theme.app title="{{ $title }}" table="Y" sizeCard="10">
    <x-slot name="cardHeader">
        <div class="row">
            <div class="col-lg-12">
                @include('persediaan.bahan_makanan.nav')

            </div>
            <div class="col-lg-12">
                <hr>
            </div>
        </div>
        <h6 class="float-start">{{ $title }}</h6>
        <div class="row justify-content-end">
            <div class="col-lg-6">
                <x-theme.btn_filter />
               
            </div>

        </div>

    </x-slot>

    <x-slot name="cardBody">

        <section class="row">
            <div class="col-lg-12">
                <a class="btn btn-primary btn-sm" data-bs-toggle="collapse" href="#masuk" role="button"
                    aria-expanded="true" aria-controls="collapseExample">
                    Stok Masuk / Keluar
                </a>

                <a class="btn btn-primary btn-sm" data-bs-toggle="collapse" href="#opname" role="button"
                    aria-expanded="true" aria-controls="collapseExample">
                    Opname
                </a>
                <hr class="mt-3 mb-3">

                <div class="collapse show" id="masuk" style="">
                    <h6>Stok Masuk & Keluar</h6>
                    <table class="table" id="tblMasuk">
                        <thead>
                            <tr>
                                <th class="dhead">#</th>
                                <th class="dhead">Invoice</th>
                                <th class="dhead">Nama Bahan</th>
                                <th class="dhead">Tgl</th>
                                <th class="dhead text-center">Debit</th>
                                <th class="dhead text-center">Kredit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stokMasuk as $no => $d)
                                <tr>
                                    <td>{{ $no + 1 }}</td>
                                    <td>{{ $d->invoice }}</td>
                                    <td>{{ $d->nm_bahan }}</td>
                                    <td>{{ tanggal($d->tgl) }}</td>
                                    <td align="right">{{ number_format($d->debit, 0) }}</td>
                                    <td align="right">{{ number_format($d->kredit, 0) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>


                <div class="collapse mt-3" id="opname" style="">
                    <h6>Stok Opname</h6>
                    <table class="table" id="tblOpname">
                        <thead>
                            <tr>
                                <th class="dhead">#</th>
                                <th class="dhead">Invoice</th>
                                <th class="dhead">Nama Bahan</th>
                                <th class="dhead">Tgl</th>
                                <th class="dhead">Stok Program</th>
                                <th class="dhead">Stok Aktual</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stokOpname as $no => $d)
                                <tr>
                                    <td>{{ $no + 1 }}</td>
                                    <td>{{ $d->invoice }}</td>
                                    <td>{{ $d->nm_bahan }}</td>
                                    <td>{{ tanggal($d->tgl) }}</td>
                                    <td>{{ number_format($d->program, 0) }}</td>
                                    <td>{{ number_format($d->debit == 0 ? $d->program - $d->kredit : $d->debit + $d->program, 0) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </x-slot>
    @section('scripts')
        <script>
            const tbl = [
                'Masuk', 'Keluar', 'Opname'
            ]
            tbl.forEach(item => {
                $('#tbl' + item).DataTable({
                    "paging": true,
                    "pageLength": 10,
                    "lengthChange": true,
                    "stateSave": true,
                    "searching": true,
                });
            })
        </script>
    @endsection


</x-theme.app>
