<x-theme.app title="{{ $title }}" table="Y" sizeCard="12">
    <x-slot name="cardHeader">
        <div class="col-lg-6">
            <h5 class="float-start mt-1">{{ $title }} {{ date('d-m-Y', strtotime($tgl1)) }} ~
                {{ date('d-m-Y', strtotime($tgl2)) }}</h5>
        </div>

        <x-theme.button modal="T" href="#" icon="fa-money-bill" addClass="float-end btn_bayar" teks="Bayar" />
        <x-theme.button modal="T" href="{{ route('jual.add') }}" icon="fa-plus" addClass="float-end"
            teks="Buat Baru" />
        <x-theme.button modal="T" href="/jual/export?tgl1={{ $tgl1 }}&tgl2={{ $tgl2 }}"
            icon="fa-file-excel" addClass="float-end float-end btn btn-success me-2" teks="Export" />
        <x-theme.btn_filter />
    </x-slot>

    <x-slot name="cardBody">
        <section class="row">
            <table class="table" width="100%" id="tableScroll">
                <thead>
                    <tr>
                        <th width="5">#</th>
                        <th></th>
                        <th>Tanggal</th>
                        <th>No Nota</th>
                        <th>No Penjual</th>
                        <th>Keterangan</th>
                        <th>Total Rp</th>
                        <th>Terbayar</th>
                        <th>Sisa Piutang</th>
                        <th>Status</th>
                        <th>Aksi</th>
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
                            <td>{{ date('d-m-Y', strtotime($d->tgl)) }}</td>
                            <td>
                                @if (!$d->kredit)
                                    <a
                                        href="{{ route('jual.edit', ['no_nota' => $d->no_nota]) }}">{{ $d->no_nota }}</a>
                                @else
                                    <p>{{ $d->no_nota }}</p>
                                @endif
                            </td>
                            <td>{{ $d->no_penjualan }}</td>
                            <td>{{ $d->ket }}</td>
                            <td align="right">Rp. {{ number_format($d->total_rp, 0) }}</td>
                            <td align="right">Rp. {{ number_format($d->kredit, 0) }}</td>
                            <td align="right">Rp. {{ number_format($d->total_rp + $d->debit - $d->kredit, 0) }}</td>
                            <td>{{ $d->status }}</td>
                            <td>
                                @if ($d->status == 'paid')
                                    <i class="fas fa-check text-success"></i>
                                @else
                                    <input type="checkbox" no_nota="{{ $d->no_nota }}"
                                        no_penjualan="{{ $d->no_penjualan }}"
                                        class="form-check-glow form-check-input form-check-primary cek_bayar" />
                                @endif
                            </td>

                        </tr>
                    @endforeach

                </tbody>
            </table>
        </section>
        <form action="{{ route('jual.piutang') }}" method="post">
            @csrf
            <x-theme.modal title="Penagihan Penjualan" idModal="jual">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="">Tanggal</label>
                            <input value="{{ date('Y-m-d') }}" type="date" name="tgl" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="">No Penjualan</label>
                            <input type="text" name="no_penjualan" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="">Total Rp</label>
                            <input type="text" name="total_rp" class="form-control">
                        </div>
                    </div>
                </div>
            </x-theme.modal>
        </form>
        <form action="{{ route('jual.create') }}" method="post">
            @csrf
            <x-theme.modal title="Terima Pembayaran" idModal="tambah">
                <div class="row">
                    <input type="text" id="no_nota" name="no_nota">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="">Tanggal</label>
                            <input value="{{ date('Y-m-d') }}" type="date" name="tgl" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="">Total Rp</label>
                            <input type="text" name="total_rp" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="">Setor Ke</label>
                            <select name="setor" class="form-control select2" id="">
                                <option value="">- Pilih Akun -</option>
                                @foreach ($akun as $d)
                                    <option value="{{ $d->id_akun }}">{{ ucwords($d->nm_akun) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </x-theme.modal>
        </form>

        <form action="{{ route('jual.delete') }}" method="get">
            <div class="modal fade" id="delete" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="row">
                                <h5 class="text-danger ms-4 mt-4"><i class="fas fa-trash"></i> Hapus Data</h5>
                                <p class=" ms-4 mt-4">Apa anda yakin ingin menghapus ?</p>
                                <input type="hidden" class="no_nota_delete" name="no_nota">
                                <input type="hidden" name="tgl1" value="{{ $tgl1 }}">
                                <input type="hidden" name="tgl2" value="{{ $tgl2 }}">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger"
                                data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
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
                    url: "/jual/get_kredit_pi?no_nota=" + no_nota,
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
            $(document).on('change', '.cek_bayar', function() {
                var anyChecked = $('.cek_bayar:checked').length > 0;
                $('.btn_bayar').toggle(anyChecked);
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
                window.location.href = "/jual/bayar?" + queryString;

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
