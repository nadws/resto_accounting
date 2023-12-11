<x-theme.app title="{{ $title }}" table="Y" sizeCard="8">
    <x-slot name="cardHeader">
        <div class="row justify-content-end">
            <div class="col-lg-6">
                <h6 class="float-start mt-1">{{ $title }}: {{ tanggal($tgl1) }}~{{ tanggal($tgl2) }}</h6>
            </div>
            <div class="col-lg-6">
                <x-theme.btn_filter />
            </div>
        </div>
    </x-slot>
    <x-slot name="cardBody">
        <section class="row">
            @php
                $ttlDebit = 0;
                $ttlKredit = 0;
                $ttlSaldo = 0;

                foreach ($buku as $d) {
                    $ttlDebit += $d->debit;
                    $ttlKredit += $d->kredit;
                    $ttlSaldo += $d->debit - $d->kredit;
                }
            @endphp
            <table class="table table-hover table-striped" id="table1">
                <thead>
                    <tr>
                        <th width="5">#</th>
                        <th>Kode Akun</th>
                        <th>Akun</th>
                        <th style="text-align: right">Debit ({{ number_format($ttlDebit, 2) }})</th>
                        <th style="text-align: right">Kredit ({{ number_format($ttlKredit, 2) }})</th>
                        <th style="text-align: right">Saldo ({{ number_format($ttlSaldo, 2) }})</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $sldo = 0;
                    @endphp
                    @foreach ($buku as $no => $a)
                        @php
                            $sldo += $a->debit - $a->kredit;
                        @endphp
                        <tr>
                            <td>{{ $no + 1 }}</td>
                            <td>{{ $a->kode_akun }}</td>
                            <td>{{ ucwords(strtolower($a->nm_akun)) }}
                            </td>
                            <td style="text-align: right">{{ number_format($a->debit, 2) }}</td>
                            <td style="text-align: right">{{ number_format($a->kredit, 2) }}</td>
                            <td style="text-align: right">
                                {{ number_format($a->debit - $a->kredit, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>


    </x-slot>
    @section('scripts')
        <script>
            // Menangani event klik pada setiap baris dan mengarahkan pengguna ke URL yang sesuai
            document.querySelectorAll('tbody .tbl').forEach(function(row) {
                row.addEventListener('click', function() {
                    window.location.href = row.getAttribute('data-href');
                });
            });
        </script>
    @endsection

</x-theme.app>
