<x-theme.app title="{{$title}}" table="Y" sizeCard="12">
    <x-slot name="cardHeader">
        <div class="row justify-content-end">
            <div class="col-lg-6">
                <a href="{{ route('summary_buku_besar.export_detail',['id_akun' => $id_akun,'tgl1' => $tgl1,'tgl2' => $tgl2]) }}"
                    class="float-end btn   btn-success me-2"><i class="fas fa-file-excel"></i> Export</a>
            </div>
        </div>
    </x-slot>
    <x-slot name="cardBody">
        <section class="row">
            <table class="table table-hover table-striped" id="table1">
                <thead>
                    <tr>
                        <th width="5">#</th>
                        <th>Tanggal</th>
                        <th>No Nota</th>
                        <th>Akun</th>
                        <th>Keterangan</th>
                        <th style="text-align: right">Debit</th>
                        <th style="text-align: right">Kredit</th>
                        <th style="text-align: right">Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $saldo = 0;
                    @endphp
                    @foreach ($detail as $n => $d)
                    @php
                    $saldo += $d->debit - $d->kredit;


                    @endphp
                    <tr>
                        <td>{{ $n+1 }}</td>
                        <td class="nowrap">{{ date('d-m-Y',strtotime($d->tgl)) }}</td>
                        <td>{{ $d->no_nota }}</td>
                        <td>{{ $d->saldo == 'Y' ? 'Saldo Awal' : ucwords(strtolower($d->nm_akun)) }}</td>
                        <td>{{ $d->ket }}</td>
                        <td style="text-align: right">{{ number_format($d->debit,2) }}</td>
                        <td style="text-align: right">{{ number_format($d->kredit,2) }}</td>
                        <td style="text-align: right">{{ number_format($saldo,2) }}</td>
                    </tr>
                    @endforeach
                </tbody>

            </table>
        </section>

    </x-slot>

</x-theme.app>