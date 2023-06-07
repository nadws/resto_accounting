<x-theme.app title="{{ $title }}" table="Y" sizeCard="12">
    <x-slot name="cardHeader">
        <div class="row">
            <div class="col-lg-12">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link  active" href="" type="button" role="tab" aria-controls="pills-home"
                            aria-selected="true">Gudang Martadah</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" href="" type="button" role="tab" aria-controls="pills-home"
                            aria-selected="true">Gudang Alpa</a>
                    </li>
                </ul>
                <hr style="border:1px solid #435EBE">
            </div>
            <div class="col-lg-6">
                <h6 class="float-start mt-1">{{ $title }}</h6>
            </div>
            <div class="col-lg-6">
                <x-theme.button modal="T" href="{{ route('tbh_stok_telur') }}" icon="fa-plus" addClass="float-end"
                    teks="Tambah Stok" />
                <x-theme.button modal="T" href="{{ route('transfer_stok_telur') }}" icon="fa-exchange-alt"
                    addClass="float-end" teks="Transfer Stok" />
            </div>
        </div>
    </x-slot>
    <x-slot name="cardBody">
        <section class="row">
            <table class="table table-hover" id="table">
                <thead>
                    <tr>
                        <th width="5">#</th>
                        <th>Tanggal</th>
                        <th>Kandang</th>
                        <th>Produk</th>
                        <th>Pcs</th>
                        <th>Kg</th>
                        <th>Ikat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stok as $no => $s)
                    <tr>
                        <td>{{$no+1}}</td>
                        <td>{{tanggal($s->tgl)}}</td>
                        <td>{{$s->nm_kandang}}</td>
                        <td>{{$s->nm_telur}}</td>
                        <td>{{$s->pcs}}</td>
                        <td>{{$s->kg}}</td>
                        <td>{{number_format($s->pcs / 180,2)}} </td>
                        <td>
                            <div class="btn-group" role="group">
                                <span class="btn btn-sm" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v text-primary"></i>
                                </span>
                                <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                    <li><a class="dropdown-item text-primary edit_akun" href=""><i
                                                class="me-2 fas fa-pen"></i>Edit</a>
                                    </li>

                                    <li>
                                        <a class="dropdown-item text-danger delete_nota" no_nota="" href="#"
                                            data-bs-toggle="modal" data-bs-target="#delete"><i
                                                class="me-2 fas fa-trash"></i>Delete
                                        </a>
                                    </li>
                                    <li><a class="dropdown-item  text-info detail_nota" href="#" href="#"
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

        {{-- sub akun --}}
        <x-theme.modal title="Edit Akun" idModal="sub-akun" size="modal-lg">
            <div id="load-sub-akun">
            </div>
        </x-theme.modal>
        {{-- end sub akun --}}
    </x-slot>
</x-theme.app>