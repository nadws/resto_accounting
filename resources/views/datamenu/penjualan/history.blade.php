<x-theme.app title="{{ $title }}" table="Y" sizeCard="8">
    <x-slot name="cardHeader">
        <div class="row">
            <div class="col-lg-12">
                @include('datamenu.penjualan.nav')
            </div>
            <div class="col-lg-12">
                <hr>
            </div>
        </div>
        <h6 class="float-start">{{ $title }} <br> {{ tanggalRange($tgl1, $tgl2) }}</h6>
        <h6></h6>
        <div class="row justify-content-end">
            <div class="col-lg-12">
                <x-theme.button modal="Y" idModal="view" icon="fa-calendar-week" addClass="float-end"
                    teks="Filter" />
                <form action="" method="GET">
                    <x-theme.modal idModal="view" title="View">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="">dari</label>
                                    <input type="date" name="tgl1"
                                        value="{{ date('Y-m-d', strtotime('-1 days')) }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="">Sampai</label>
                                    <input type="date" name="tgl2"
                                        value="{{ date('Y-m-d', strtotime('-1 days')) }}" class="form-control">
                                </div>
                            </div>
                        </div>
                    </x-theme.modal>
                </form>
            </div>

        </div>

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
                                <td align="right">{{ number_format($d->kredit, 0) . ' ' . strtoupper($d->nm_satuan) }}</td>
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
