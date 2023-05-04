<x-theme.app title="{{ $title }} : {{date('d-m-Y',strtotime($tgl1))}} ~ {{date('d-m-Y',strtotime($tgl2))}}" table="Y"
    sizeCard="12">
    <x-slot name="cardHeader">
        <div class="row justify-content-end">
            <div class="col-lg-6">
                {{-- <a href="{{ route('export_jurnal', ['tgl1' => $tgl1, 'tgl2' => $tgl2]) }}"
                    class="float-end btn   btn-success me-2"><i class="fas fa-file-excel"></i> Export</a> --}}
                <a target="_blank" href="{{ route('profit_print', ['tgl1' => $tgl1, 'tgl2' => $tgl2]) }}"
                    class="float-end btn   btn-primary me-2"><i class="fas fa-print"></i> Print</a>
                <x-theme.button modal="Y" idModal="view" icon="fa-filter" addClass="float-end" teks="" />
            </div>
        </div>
    </x-slot>
    <x-slot name="cardBody">
        <section class="row">
            <table class="table table-bordered">

                <tr>
                    <th colspan="4">Uraian</th>
                </tr>
                <tr>
                    <th colspan="4">PEREDARAN USAHA</th>
                </tr>
                @php
                $total_pendapatan = 0;
                @endphp
                @foreach ($profit as $p)
                @php
                $total_pendapatan += $p->kredit - $p->debit;
                @endphp
                <tr>
                    <td></td>
                    <td>{{ ucwords(strtolower($p->nm_akun))}}</td>
                    <td width="5%">Rp</td>
                    <td align="right">{{number_format($p->kredit - $p->debit,0)}}</td>
                </tr>
                @endforeach
                <tr>
                    <td style="border-bottom: 1px solid black;"></td>
                    <td class="fw-bold" style="border-bottom: 1px solid black;">Total Pendapatan</td>
                    <td class="fw-bold" style="border-bottom: 1px solid black;">Rp</td>
                    <td class="fw-bold" align="right" style="border-bottom: 1px solid black;">
                        {{number_format($total_pendapatan,0)}}</td>
                </tr>
                <tr>
                    <th colspan="4">BIAYA - BIAYA</th>
                </tr>
                @php
                $total_biaya = 0;
                @endphp
                @foreach ($loss as $l)
                @php
                $total_biaya += $l->debit - $l->kredit;
                @endphp
                <tr>
                    <td></td>
                    <td>{{ ucwords(strtolower($l->nm_akun))}}</td>
                    <td width="5%">Rp</td>
                    <td align="right">{{number_format($l->debit - $l->kredit,0)}}</td>
                </tr>
                @endforeach
                <tr>
                    <td style="border-bottom: 1px solid black;"></td>
                    <td class="fw-bold" style="border-bottom: 1px solid black;">Total Biaya-biaya</td>
                    <td class="fw-bold" style="border-bottom: 1px solid black;">Rp</td>
                    <td class="fw-bold" align="right" style="border-bottom: 1px solid black;">
                        {{number_format($total_biaya,0)}}</td>
                </tr>
                <tr>
                    <td colspan="2" class="fw-bold">TOTAL LABA BERSIH</td>
                    <td class="fw-bold">Rp</td>
                    <td class="fw-bold" align="right">{{number_format( $total_pendapatan - $total_biaya,0)}}</td>
                </tr>

                <tbody>

                </tbody>
            </table>
        </section>


        <form action="" method="get">
            <x-theme.modal title="Filter Profit & Loss" idModal="view">
                <div class="row">
                    <div class="col-lg-12">

                        <table width="100%" cellpadding="10px">
                            <tr>
                                <td>Tanggal</td>
                                <td>
                                    <label for="">Dari</label>
                                    <input type="date" name="tgl1" class="form-control">
                                </td>
                                <td>
                                    <label for="">Sampai</label>
                                    <input type="date" name="tgl2" class="form-control">
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

            </x-theme.modal>
        </form>
    </x-slot>
    @section('scripts')
    <script>
        $(document).ready(function() {
                
        });
    </script>
    @endsection
</x-theme.app>