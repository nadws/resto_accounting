<x-theme.app title="{{$title}}" table="Y" sizeCard="12">
    <x-slot name="cardHeader">
        <div class="row justify-content-end">
            <div class="col-lg-2">
                <x-theme.button modal="T" href="{{route('add_jurnal')}}" icon="fa-plus" addClass="float-end"
                    teks="Buat Baru" />
            </div>
        </div>

    </x-slot>

    <x-slot name="cardBody">
        <section class="row">
            <table class="table table-hover" id="table1">
                <thead>
                    <tr>
                        <th width="5">#</th>
                        <th>Tanggal</th>
                        <th>No Nota</th>
                        <th>Akun</th>
                        <th>Keterangan</th>
                        <th>Debit</th>
                        <th>Kredit</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jurnal as $no => $j)
                    <tr>
                        <td>{{$no + 1}}</td>
                        <td>{{$a->tgl}}</td>
                        <td>{{$a->no_nota}}</td>
                        <td>{{$a->akun->nm_akun}}</td>
                        <td>{{$a->ket}}</td>
                        <td>{{number_format($a->debit,0)}}</td>
                        <td>{{number_format($a->kredit,0)}}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <span class="btn btn-sm" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v text-primary"></i>
                                </span>
                                <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                    <li><a class="dropdown-item text-primary edit_akun" href="#" data-bs-toggle="modal"
                                            data-bs-target="#edit" id_akun="{{$a->id_akun}}"><i
                                                class="me-2 fas fa-pen"></i>Edit</a>
                                    </li>
                                    <li><a class="dropdown-item  text-danger" href="#"><i
                                                class="me-2 fas fa-trash"></i>Delete</a>
                                    </li>
                                    <li><a class="dropdown-item  text-info" href="#"><i
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

        <form action="{{route('akun')}}" method="post">
            @csrf
            <x-theme.modal title="Tambah Akun" idModal="tambah">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="">Subklasifikasi</label>

                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="">Kode Akun</label>
                            <input type="text" class="form-control kode" readonly>
                            <input type="hidden" name="kode_akun" class="form-control kode2">
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="">Nama Akun</label>
                            <input type="text" name="nm_akun" class="form-control">
                        </div>
                    </div>
                </div>
            </x-theme.modal>
        </form>


    </x-slot>
    @section('scripts')
    <script>
        $(document).ready(function () {
            
        });
    </script>
    @endsection
</x-theme.app>