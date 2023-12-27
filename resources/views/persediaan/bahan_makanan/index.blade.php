<x-theme.app title="{{ $title }}" table="Y" sizeCard="10">
    <x-slot name="cardHeader">
        <div class="row">
            <div class="col-lg-12">
                @include('persediaan.bahan_makanan.nav')

            </div>
            <div class="col-lg-12">
                <hr>
            </div>
        </div>
        <h6 class="float-start">{{ $title }}</h6>
        <div class="row justify-content-end">
            <div class="col-lg-6">
                <x-theme.button modal="Y" idModal="tambah" href="#" icon="fa-plus" addClass="float-end"
                    teks="Buat Baru" />
                <x-theme.button href="#" icon="fa-history" addClass="float-end history" teks="History" />
                <x-theme.button modal="Y" idModal="import" href="#" icon="fa-upload" addClass="float-end"
                    teks="Import" />
            </div>

        </div>

    </x-slot>

    <x-slot name="cardBody">

        <section class="row">
            <table class="table" id="table1">
                <thead>
                    <tr>
                        <th width="5">#</th>
                        <th>Nama Bahan</th>
                        <th>Kategori</th>
                        <th class="text-center">Stok</th>
                        <th>Satuan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($bahan as $i => $d)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $d->nm_bahan }}</td>
                            <td>{{ $d->nm_kategori }}</td>
                            <td>{{ number_format($d->stok, 0) }}</td>
                            <td>{{ $d->nm_satuan }}</td>
                            <td align="center">
                                <a href="#" id_bahan="{{ $d->id_list_bahan }}"
                                    class="btn btn-primary btn-sm edit"><i class="fas fa-pen"></i></a>
                                <a onclick="return confirm('JIKA DIHAPUS STOK JUGA HILANG. Yakin dihapus ?')"
                                    href="{{ route('bahan.delete', $d->id_list_bahan) }}"
                                    class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <form action="{{ route('bahan.save') }}" method="post" enctype="multipart/form-data"
                x-data="{}">
                @csrf
                <x-theme.modal title="Tambah Bahan" idModal="tambah" size="modal-lg">
                    <div class="row">
                        <div class="col-lg-4">
                            <label for="">Nama Bahan</label>
                            <input type="text" class="form-control" name="nm_bahan">
                        </div>
                        <div class="col-lg-2">
                            <label for="">Kategori</label>
                            <select name="kategori_id" id="" class="select2">
                                <option value="">Pilih Kategori</option>
                                @foreach ($kategori as $k)
                                    <option value="{{ $k->id_kategori_bahan }}">{{ strtoupper($k->nm_kategori) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <label for="">Satuan</label>
                            <select name="satuan_id" id="" class="select2">
                                <option value="">Pilih Satuan</option>
                                @foreach ($satuan as $s)
                                    <option value="{{ $s->id_satuan }}">{{ strtoupper($s->nm_satuan) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="">Stok</label>
                                <input x-mask:dynamic="$money($input)" type="text" name="stok"
                                    class="text-end form-control">
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="">Ttl Rp</label>
                                <input x-mask:dynamic="$money($input)" type="text" name="ttl_rp"
                                    class="text-end form-control">
                            </div>
                        </div>

                    </div>

                </x-theme.modal>
            </form>
            <form action="{{ route('bahan.update') }}" method="post">
                @csrf
                <x-theme.modal title="Edit Bahan" idModal="edit" size="modal-lg">
                    <div id="load_edit"></div>

                </x-theme.modal>
            </form>

            <form action="{{ route('bahan.import') }}" enctype="multipart/form-data" method="post">
                @csrf
                <x-theme.modal size="modal-lg" idModal="import" title="Import Bk">
                    <div class="row">
                        <table>
                            <tr>
                                <td width="100" class="pl-2">
                                    <img width="80px" src="{{ asset('/img/1.png') }}" alt="">
                                </td>
                                <td>
                                    <span style="font-size: 20px;"><b> Download Excel template</b></span><br>
                                    File ini memiliki kolom header dan isi yang sesuai dengan data menu
                                </td>
                                <td>
                                    <a href="{{ route('bahan.template') }}" class="btn btn-primary btn-sm"><i
                                            class="fa fa-download"></i> DOWNLOAD
                                        TEMPLATE</a>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <hr>
                                </td>
                            </tr>
                            <tr>
                                <td width="100" class="pl-2">
                                    <img width="80px" src="{{ asset('/img/2.png') }}" alt="">
                                </td>
                                <td>
                                    <span style="font-size: 20px;"><b> Upload Excel template</b></span><br>
                                    Setelah mengubah, silahkan upload file.
                                </td>
                                <td>
                                    <input type="file" name="file" class="form-control">
                                </td>
                            </tr>
                        </table>

                    </div>
                </x-theme.modal>
            </form>


            <x-theme.modal title="History Bahan" btnSave="T" idModal="history" size="modal-lg">
                <div id="load_history"></div>
            </x-theme.modal>
        </section>
    </x-slot>
    @section('scripts')
        <script>
            $(document).on('click', '.edit', function(e) {
                e.preventDefault();
                $('#edit').modal('show')
                var id_bahan = $(this).attr('id_bahan')
                $.ajax({
                    type: "GET",
                    url: "{{ route('bahan.load_edit') }}?id_bahan=" + id_bahan,
                    success: function(response) {
                        $("#load_edit").html(response);
                        $('.select2edit').select2({
                            dropdownParent: $('#edit .modal-content')
                        });
                    }
                });
            })

            $(document).on('click', '.history', function(e) {
                e.preventDefault()
                $('#history').modal('show')
                $.ajax({
                    type: "GET",
                    url: "{{ route('bahan.history') }}",
                    success: function(r) {
                        $("#load_history").html(r);
                        const tbl = [
                            'Masuk', 'Keluar', 'Opname'
                        ]
                        tbl.forEach(item => {
                            $('#tbl' + item).DataTable({
                                "paging": true,
                                "pageLength": 10,
                                "lengthChange": true,
                                "stateSave": true,
                                "searching": true,
                            });
                        })

                    }
                });
            })
        </script>
    @endsection

</x-theme.app>
