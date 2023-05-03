<x-theme.app title="{{$title}}" table="Y" sizeCard="12">
    <x-slot name="cardHeader">
        <div class="row justify-content-end">
            <div class="col-lg-2">
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
                        <th>Kode</th>
                        <th>Nama Akun</th>
                        <th>Subklasifikasi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($akun as $no => $a)
                    <tr>
                        <td>{{$no + 1}}</td>
                        <td>{{$a->kode_akun}}</td>
                        <td>{{ ucwords(strtolower($a->nm_akun))}}</td>
                        <td>
                            {{$a->klasifikasi->nm_subklasifikasi}}
                        </td>
                        <td>
                            <span class=" badge bg-{{ $a->is_active != 'Y' ? 'danger' : 'success' }}">
                                {{ $a->is_active != 'Y' ? 'Tidak Aktif' : 'Aktif' }}</span>
                        </td>
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
                            <select name="id_klasifikasi" id="" class="select2 get_kode">
                                <option value="">Pilih Subklasifikasi</option>
                                @foreach ($subklasifikasi as $s)
                                <option value="{{$s->id_subklasifikasi_akun}}">{{$s->nm_subklasifikasi}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="">Kode Akun</label>
                            <input type="text" class="form-control kode" name="kode_akun" required>
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

        <form action="{{route('akun')}}" method="post">
            @csrf
            <x-theme.modal title="Edit Akun" idModal="edit">
                <div id="get_edit">

                </div>
            </x-theme.modal>
        </form>
    </x-slot>
    @section('scripts')
    <script>
        $(document).ready(function () {
            $(document).on("change", ".get_kode", function () {
                var id_sub = $(this).val();
                $.ajax({
                    type: "get",
                    url: "/get_kode?id_sub="+ id_sub,
                    success: function (data) {
                        $('.kode').val(data.kode);
                        $('.kode2').val(data.kode_max);
                    }
                });
            });
            $(document).on("click", ".edit_akun", function () {
                var id_akun = $(this).attr('id_akun');
                $.ajax({
                    type: "get",
                    url: "/get_edit_akun?id_akun="+ id_akun,
                    success: function (data) {
                       $('#get_edit').html(data)
                    }
                });
            });
        });
    </script>
    @endsection
</x-theme.app>