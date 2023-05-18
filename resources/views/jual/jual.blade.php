<x-theme.app title="{{ $title }}" table="Y" sizeCard="9">
    <x-slot name="cardHeader">
        <x-theme.button modal="Y" idModal="jual" icon="fa-plus" addClass="float-end" teks="Buat Baru" />
    </x-slot>

    <x-slot name="cardBody">
        <section class="row">
            <table class="table" id="table1">
                <thead>
                    <tr>
                        <th width="5">#</th>
                        <th>Tanggal</th>
                        <th>No Nota</th>
                        <th>No Penjual</th>
                        <th>Total Rp</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jual as $no => $d)
                        <tr>
                            <td>{{ $no + 1 }}</td>
                            <td>{{ date('d-m-Y', strtotime($d->tgl)) }}</td>
                            <td>{{ $d->no_nota }}</td>
                            <td>{{ $d->no_penjualan }}</td>
                            <td>{{ number_format($d->total_rp, 0) }}</td>
                            <td>{{ $d->status }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <span class="btn btn-sm" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v text-primary"></i>
                                    </span>
                                    <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">

                                        <li>
                                            <a class="dropdown-item  text-primary bayar_nota"
                                                no_nota="{{ $d->no_nota }}" href="#" data-bs-toggle="modal"
                                                data-bs-target="#tambah"><i class="me-2 fas fa-money-bill"></i>Bayar
                                            </a>
                                        </li>
                                        <li><a class="dropdown-item text-primary edit_akun"
                                                href="{{ route('jual.edit', ['no_penjualan' => $d->no_penjualan]) }}"><i
                                                    class="me-2 fas fa-pen"></i>Edit</a>
                                        </li>


                                        <li>
                                            <a class="dropdown-item  text-danger delete_nota"
                                                no_penjualan="{{ $d->no_penjualan }}" href="#"
                                                data-bs-toggle="modal" data-bs-target="#delete"><i
                                                    class="me-2 fas fa-trash"></i>Delete
                                            </a>
                                        </li>

                                        <li><a class="dropdown-item  text-info detail_nota" href="#"
                                                no_penjualan="{{ $d->no_penjualan }}" href="#"
                                                data-bs-toggle="modal" data-bs-target="#detail"><i
                                                    class="me-2 fas fa-search"></i>Detail</a>
                                        </li>
                                    </ul>
                                </div>
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
                            <input type="date" name="tgl" class="form-control">
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
                    {{-- <div class="col-lg-6">
                        <div class="form-group">
                            <label for="">Setor Ke</label>
                            <select name="setor" class="form-control select2" id="">
                                <option value="">- Pilih Akun -</option>
                                @foreach ($akun as $d)
                                    <option value="{{ $d->id_akun }}">{{ ucwords($d->nm_akun) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div> --}}
                </div>
            </x-theme.modal>
        </form>
        <form action="{{ route('jual.create') }}" method="post">
            @csrf
            <x-theme.modal title="Terima Pembayaran" idModal="tambah">
                <div class="row">
                    <input type="text" id="no_nota">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="">Tanggal</label>
                            <input type="date" name="tgl" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-8">
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
                                <input type="hidden" class="no_nota" name="no_nota">
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
        $(document).on('click', '.bayar_nota', function(){
            var no_nota = $(this).attr('no_nota')
            $("#no_nota").val(no_nota);
        })
    </script>
    @endsection
</x-theme.app>
