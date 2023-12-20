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

            </div>

        </div>

    </x-slot>

    <x-slot name="cardBody">
        <form action="{{ route('bahan.save_opname') }}" method="post">
            @csrf

            <section class="row">
                <div class="col-lg-12">
                    <button type="submit" class="mb-2 btn btn-sm btn-primary float-end"><i
                            class="fas fa-save"></i>Opname</button>
                </div>
                <div class="col-lg-12">

                    <table class="table" id="tbScroll" x-data="{}">
                        <thead>
                            <tr>
                                <th width="5">#</th>
                                <th>Nama Bahan</th>
                                <th width="100" class="text-end">Stok Program</th>
                                <th width="100" class="text-end">Stok Aktual</th>
                                <th>Kategori</th>
                                <th>Satuan</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($bahan as $i => $d)
                                <tr>
                                    <input type="hidden" name="id_bahan[]" value="{{ $d->id_list_bahan }}">
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $d->nm_bahan }}</td>
                                    <td align="right">0</td>
                                  
                                    <td>
                                        <input type="hidden" name="stok_program[]" value="{{ $d->stok }}">
                                        <input x-mask:dynamic="$money($input)" value="{{ $d->stok }}" type="text"
                                            name="stok_aktual[]" class="form-control text-end inputStok">
                                    </td>
                                    <td>{{ $d->nm_kategori }}</td>
                                    <td>{{ $d->nm_satuan }}</td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
        </form>

        @section('scripts')
            <script>
                $(document).ready(function() {
                    $('.inputStok').on('focus', function() {
                        // Pilih teks di dalam input
                        this.select();
                    });
                    $('#tbScroll').DataTable({
                        "paging": false,
                        "pageLength": 100,
                        "scrollY": "100%",
                        "lengthChange": false,
                        // "ordering": false,
                        "info": false,
                        "stateSave": true,
                        "autoWidth": true,
                        // "order": [ 5, 'DESC' ],
                        "searching": true,
                    });
                });
            </script>
        @endsection
    </x-slot>


</x-theme.app>
