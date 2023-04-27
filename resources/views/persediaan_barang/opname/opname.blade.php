<x-theme.app title="{{ $title }}" table="Y" sizeCard="12">

    <x-slot name="cardHeader">
        <form action="{{ route('opname.save') }}" method="post">
            @csrf
            <button type="submit" class="btn btn-primary float-end"> <i class="fas fa-save"></i> Save Opname</button>

            <div class="row justify-content-end">
                <div class="col-lg-4">
                    <select name="" class="form-control select-gudang" id="select2">
                        <option value="" selected>All Warehouse </option>
                        @foreach ($gudang as $g)
                            <option {{ Request::segment(2) == $g->id_gudang ? 'selected' : '' }}
                                value="{{ $g->id_gudang }}">
                                {{ ucwords($g->nm_gudang) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
    </x-slot>
    <x-slot name="cardBody">

        <section class="row">
            <table class="table table-hover">
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
                            <td>{{ ucwords($d->nm_produk) }} ({{ strtoupper($d->nm_satuan) }})</td>
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
                            <td align="right">{{ number_format($stk, 0) }}</td>
                            <td>
                                <input name="id_produk[]" type="hidden" value="{{ $d->id_produk }}">
                                <input name="buku[]" type="hidden" value="{{ $stk ?? 0 }}">
                                <input name="gudang_id[]" type="hidden" value="{{ $d->gudang_id }}">
                                <input name="fisik[]" value="{{ $stk ?? 0 }}" style="text-align: right"
                                    stk="{{ $stk ?? 0 }}" type="text" class="form-control fisik"
                                    count="{{ $no + 1 }}">
                            </td>
                            <td>
                                <input name="selisih[]" value="0" type="text" readonly style="text-align: right"
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
            $(".select-gudang").change(function(e) {
                e.preventDefault();
                var gudang_id = $(this).val()
                document.location.href = `/opname/${gudang_id}`
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
