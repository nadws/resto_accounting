<x-theme.app title="{{ $title }}" table="Y" sizeCard="12">
    <x-slot name="cardHeader">
        <div class="row">
            <div class="col-lg-12">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    @foreach ($gudang as $g)
                    <li class="nav-item" role="presentation">
                        <a class="nav-link  {{$id_gudang == $g->id_gudang_telur ? 'active' : ''}} "
                            href="{{route('stok_telur',['id_gudang' => $g->id_gudang_telur])}}" type="button" role="tab"
                            aria-controls="pills-home" aria-selected="true">Gudang {{$g->nm_gudang}}</a>
                    </li>
                    @endforeach


                </ul>
                <hr style="border:1px solid #435EBE">
            </div>
            <div class="col-lg-6">
                <h6 class="float-start mt-1">{{ $title }} : {{tanggal($tgl1)}}~{{tanggal($tgl2)}}</h6>
            </div>
            <div class="col-lg-6">
                <x-theme.button modal="T" href="{{ route('tbh_stok_telur',['id_gudang' => $id_gudang]) }}"
                    icon="fa-plus" addClass="float-end" teks="Tambah Stok" />
                <x-theme.button modal="T" href="{{ route('transfer_stok_telur',['id_gudang' => $id_gudang]) }}"
                    icon="fa-exchange-alt" addClass="float-end" teks="Transfer Stok" />
                <x-theme.btn_filter />
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
                        <th>Keterangan</th>
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
                        <td>{{$s->ket}}</td>
                        <td>{{$s->pcs}}</td>
                        <td>{{$s->kg}}</td>
                        <td>{{number_format($s->pcs / 180,2)}} </td>
                        <td>
                            <div class="btn-group" role="group">
                                <span class="btn btn-sm" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v text-primary"></i>
                                </span>
                                <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                    <li><a class="dropdown-item text-primary "
                                            href="{{route('edit_telur',['id_stok_telur' => $s->id_stok_telur])}}"><i
                                                class="me-2 fas fa-pen"></i>Edit</a>
                                    </li>

                                    <li>
                                        <a class="dropdown-item text-danger delete_nota" no_nota="{{$s->id_stok_telur}}"
                                            href="#" data-bs-toggle="modal" data-bs-target="#delete"><i
                                                class="me-2 fas fa-trash"></i>Delete
                                        </a>
                                    </li>
                                    {{-- <li><a class="dropdown-item  text-info detail_nota" href="#" href="#"
                                            data-bs-toggle="modal" data-bs-target="#detail"><i
                                                class="me-2 fas fa-search"></i>Detail</a>
                                    </li> --}}

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

        <form action="{{ route('delete_telur') }}" method="get">
            <div class="modal fade" id="delete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="row">
                                <h5 class="text-danger ms-4 mt-4"><i class="fas fa-trash"></i> Hapus Data</h5>
                                <p class=" ms-4 mt-4">Apa anda yakin ingin menghapus ?</p>
                                <input type="hidden" class="no_nota" name="no_nota">
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
        {{-- end sub akun --}}
    </x-slot>
    @section('js')
    <script>
        $(document).ready(function() {
                $(document).on('click', '.delete_nota', function() {
                    var no_nota = $(this).attr('no_nota');
                    $('.no_nota').val(no_nota);
                })
            });
    </script>
    @endsection
</x-theme.app>