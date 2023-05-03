<x-theme.app title="{{ $title }}" table="Y" sizeCard="12">

    <x-slot name="cardHeader">
        <form action="{{ route('opname.update') }}" method="post">
            @csrf
            <div class="btn-group float-end dropdown me-1 mb-1">
                <input type="hidden" name="no_nota" value="{{ $no_nota }}">
                <button type="submit" name="simpan" value="simpan" class=" btn btn-primary button-save">
                    Simpan
                </button>
                <button class="float-end btn btn-primary btn_save_loading" type="button" disabled hidden>
                    <span class="spinner-border spinner-border-sm " role="status" aria-hidden="true"></span>
                    Loading...
                </button>
                <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true" data-reference="parent">
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <div class="dropdown-menu "
                    style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate3d(81px, 40px, 0px);"
                    data-popper-placement="bottom-start">
                    <button class="dropdown-item" type="submit" value="draft" name="simpan">Draft</button>
                    <button class="dropdown-item" type="submit" value="simpan" name="simpan">Simpan</button>
                </div>
            </div>
            {{-- <button type="submit" class="btn btn-primary float-end"> <i class="fas fa-save"></i> Save Opname</button> --}}

            <div class="row justify-content-end">

                <div class="col-lg-4">
                    <select name="" class="form-control select-gudang" id="select2">
                        <option value="" selected>All Warehouse </option>
                        @foreach ($gudang as $g)
                            <option {{ Request::segment(3) == $g->id_gudang ? 'selected' : '' }}
                                value="{{ $g->id_gudang }}">
                                {{ ucwords($g->nm_gudang) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mt-3">
               
                <div class="col-lg-3">
                    <input type="text" autofocus class="form-control mb-3" id="searchOpname" placeholder="search...">
                </div>
            </div>
       
    </x-slot>
    <x-slot name="cardBody">

        <section class="row">
            <table class="table table-hover" id="tblOpname">
                <thead>
                    <tr>
                        <th width="3%">No</th>
                        <th width="20%">Produk (Satuan)</th>
                        <th width="6%" style="text-align: right">Tersedia (Program)</th>
                        <th width="4%" style="text-align: right">Tersedia (Fisik)</th>
                        <th width="6%" style="text-align: right">Selisih</th>
                        <th class="text-center" width="8%">Countdown</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($produk as $no => $d)
                        <tr>
                            <td>{{ $no + 1 }}</td>
                            <td>{{ ucwords($d->produk->nm_produk) }} ({{ strtoupper($d->produk->satuan->nm_satuan) }})</td>
                            @php
                                $debit = $d->debit ?? 0;
                                $kredit = $d->kredit ?? 0;
                                $stk = $debit - $kredit;
                                $tgl1 = date('Y-m-d');
                                $tgl2 = date('Y-m-d', strtotime('30 days', strtotime($d->tgl1)));
                                
                                $tKerja = '0';
                                if (!empty($d->tgl1)) {
                                    $totalKerja = new DateTime($tgl1);
                                    $today = new DateTime($tgl2);
                                    $tKerja = $today->diff($totalKerja);
                                }
                            @endphp
                            <td align="right">{{ number_format($d->jml_sebelumnya, 0) }}</td>
                            <td>
                                <input name="id_produk[]" type="hidden" value="{{ $d->id_produk }}">
                                <input name="buku[]" type="hidden" value="{{ $d->jml_sebelumnya }}">
                                <input name="gudang_id[]" type="hidden" value="{{ $d->gudang_id }}">
                                <input name="fisik[]" value="{{ $d->jml_sesudahnya }}" style="text-align: right"
                                    stk="{{ $d->jml_sebelumnya }}" type="text" class="form-control fisik"
                                    count="{{ $no + 1 }}">
                            </td>
                            <td>
                                <input name="selisih[]" value="{{$d->selisih}}" type="text" readonly style="text-align: right"
                                    class="form-control selisih{{ $no + 1 }}" count="{{ $no + 1 }}">
                            </td>
                            <td align="center">{{ $tKerja == '0' ? ' - ' : $tKerja->days . ' Hari' }} </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </section>
        </form>
    </x-slot>

    @section('scripts')
        <script>
            pencarian('searchOpname', 'tblOpname')

            $(".select-gudang").change(function(e) {
                e.preventDefault();
                var gudang_id = $(this).val()
                document.location.href = `/opname/add/${gudang_id}`
            });

            var count = 1
            $(document).on('keyup', '.fisik', function() {
                var val = $(this).val()
                var count = $(this).attr('count')
                var stk = $(this).attr('stk')

                $(".selisih" + count).val(val - stk);
            })
        </script>
    @endsection
</x-theme.app>
