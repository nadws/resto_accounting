<x-theme.app title="{{ $title }}" table="Y" sizeCard="12">
    <x-slot name="cardHeader">
        <div class="row">
            <div class="col-lg-12">
                {{-- @include('persediaan.bahan_makanan.nav') --}}
            </div>
            <div class="col-lg-12">
                <hr>
            </div>
        </div>
        <h6 class="float-start">{{ $title }}</h6>
        <div class="row justify-content-end">
            <div class="col-lg-8">
                <div class="dropdown float-end">
                    <button class="btn btn-primary dropdown-toggle me-1 btn-sm" type="button" id="dropdownMenuButton"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-plus"></i> Tambah Data
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#tambah" href="#">Menu</a>
                        <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#tbhKategori"
                            href="#">Kategori</a>
                        <a class="dropdown-item" id="stationC" data-bs-toggle="modal" data-bs-target="#station"
                            href="#">Station</a>
                        <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#tbhHandicap"
                            href="#">Level Point</a>
                    </div>
                </div>
                <div class="dropdown float-end">
                    <button class="btn btn-primary dropdown-toggle me-1 btn-sm" type="button" id="dropdownMenuButton"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-file-excel"></i> Import/Export
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#exportmenu" href="#">Menu</a>
                        <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#resepexport" href="#">Resep</a>
                   
                    </div>
                </div>


            </div>

        </div>

    </x-slot>

    <x-slot name="cardBody">
        <section class="row">
            <div class="col-lg-1">
                <select name="" id="perpage" class="perpage form-control">
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
            <div class="col-lg-9">

            </div>
            <div class="col-lg-2">
                <input type="text" class="form-control search" name="search" placeholder="Cari...">
            </div>
            <input type="hidden" class="halaman" value="1">
            <div id="load_menu"></div>
            <form id="save_menu">
                <x-theme.modal title="Tambah Menu" idModal="tambah" size="modal-lg-max">
                    <div class="row">
                        <div class="col-sm-4 ol-md-6 col-xs-12 mb-2">
                            <label for="">Masukkan Gambar</label>
                            <input type="file" class="dropify" data-height="150" name="image" placeholder="Image">
                        </div>
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-lg-6 mb-2">
                                    <label for="">
                                        <dt>Kategori</dt>
                                    </label>
                                    <select name="id_kategori" id="" class="form-control select2">
                                        <option value="">-Pilih Kategori-</option>
                                        @foreach ($kategori as $m)
                                            <option value="{{ $m->kd_kategori }}">{{ $m->kategori }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <label for="">
                                        <dt>Level Point</dt>
                                    </label>
                                    <select name="id_handicap" id="" class="form-control select2">
                                        <option value="">-Pilih Level-</option>
                                        @foreach ($handicap as $m)
                                            <option value="{{ $m->id_handicap }}">{{ $m->handicap }}
                                                ({{ $m->point }} Point)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 mb-2">
                                    <label for="">
                                        <dt>Kode Menu</dt>
                                    </label>
                                    <input readonly type="text" name="kd_menu" class="form-control"
                                        placeholder="Kode Menu" value="{{ $menu->kd_menu + 1 }}">
                                </div>
                                <div class="col-lg-6 mb-2">
                                    <label for="">
                                        <dt>Nama Menu</dt>
                                    </label>
                                    <input type="text" name="nm_menu" class="form-control"
                                        placeholder="Nama Menu">
                                </div>
                                <div class="col-lg-2 mb-2">
                                    <label for="">
                                        <dt>Tipe</dt>
                                    </label>
                                    <Select class="form-control select2" name="tipe">
                                        <option value="">-Pilih tipe-</option>
                                        <option value="food">Food</option>
                                        <option value="drink">Drink</option>
                                    </Select>
                                </div>
                                <div class="col-lg-4 mb-2">
                                    <label for="">
                                        <dt>Station</dt>
                                    </label>
                                    <Select class="form-control select2" name="id_station">
                                        <option value="">-Pilih station-</option>
                                        @foreach ($st as $s)
                                            <option value="{{ $s->id_station }}">{{ $s->nm_station }}</option>
                                        @endforeach
                                    </Select>
                                </div>

                                <div class="col-lg-5 mb-2">
                                    <label for="">
                                        <dt>Distribusi</dt>
                                    </label>
                                    <input type="hidden" name="id_distribusi[]" value="1">
                                    <input type="text" class="form-control" value="DINE-IN / TAKEWAY" readonly>
                                </div>
                                <div class="col-lg-5 mb-2">
                                    <label for="">
                                        <dt>Harga</dt>
                                    </label>
                                    <input type="text" name="harga[]" class="form-control" placeholder="Harga">
                                </div>
                                <div class="col-lg-5 mb-2">
                                    <input type="hidden" name="id_distribusi[]" value="2">
                                    <input type="text" class="form-control" value="GOJEK" readonly>
                                </div>
                                <div class="col-lg-5 mb-2">
                                    <input type="text" name="harga[]" class="form-control" placeholder="Harga">
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-12">
                            <hr>
                        </div>
                        <div class="col-lg-6">
                            <h6>Resep</h6>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="50">Bahan</th>
                                        <th width="10">Qty</th>
                                        <th width="10">Satuan</th>
                                        <th width="5">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="baris1">
                                        <td>
                                            <select name="id_bahan[]" id="" class="select2 id_bahan"
                                                count="1">
                                                <option value="">Pilih Bahan</option>
                                                @foreach ($bahan as $b)
                                                    <option value="{{ $b->id_list_bahan }}">{{ $b->nm_bahan }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="text" class="form-control" name="qty[]"
                                                value="0">
                                        </td>
                                        <td><input type="text" class="form-control nm_bahan1" value=""
                                                readonly>
                                        </td>
                                        <td class="text-center"><a href="#"
                                                class="btn btn-rounded remove_baris" count="1"><i
                                                    class="fas fa-trash text-danger"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                <tbody class="load_tambah_resep"></tbody>
                                </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4"><button type="button"
                                                class="btn btn-primary btn-block tambah_baris_resep">Tambah
                                                Baris</button>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </x-theme.modal>
            </form>

            <form id="delete_menu">
                <div class="modal fade" id="delete" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="row">
                                    <h5 class="text-danger ms-4 mt-4"><i class="fas fa-trash"></i> Hapus Data</h5>
                                    <p class=" ms-4 mt-4">Apa anda yakin ingin menghapus ?</p>
                                    <input type="hidden" class="id_menu" name="id_menu">
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

            <form id="edit_menu">
                <x-theme.modal title="Edit Menu" idModal="edit" size="modal-lg-max">
                    <div id="load_edit"></div>
                </x-theme.modal>
            </form>
            <form id="edit_resep">
                <x-theme.modal title="Resep" idModal="resep" size="modal-lg">
                    <div id="load_resep"></div>
                </x-theme.modal>
            </form>

            <form action="{{ route('menu.importMenuLevel') }}" method="post" enctype="multipart/form-data">
                @csrf
                <x-theme.modal title="Menu" idModal="exportmenu" size="modal-lg">
                    <div class="row">
                        <div class="col-lg-12">
                            <table>
                                <tr>
                                    <td width="100" class="pl-2">
                                        <img width="80px" src="{{ asset('img') }}/1.png" alt="">
                                    </td>
                                    <td>
                                        <span style="font-size: 20px;"><b> Download Excel template</b></span><br>
                                        File ini memiliki kolom header dan isi yang sesuai dengan data menu
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('menu.export_menu') }}" class="btn btn-primary btn-sm"><i
                                                class="fa fa-download"></i>
                                            DOWNLOAD TEMPLATE
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <hr>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="100" class="pl-2">
                                        <img width="80px" src="{{ asset('img') }}/2.png" alt="">
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
                    </div>
                </x-theme.modal>
            </form>

            <form action="{{ route('menu.import_resep') }}" method="post" enctype="multipart/form-data">
                @csrf
                <x-theme.modal title="Export dan import resep" idModal="resepexport" size="modal-lg">
                    <div class="row">
                        <div class="col-lg-12">
                            <table>
                                <tr>
                                    <td width="100" class="pl-2">
                                        <img width="80px" src="{{ asset('img') }}/1.png" alt="">
                                    </td>
                                    <td>
                                        <span style="font-size: 20px;"><b> Download Excel template</b></span><br>
                                        File ini memiliki kolom header dan isi yang sesuai dengan data menu
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('menu.export_resep') }}" class="btn btn-primary btn-sm"><i
                                                class="fa fa-download"></i>
                                            DOWNLOAD TEMPLATE
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <hr>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="100" class="pl-2">
                                        <img width="80px" src="{{ asset('img') }}/2.png" alt="">
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
                    </div>
                </x-theme.modal>
            </form>


            <form action="{{ route('menu.import_resep') }}" method="post" enctype="multipart/form-data">
                @csrf
                <x-theme.modal title="Tambah Kategori" idModal="tbhKategori">
                    <div class="row">
                        @php
                            $kategoriKd = DB::table('tb_kategori')
                                ->orderBy('kd_kategori', 'desc')
                                ->where('lokasi', 'TAKEMORI')
                                ->first();
                        @endphp
                        <div class="col-lg-3">
                            <label>Kode</label>
                            <input type="number" readonly class="form-control"
                                value="{{ $kategoriKd->kd_kategori + 1 }}" name="kd_kategori">
                        </div>
                        <div class="col-lg-9">
                            <label>Kategori</label>
                            <input type="text" required class="form-control" name="nm_kategori">
                        </div>
                    </div>
                </x-theme.modal>
            </form>
            <form action="{{ route('menu.import_resep') }}" method="post" enctype="multipart/form-data">
                @csrf
                <x-theme.modal title="Tambah Station" idModal="station">
                    <div class="row">
                        <div class="col-lg-12 mb-4">
                            <label for="">Nama Station</label>
                            <input autofocus type="text" id="nm_station" name="nm_station" class="form-control">
                        </div>

                        <div id="stationK"></div>
                    </div>
                </x-theme.modal>
            </form>
            <form action="{{ route('menu.import_resep') }}" method="post" enctype="multipart/form-data">
                @csrf
                <x-theme.modal title="Tambah Handicap" idModal="tbhHandicap" size="modal-lg">
                    <div class="row">
                        <div class="col-lg-3">
                            <label for="">Level</label>
                            <input type="text" class="form-control" name="handicap">

                        </div>
                        <div class="col-lg-6">
                            <label for="">Keterangan</label>
                            <input type="text" class="form-control" name="ket">

                        </div>
                        <div class="col-lg-3">
                            <label for="">Point</label>
                            <input required type="number" name="point" class="form-control">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12">
                            <table id="table1" class="table table-bordered" width="100%">
                                <thead>
                                    <tr>
                                        <td>#</td>
                                        <td>Level</td>
                                        <td>Keterangan</td>
                                        <td>Point</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($handicap as $h)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $h->handicap }}</td>
                                            <td>{{ $h->ket }}</td>
                                            <td>{{ $h->point }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </x-theme.modal>
            </form>

        </section>
        @section('scripts')
            <script src="{{ asset('js') }}/menu.js"></script>
            <script>
                function station() {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('menu.station') }}",
                        success: function(data) {
                            $("#stationK").html(data)
                        }
                    });
                }
                $("#stationC").click(function(e) {
                    station()
                });
            </script>
        @endsection
    </x-slot>


</x-theme.app>
