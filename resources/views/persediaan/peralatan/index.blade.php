<x-theme.app title="{{ $title }}" table="Y" sizeCard="12">
    <x-slot name="cardHeader">
        <h6>{{ $title }}</h6>
        <div class="row justify-content-end">
            <div class="col-lg-6">

                <x-theme.button modal="T" href="#" icon="fa-plus" addClass="float-end" teks="Buat Baru" />


            </div>
        </div>

    </x-slot>
    <x-slot name="cardBody">
        <section class="row">
            <table class="table table-hover" id="table1">
                <thead>
                    <tr>
                        <th width="5">#</th>
                        <th>Tanggal Perolehan</th>
                        <th>Nama</th>
                        <th>Kelompok</th>
                        <th class="text-end">Nilai Perolehan</th>
                        <th class="text-end">Penysutan Perbulan</th>
                        <th class="text-end">Akumulasi Penyusutan</th>
                        <th class="text-end">Nilai Buku</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($peralatan as $no => $a)
                        <tr>
                            <td>{{ $no + 1 }}</td>
                            <td>{{ date('d-m-Y', strtotime($a->tgl)) }}</td>
                            <td>{{ $a->nm_aktiva }}</td>
                            <td>{{ $a->nm_kelompok }}</td>
                            <td align="right">Rp {{ number_format($a->h_perolehan, 0) }}</td>
                            <td align="right">Rp {{ number_format($a->biaya_depresiasi, 0) }}</td>
                            <td align="right">Rp {{ number_format($a->beban, 0) }}</td>
                            <td align="right">Rp {{ number_format($a->h_perolehan - $a->beban, 0) }}</td>

                        </tr>
                    @endforeach
                </tbody>

            </table>
        </section>

    </x-slot>
    @section('scripts')
    @endsection
</x-theme.app>
