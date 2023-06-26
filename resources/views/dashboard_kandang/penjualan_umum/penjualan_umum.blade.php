<x-theme.app title="{{ $title }}" table="Y" sizeCard="12">
    <x-slot name="cardHeader">
        <h6 class="float-start">{{ $title }}</h6>
        <x-theme.button modal="T" href="{{ route('dashboard_kandang.add_penjualan_umum') }}" icon="fa-plus"
            addClass="float-end" teks="Buat Baru" />
        <x-theme.button modal="T" href="{{ route('dashboard_kandang.index') }}" icon="fa-arrow-left"
            addClass="float-end" teks="kembali Ke Dashboard" />
        <x-theme.btn_filter />
    </x-slot>

    <x-slot name="cardBody">
        <section class="row">
            <table class="table" id="table1">
                <thead>
                    <tr>
                        <th width="5">#</th>
                        <th>Nota</th>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
                        <th width="20%" class="text-center">Total Produk</th>
                        <th class="text-end">Total Rp</th>
                        <th width="20%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($penjualan as $no => $d)
                        <tr>
                            <td>{{ $no + 1 }}</td>
                            <td>PAGL-{{ $d->urutan }}</td>
                            <td>{{ tanggal($d->tgl) }}</td>
                            <td>{{ $d->id_customer }}</td>
                            <td align="center">{{ $d->ttl_produk }}</td>
                            <td align="right">Rp. {{ number_format($d->total, 2) }}</td>
                            <td align="center">
                                <div class="btn-group" role="group">
                                    <span class="btn btn-sm" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v text-primary"></i>
                                    </span>
                                    <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                        <li><a class="dropdown-item  text-info detail_nota" href="#"
                                                no_nota="{{ $d->urutan }}" href="#" data-bs-toggle="modal"
                                                data-bs-target="#detail"><i class="me-2 fas fa-search"></i>Detail</a>
                                        </li>

                                        <li>
                                            <a class="dropdown-item text-info edit_akun"
                                                href="{{ route('dashboard_kandang.edit_penjualan', ['urutan' => $d->urutan]) }}"><i
                                                    class="me-2 fas fa-pen"></i>Edit</a>
                                        </li>

                                        <li>
                                            <a class="dropdown-item text-danger delete_nota"
                                                no_nota="{{ $d->urutan }}" href="#" data-bs-toggle="modal"
                                                data-bs-target="#delete"><i class="me-2 fas fa-trash"></i>Delete
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>

            <x-theme.modal btnSave="" title="Detail Jurnal" size="modal-lg" idModal="detail">
                <div class="row">
                    <div class="col-lg-12">
                        <div id="detail_jurnal"></div>
                    </div>
                </div>
            </x-theme.modal>

            <x-theme.btn_alert_delete route="penjualan2.delete" name="urutan" :tgl1="$tgl1" :tgl2="$tgl2" />
        </section>
        @section('js')
            <script>
                edit('detail_nota', 'no_nota', 'detail', 'detail_jurnal')
            </script>
        @endsection
    </x-slot>
</x-theme.app>
