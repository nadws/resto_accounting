<x-theme.app title="{{ $title }}" table="Y" sizeCard="10">
    <x-slot name="cardHeader">
        <div class="row">
            <div class="col-lg-12">
                @include('datamenu.po.nav', ['tgl1' => $tgl1, 'tgl2' => $tgl2])
            </div>
            <div class="col-lg-12">
                <hr>

            </div>
        </div>
        <h6 class="float-start">{{ $title }}</h6>
    </x-slot>

    <x-slot name="cardBody">
        <section class="row">
            <table class="table" id="table1">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>No Nota / Manual</th>
                        <th>Suplier</th>
                        <th>Tanggal</th>
                        <th>Total Rp</th>
                        <th>Status</th>
                        <th>Admin</th>
                        <th width="10%" class="text-center"></th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        function getCountTbhBayar($no_nota)
                        {
                            return DB::table('po_biaya_tambahan as a')
                                ->join('tb_ekspedisi as b', 'a.id_ekspedisi', 'b.id_ekspedisi')
                                ->where('a.no_nota', $no_nota)
                                ->count();
                        }
                    @endphp
                    @foreach ($po as $i => $d)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td><a href="{{ route('po.detail', $d->no_nota) }}">PO/{{ $d->no_nota }}</a> /
                                {{ $d->nota_manual }}</td>
                            <td>{{ $d->nm_suplier }}</td>
                            <td>{{ tanggal($d->tgl) }}</td>
                            <td>{{ number_format($d->sub_total, 0) }}</td>
                            @php
                                $warna = [
                                    'disetujui' => 'warning',
                                    'hutang' => 'danger',
                                    'lunas' => 'primary',
                                    'selesai' => 'primary',
                                ];
                            @endphp
                            <td class="text-{{ $warna[$d->status] }}">
                                <span
                                    class="badge badge-sm bg-{{ $warna[$d->status] }}">{{ ucwords($d->status) }}</span>
                                @if ($d->nota_jurnal)
                                    <br>
                                    <span class="mt-1 badge badge-sm bg-{{ $warna[$d->status] }}">Nota Jurnal :
                                        {{ ucwords($d->nota_jurnal) }}</span>
                                @endif
                                @if (getCountTbhBayar($d->no_nota) > 0)
                                    <br>

                                    <span class="mt-1 badge bg-success">Biaya Pengiriman :
                                        {{ $d->nota_jurnal_pengiriman ?? getCountTbhBayar($d->no_nota) }}</span>
                                @endif
                            </td>
                            <td>{{ $d->admin }}</td>
                            <td align="center">
                                <style>
                                    .dropdown-item:hover {
                                        background-color: #5b76d5;
                                        color: aliceblue;
                                    }
                                </style>
                                <div class="btn-group dropend me-1 mb-1">
                                    <button type="button" class="btn btn-sm btn-primary dropdown-toggle"
                                        data-bs-toggle="dropdown">
                                        Aksi
                                    </button>
                                    <div class="dropdown-menu" style="">
                                        @if (!$d->nota_jurnal)
                                            <a href="#" class="dropdown-item bukukan"
                                                linkSubmit="{{ route('po.create_bukukan') }}"
                                                link="{{ route('po.load_bukukan') }}" no_nota="{{ $d->no_nota }}">
                                                <i class="fas fa-book"></i> Bukukan +
                                                Stokan
                                            </a>
                                        @endif
                                        @if (!$d->nota_jurnal_pengiriman)
                                            @if (getCountTbhBayar($d->no_nota) > 0)
                                                <a href="#" class="dropdown-item bukukan"
                                                    linkSubmit="{{ route('po.create_bukukan_pengiriman') }}"
                                                    link="{{ route('po.load_bukukan_pengiriman') }}"
                                                    no_nota="{{ $d->no_nota }}"><i class="fas fa-book"></i> Bukukan
                                                    Biaya Pengiriman
                                                </a>
                                            @else
                                                <a href="{{ route('po.tambahan', $d->no_nota) }}"
                                                    class="dropdown-item"><i class="fas fa-plus"></i> Biaya Pengiriman

                                                </a>
                                            @endif
                                        @endif
                                        <a target="_blank" href="{{ route('po.print', $d->no_nota) }}"
                                            class="dropdown-item"><i class="fas fa-print"></i> Cetak</a>
                                        <a href="{{ route('po.detail', $d->no_nota) }}" class="dropdown-item"><i
                                                class="fas fa-eye"></i> Detail</a>
                                        <a onclick="return confirm('Yakin dihapus ? ')"
                                            href="{{ route('po.delete', $d->no_nota) }}"
                                            class="dropdown-item text-danger"><i class="fas fa-trash"></i> Hapus</a>
                                    </div>
                                </div>

                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </section>
        <form id="formSubmitBukukan" method="post">
            @csrf
            <x-theme.modal title="Bukukan ke Jurnal" size="modal-lg" idModal="modal_bukukan">
                <div id="load_bukukan"></div>
            </x-theme.modal>
        </form>


        @section('scripts')
            <script>
                $(document).on('click', '.bukukan', function(e) {
                    e.preventDefault();
                    const no_nota = $(this).attr('no_nota')
                    const link = $(this).attr('link')
                    const linkSubmit = $(this).attr('linkSubmit')

                    $("#formSubmitBukukan").attr('action', linkSubmit);
                    $("#modal_bukukan").modal('show')
                    
                    $.ajax({
                        type: "GET",
                        url: link,
                        data: {
                            no_nota: no_nota
                        },
                        success: function(r) {
                            $("#load_bukukan").html(r);
                            $('.selectAkun').select2()
                            $('#tblBukukan').DataTable({
                                "paging": true,
                                "pageLength": 10,
                                "lengthChange": true,
                                "stateSave": true,
                                "searching": true,
                            });

                        }
                    });
                })
            </script>
        @endsection
    </x-slot>
</x-theme.app>
