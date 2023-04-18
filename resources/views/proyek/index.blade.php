<x-theme.app title="{{$title}}" table="Y" sizeCard="12">
    <x-slot name="cardHeader">
        <div class="row justify-content-end">
            <div class="col-lg-6">
                <x-theme.button modal="Y" idModal="tambah" icon="fa-plus" addClass="float-end" teks="Buat Baru" />
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
                        <th>Kode </th>
                        <th>Nama Proyek</th>
                        <th>Manager Proyek</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($proyek as $no => $a)
                    <tr>
                        <td>{{$no + 1}}</td>
                        <td>{{date('d-m-Y',strtotime($a->tgl))}}</td>
                        <td>{{$a->kode_proyek}}</td>
                        <td>{{$a->nm_proyek}}</td>
                        <td>{{$a->manager_proyek}}</td>
                        <td><span
                                class="badge {{$a->status == 'berjalan' ? 'bg-warning': 'bg-success'}}">{{$a->status}}</span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <span class="btn btn-sm" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v text-primary"></i>
                                </span>
                                <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                    <li><a class="dropdown-item text-primary edit_akun" href="#" data-bs-toggle="modal"
                                            data-bs-target="#edit" id_proyek="{{$a->id_proyek}}"><i
                                                class="me-2 fas fa-pen"></i>Edit</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item  text-danger delete_proyek"
                                            id_proyek="{{$a->id_proyek}}" href="#" data-bs-toggle="modal"
                                            data-bs-target="#delete"><i class="me-2 fas fa-trash"></i>Delete
                                        </a>
                                    </li>
                                    <li><a class="dropdown-item  text-info" href="#"><i
                                                class="fas fa-clipboard-check"></i> Selesai</a>
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
        <form action="{{route('proyek')}}" method="post">
            @csrf
            <x-theme.modal title="Tambah Proyek" idModal="tambah">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="">Kode Proyek</label>
                            <input type="text" class="form-control" name="kd_proyek">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="">Nama Proyek</label>
                            <input type="text" class="form-control" name="nm_proyek">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="">Tanggal Proyek</label>
                            <input type="date" class="form-control " name="tgl">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="">Tanggal Estimasi Selesai</label>
                            <input type="date" class="form-control " name="tgl_estimasi">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="">Manager Proyek</label>
                            <input type="text" name="manager_proyek" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="">Estimasi Biaya</label>
                            <input type="text" class="form-control b_estimasi" style="text-align: right">
                            <input type="hidden" name="biaya_estimasi" class="form-control b_estimasi_biasa"
                                style="text-align: right">
                        </div>
                    </div>
                </div>
            </x-theme.modal>
        </form>

        <form action="{{route('proyek_delete')}}" method="get">
            <div class="modal fade" id="delete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="row">
                                <h5 class="text-danger ms-4 mt-4"><i class="fas fa-trash"></i> Hapus Data</h5>
                                <p class=" ms-4 mt-4">Apa anda yakin ingin menghapus ?</p>
                                <input type="hidden" class="id_proyek" name="id_proyek">
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
    </x-slot>

    @section('scripts')
    <script src="/js/proyek.js"></script>
    @endsection
</x-theme.app>