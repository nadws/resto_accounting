<x-theme.app title="{{ $title }}" table="Y" sizeCard="9">
    <x-slot name="cardHeader">
        <div class="row">
            <div class="col-lg-4 col-4">
                <h5 class="float-start">Cashflow</h5>
            </div>
            <div class="col-lg-4 col-8">
                <button class="btn rounded-pill btn-outline-primary btn-block">
                    <span style="font-size: 25px">Rp. {{ number_format($sisa, 0) }}</span>
                </button>
            </div>
            <div class="col-lg-4 col-12">
                <a class="me-2 btn btn-primary btn-sm float-end" href="{{ route('cashflow.add') }}"><i
                        class="fas fa-plus"></i> Tambah</a>
                <x-theme.btn_filter />
            </div>
        </div>

    </x-slot>
    <x-slot name="cardBody">
        <section class="row">
            <table class="table stripped" id="table1">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th class="text-end">Debit ({{ number_format($ttlDebit, 0) }})</th>
                        <th class="text-end">Kredit ({{ number_format($ttlKredit, 0) }})</th>
                        <th>Keterangan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($datas as $no => $d)
                        @php
                            $debit = (int) Crypt::decrypt($d->debit);
                            $kredit = (int) Crypt::decrypt($d->kredit);
                        @endphp
                        <tr>
                            <td>{{ $no + 1 }}</td>
                            <td>{{ tanggal($d->tgl) }}</td>
                            <td align="right">{{ number_format($debit, 0) }}</td>
                            <td align="right">{{ number_format($kredit, 0) }}</td>
                            <td>{{ ucwords($d->ket) }}</td>
                            <td align="right">
                                <a href="#" id_transaksi="{{ $d->id_transaksi }}"
                                    class="edit btn btn-sm btn-primary"><i class="fas fa-pen"></i></a>
                                <a class="btn btn-sm btn-danger delete_nota" no_nota="{{ $d->id_transaksi }}"
                                    href="#" data-bs-toggle="modal" data-bs-target="#delete"><i
                                        class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
        <form action="{{ route('cashflow.destroy') }}" method="post">
            @csrf
            <div class="modal fade" id="delete" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="row">
                                <h5 class="text-danger ms-4 mt-4"><i class="fas fa-trash"></i> Hapus Data</h5>
                                <p class=" ms-4 mt-4">Apa anda yakin ingin menghapus ?</p>
                                <input type="hidden" class="no_nota" name="no_nota">
                                <input type="hidden" name="tgl1" value="{{ $tgl1 }}">
                                <input type="hidden" name="tgl2" value="{{ $tgl2 }}">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <form action="{{ route('cashflow.update') }}" method="post">
            @csrf
            <x-theme.modal title="Edit Cashflow" size="modal-lg" idModal="edit">
                
                <div id="load_edit"></div>
            </x-theme.modal>
        </form>
        @section('scripts')
            <script>
                $(document).on('click', '.delete_nota', function() {
                    var no_nota = $(this).attr('no_nota');
                    $('.no_nota').val(no_nota);
                })
                $(document).on('click', '.edit', function() {
                    var id = $(this).attr('id_transaksi')
                    $("#edit").modal('show')
                    $.ajax({
                        type: "GET",
                        url: "{{ route('cashflow.edit') }}?id_transaksi=" + id,
                        success: function(r) {
                            $("#load_edit").html(r);
                        }
                    });
                })
            </script>
        @endsection
    </x-slot>

</x-theme.app>
