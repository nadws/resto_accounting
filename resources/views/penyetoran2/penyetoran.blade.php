<x-theme.app title="{{ $title }}" table="Y" sizeCard="12">
    <x-slot name="cardHeader">
        <div class="col-lg-6">
            <h6 class="float-start mt-1">{{ $title }} {{ tanggal($tgl1) }} ~
                {{ tanggal($tgl2) }}</h6>
        </div>
        <button class="btn btn-sm icon icon-left btn-primary me-2 float-end btn_bayar"><i class="fas fa-money-bill"></i>
            Setor</button>
        <x-theme.button modal="T" href="/penyetoran/export?tgl1={{ $tgl1 }}&tgl2={{ $tgl2 }}"
            icon="fa-file-excel" addClass="float-end float-end btn btn-success me-2" teks="Export" />
        <x-theme.button modal="Y" href="#" idModal="perencanaan" icon="fa-list" addClass="float-end"
            teks="List Perencanaan" />
        <x-theme.button modal="Y" href="#" idModal="history" icon="fa-history" addClass="float-end"
            teks="History Penyetoran" />
        <x-theme.btn_filter />

    </x-slot>

    <x-slot name="cardBody">
        <div class="card">
            <div class="card-body px-4 py-4-5">
                <div class="row float-end text-center">

                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                        @php
                            $ttlAllPiutang = 0;
                            
                            // foreach ($semuaPiutang as $d) {
                            //     $ttlAllPiutang += $d->total_rp + $d->debit - $d->kredit;
                            // }
                            
                        @endphp
                        <button type="button" class="btn btn-outline-primary btn-md font-extrabold mb-0">
                            Total Setor : Rp. <span class="piutangBayar">0</span>
                        </button>

                    </div>
                </div>

            </div>
        </div>
        <section class="row">
            <table class="table" width="100%" id="tableScroll">
                @php
                    $ttlRp = 0;
                    
                    foreach ($setor as $d) {
                        $ttlRp += $d->debit;
                    }
                @endphp
                <thead>
                    <tr>
                        <th width="5">#</th>
                        <th width="15%">Tanggal</th>
                        <th width="15%">No Nota</th>
                        <th>Pembayaran</th>
                        <th>Keterangan</th>
                        <th class="text-end">Debit <br> (Rp. {{ number_format($ttlRp, 0) }})</th>
                        <th width="5%">Admin</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($setor as $no => $d)
                        @if ($d->id_akun == 12)
                            @continue
                        @endif
                        <tr class="row-clickable">
                            <td>{{ $no + 1 }}</td>
                            <td>{{ tanggal($d->tgl) }}</td>
                            <td>{{ $d->no_nota }}</td>
                            <td>{{ $d->nm_akun }}</td>
                            <td>{{ $d->ket }}</td>
                            <td align="right">Rp. {{ number_format($d->debit, 0) }}</td>
                            <td>{{ ucwords($d->admin) }}</td>
                            <td align="center">

                                <input type="checkbox" no_nota="{{ $d->id_jurnal }}" no_penjualan="1"
                                    piutang="{{ $d->debit }}"
                                    class="form-check-glow form-check-input form-check-primary cek_bayar" />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <x-theme.modal btnSave="" title="History Penyetoran" size="modal-lg" idModal="history">
                <div class="row">
                    <div class="col-lg-12">
                        <div id="load-history"></div>
                    </div>
                </div>
            </x-theme.modal>

            <x-theme.modal btnSave="" title="Perencanaan" size="modal-lg" idModal="perencanaan">
                <div class="row">
                    <div class="col-lg-12">
                        <div id="load_perencanaan"></div>
                    </div>
                </div>
            </x-theme.modal>

            <form action="{{ route('penyetoran.save_setor') }}" method="post">
                @csrf
                <x-theme.modal size="modal-lg" title="Save Penyetoran" idModal="edit">
                    <div id="load-edit"></div>
                </x-theme.modal>
            </form>

            <form action="{{ route('penyetoran.hapus_setor') }}" method="post">
                @csrf
                <x-theme.modal size="modal-lg" title="Delete Penyetoran" idModal="kembali">
                    <div id="load-kembali"></div>
                </x-theme.modal>
            </form>
        </section>
    </x-slot>

    @section('scripts')
        <script>
            $.ajax({
                type: "GET",
                url: "{{ route('penyetoran.load_perencanaan') }}",
                success: function(r) {
                    $("#load_perencanaan").html(r);
                    edit('edit', 'id_produk', 'penyetoran/edit', 'load-edit')
                }
            });

            $.ajax({
                type: "GET",
                url: "{{ route('penyetoran.load_history') }}",
                success: function(r) {
                    $("#load-history").html(r);
                    edit('kembali', 'id_produk', 'penyetoran/kembali', 'load-kembali')
                }
            });

            $(document).on('click', '.edit', function() {
                $("#edit").modal('show')
            })

            $(document).on('click', '.kembali', function() {
                $("#kembali").modal('show')
            })

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
                window.location.href = "/penyetoran/perencanaan?" + queryString;

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
