<x-theme.app title="{{$title}}" table="Y" sizeCard="12">
    <x-slot name="cardHeader">
        <div class="row justify-content-end">
            <div class="col-lg-6">
                <x-theme.button modal="T" href="{{route('jurnal.add')}}" icon="fa-plus" addClass="float-end"
                    teks="Buat Baru" />
                <x-theme.button modal="Y" idModal="view" icon="fas fa-search" addClass="float-end" teks="" />
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
                        <th style="text-align: right">Debit</th>
                        <th style="text-align: right">Kredit</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jurnal as $no => $a)
                    <tr>
                        <td>{{$no + 1}}</td>
                        <td>{{date('d-m-Y',strtotime($a->tgl))}}</td>
                        <td>{{$a->no_nota}}</td>
                        <td>{{$a->akun->nm_akun}}</td>
                        <td>{{$a->ket}}</td>
                        <td align="right">{{number_format($a->debit,0)}}</td>
                        <td align="right">{{number_format($a->kredit,0)}}</td>
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
                                    <li>
                                        <a class="dropdown-item  text-danger"
                                            href="{{route('jurnal-delete',['no_nota' => $a->no_nota])}}"><i
                                                class="me-2 fas fa-trash"></i>Delete
                                        </a>
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

        <form action="" method="get">
            <x-theme.modal title="Tambah Akun" idModal="view">
                <div class="row">
                    <div class="col-lg-6">
                        <label for="">Dari</label>
                        <input type="date" name="tgl1" class="form-control">
                    </div>
                    <div class="col-lg-6">
                        <label for="">Sampai</label>
                        <input type="date" name="tgl2" class="form-control">
                    </div>
                </div>

            </x-theme.modal>
        </form>


    </x-slot>
    {{-- @section('scripts')
    <script>
        $(document).ready(function () {
            
        });
    </script>
    @endsection --}}
</x-theme.app>