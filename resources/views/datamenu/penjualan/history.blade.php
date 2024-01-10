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
        <h6 class="float-start">{{ $title }} <br> {{ tanggalRange($tgl1, $tgl2) }}</h6>
        <h6></h6>


    </x-slot>

    <x-slot name="cardBody">
        <section class="row">
            <div class="col-lg-12">
                <table class="table table-striped table-hover" id="table1">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tanggal</th>
                            <th>Nama Bahan</th>
                            <th class="text-center">Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($history as $no => $d)
                            <tr class="detail" tgl="{{ $d->tgl }}" id_bahan="{{ $d->id_bahan }}">
                                <td>{{ $no + 1 }}</td>
                                <td>{{ tanggal($d->tgl) }}</td>
                                <td>
                                    <a href="#">{{ $d->nm_bahan }}</a>
                                </td>
                                <td align="right">{{ number_format($d->kredit, 0) . ' ' . strtoupper($d->nm_satuan) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>


        <x-theme.modal idModal="detail" title="Detail Bahan" btnSave="T">
            <div id="load_detail"></div>
        </x-theme.modal>

        @section('scripts')
            <script>
                $(document).on('click', '.detail', function(e) {
                    e.preventDefault();
                    const id_bahan = $(this).attr('id_bahan')
                    const tgl = $(this).attr('tgl')
                    $('#detail').modal('show')
                    $.ajax({
                        type: "GET",
                        url: "{{ route('penjualan.detail') }}",
                        data: {
                            id_bahan: id_bahan,
                            tgl: tgl,
                        },
                        success: function(r) {
                            $("#load_detail").html(r);

                        }
                    });
                })
            </script>
        @endsection
    </x-slot>


</x-theme.app>
