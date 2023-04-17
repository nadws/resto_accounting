<x-theme.app title="{{$title}}" table="Y" sizeCard="12">
    <x-slot name="cardHeader">
        <div class="row justify-content-end">
            <div class="col-lg-6">
                <a href="{{route('export_jurnal',['tgl1'=> $tgl1, 'tgl2'=>$tgl2])}}"
                    class="float-end btn btn-success"><i class="fas fa-file-excel"></i> Export</a>
                <x-theme.button modal="T" href="{{route('po.add')}}" icon="fa-plus" addClass="float-end"
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
                        <th>No Po</th>
                        <th>Admin</th>
                        <th>Total Rp</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                
            </table>
        </section>

        {{-- <form action="" method="get">
            <x-theme.modal title="Filter Jurnal Umum" idModal="view">
                <div class="row">
                    <div class="col-lg-12">

                        <table width="100%" cellpadding="10px">
                            <tr>
                                <td>Tanggal</td>
                                <td>
                                    <label for="">Dari</label>
                                    <input type="date" name="tgl1" class="form-control">
                                </td>
                                <td>
                                    <label for="">Sampai</label>
                                    <input type="date" name="tgl2" class="form-control">
                                </td>
                            </tr>
                            <tr>
                                <td>Proyek</td>
                                <td colspan="2">
                                    <select name="id_proyek" id="selectView" class="">
                                        <option value="0">All</option>
                                        @foreach ($proyek as $p)
                                        <option value="{{$p->id_proyek}}">{{$p->nm_proyek}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

            </x-theme.modal>
        </form>

        <form action="{{route('jurnal-delete')}}" method="get">
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
        </form> --}}




    </x-slot>
    @section('scripts')
    <script>
    
    </script>
    @endsection
</x-theme.app>