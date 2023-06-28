<x-theme.app cont="container-fluid" title="{{ $title }}" table="Y" sizeCard="8">
    <x-slot name="cardHeader">
        <div class="row justify-content-end">
            <div class="col-lg-12">

                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{$id_gudang == 1 ? 'active' : ''}}  "
                            href="{{route('produk_telur',['id_gudang' => '1'])}}" type="button" role="tab"
                            aria-controls="pills-home" aria-selected="true">Gudang Martadah</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link  {{$id_gudang == 2 ? 'active' : ''}} "
                            href="{{route('produk_telur',['id_gudang' => '2'])}}" type="button" role="tab"
                            aria-controls="pills-home" aria-selected="true">Gudang Alpa</a>
                    </li>
                </ul>
                <hr style="border:1px solid #435EBE">
            </div>
            <div class="col-lg-6">
                <h6 class="float-start mt-1">{{ $title }} </h6>
            </div>
            <div class="col-lg-6">
                <x-theme.button modal="T" href="#" icon="fa-plus" addClass="float-end" teks="Buat Baru" />
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
                        <th>Nama Produk</th>
                        <th style="text-align: right">Pcs</th>
                        <th style="text-align: right">Kg</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($produk as $no => $p)
                    <tr>
                        <td>{{$no+1}}</td>
                        <td>{{$p->kode_produk}}</td>
                        <td>{{$p->nm_telur}}</td>
                        <td align="right">
                            {{ empty(($p->pcs - $p->pcs_kredit)) ? '0' : number_format($p->pcs - $p->pcs_kredit,0)}}
                        </td>
                        <td align="right">{{empty(($p->kg - $p->kg_kredit)) ? '0' : number_format($p->kg -
                            $p->kg_kredit,2)}}
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <span class="btn btn-sm" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v text-primary"></i>
                                </span>
                                <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                    <li><a class="dropdown-item text-primary " href="">
                                            <i class="me-2 fas fa-pen"></i>Edit</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-danger delete_nota"
                                            no_nota="{{$p->id_produk_telur}}" href="#" data-bs-toggle="modal"
                                            data-bs-target="#delete">
                                            <i class="me-2 fas fa-trash"></i>Delete
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </section>

        <form action="{{ route('jurnal-delete') }}" method="get">
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