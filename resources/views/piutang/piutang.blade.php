<x-theme.app title="{{ $title }}" table="Y" sizeCard="12">
    <x-slot name="cardHeader">
        <div class="col-lg-6">
            <h6 class="float-start mt-1">{{ $title }} {{ tanggal($tgl1) }} ~
                {{ tanggal($tgl2) }}</h6>
        </div>
        @if (!empty($bayar))
            <button class="btn btn-sm icon icon-left btn-primary me-2 float-end btn_bayar"><i
                    class="fas fa-money-bill"></i>
                Bayar</button>
        @endif
        @if (!empty($export))
            <x-theme.button modal="T" href="/piutang/export?tgl1={{ $tgl1 }}&tgl2={{ $tgl2 }}"
                icon="fa-file-excel" addClass="float-end float-end btn btn-success me-2" teks="Export" />
        @endif
        <x-theme.akses :halaman="$halaman" route="piutang.index" />
        <x-theme.btn_filter />

    </x-slot>

    <x-slot name="cardBody">
        <div class="card">
            <div class="card-body px-4 py-4-5">
                <div class="row float-end text-center">

                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                        @php
                            $ttlAllPiutang = 0;
                            
                            foreach ($semuaPiutang as $d) {
                                $ttlAllPiutang += $d->total_rp + $d->debit - $d->kredit;
                            }
                        @endphp
                        <button type="button" class="btn btn-outline-primary btn-md font-extrabold mb-0"> Semua Piutang
                            : Rp. {{ number_format($ttlAllPiutang, 2) }}
                            <br>
                            Piutang Diceklis : Rp. <span class="piutangBayar">0</span>
                        </button>

                    </div>
                </div>

            </div>
        </div>
        <section class="row">
            <table class="table" width="100%" id="tableScroll">
                @php
                    $ttlRp = 0;
                    $ttlTerbayar = 0;
                    $ttlPiutang = 0;
                    
                    foreach ($jual as $d) {
                        $ttlRp += $d->total_rp;
                        $ttlTerbayar += $d->kredit;
                        $ttlPiutang += $d->total_rp + $d->debit - $d->kredit;
                    }
                @endphp
                <thead>
                    <tr>
                        <th width="5">#</th>
                        <th></th>
                        <th>Tanggal</th>
                        <th>No Nota</th>
                        <th>No Penjual</th>
                        <th>Ket</th>
                        <th>Total Rp <br> ({{ number_format($ttlRp, 0) }})</th>
                        <th>Terbayar <br> ({{ number_format($ttlTerbayar, 0) }})</th>
                        <th>Sisa Piutang <br> ({{ number_format($ttlPiutang, 0) }})</th>
                        <th>Status</th>
                        <th>Admin</th>
                        @if (!empty($bayar))
                            <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jual as $no => $d)
                        <tr class="fw-bold induk_detail{{ $d->no_nota }}">
                            <td>{{ $no + 1 }}</td>
                            <td>
                                <a href="#" onclick="event.preventDefault();"
                                    class="detail_bayar detail_bayar{{ $d->no_nota }}"
                                    no_nota="{{ $d->no_nota }}"><i class="fas fa-angle-down"></i></a>

                                <a href="#" onclick="event.preventDefault();"
                                    class="hide_bayar hide_bayar{{ $d->no_nota }}" no_nota="{{ $d->no_nota }}"><i
                                        class="fas fa-angle-up"></i></a>
                            </td>
                            <td>{{ tanggal($d->tgl) }}</td>
                            <td>
                                @if (!$d->kredit)
                                    <a
                                        href="{{ route('piutang.edit', ['no_nota' => $d->no_nota]) }}">{{ $d->no_nota }}</a>
                                @else
                                    {{ $d->no_nota }}
                                @endif
                            </td>
                            <td>{{ $d->no_penjualan }}</td>
                            <td>{{ $d->ket }}</td>
                            <td align="right">Rp. {{ number_format($d->total_rp, 0) }}</td>
                            <td align="right">Rp. {{ number_format($d->kredit, 0) }}</td>
                            <td align="right">Rp. {{ number_format($d->total_rp + $d->debit - $d->kredit, 0) }}</td>
                            <td>{{ $d->status }}</td>
                            <td>{{ $d->admin }}</td>
                            @if (!empty($bayar))
                                
                            <td>
                                @if ($d->status == 'paid')
                                    <i class="fas fa-check text-success"></i>
                                @else
                                    <input type="checkbox" no_nota="{{ $d->no_nota }}"
                                        no_penjualan="{{ $d->no_penjualan }}"
                                        piutang="{{ $d->total_rp + $d->debit - $d->kredit }}"
                                        class="form-check-glow form-check-input form-check-primary cek_bayar" />
                                @endif
                            </td>
                            @endif

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    </x-slot>

    @section('scripts')
        <script>
            $('.hide_bayar').hide();
            $(document).on("click", ".detail_bayar", function() {
                var no_nota = $(this).attr('no_nota');
                var clickedElement = $(this);

                clickedElement.prop('disabled', true);
                $.ajax({
                    type: "get",
                    url: "/piutang/get_kredit_pi?no_nota=" + no_nota,
                    success: function(data) {

                        $('.induk_detail' + no_nota).after("<tr>" + data + "</tr>");
                        $(".show_detail" + no_nota).show();
                        $(".detail_bayar" + no_nota).hide();
                        $(".hide_bayar" + no_nota).show();

                        clickedElement.prop('disabled', false);
                    },
                    error: function() {
                        clickedElement.prop('disabled',
                            false
                        ); // Jika ada kesalahan dalam permintaan AJAX, pastikan elemen yang diklik diaktifkan kembali
                    }
                });

            });
            $(document).on("click", ".hide_bayar", function() {
                var no_nota = $(this).attr('no_nota');
                $(".show_detail" + no_nota).remove();
                $(".detail_bayar" + no_nota).show();
                $(".hide_bayar" + no_nota).hide();

            });

            $(".btn_bayar").hide();
            $(".piutang_cek").hide();
            $(document).on('change', '.cek_bayar', function() {
                var totalPiutang = 0
                $('.cek_bayar:checked').each(function() {
                    var piutang = $(this).attr('piutang');
                    totalPiutang += parseInt(piutang);
                });
                var anyChecked = $('.cek_bayar:checked').length > 0;
                $('.btn_bayar').toggle(anyChecked);
                $(".piutang_cek").toggle(anyChecked);
                $('.piutangBayar').text(totalPiutang.toLocaleString('en-US'));
            })

            $(document).on('click', '.btn_bayar', function() {
                var dipilih = [];
                $('.cek_bayar:checked').each(function() {
                    var no_nota = $(this).attr('no_nota');
                    dipilih.push(no_nota);

                });
                var params = new URLSearchParams();

                dipilih.forEach(function(orderNumber) {
                    params.append('no_order', orderNumber);
                });
                var queryString = 'no_order[]=' + dipilih.join('&no_order[]=');
                window.location.href = "/piutang/bayar?" + queryString;

            })

            $(document).on('click', '.bayar_nota', function() {
                var no_nota = $(this).attr('no_nota')
                $("#no_nota").val(no_nota);
            })

            $(document).on('click', '.delete_nota', function() {
                var no_nota = $(this).attr('no_nota')
                $(".no_nota_delete").val(no_nota);
            })
        </script>
    @endsection
</x-theme.app>
