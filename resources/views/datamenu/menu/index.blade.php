<x-theme.app title="{{ $title }}" table="Y" sizeCard="12">
    <x-slot name="cardHeader">
        <div class="row">

        </div>
        <h6 class="float-start">{{ $title }}</h6>
        <div class="row justify-content-end">
            <div class="col-lg-6">
                <x-theme.button modal="Y" idModal="tambah" href="#" icon="fa-plus" addClass="float-end"
                    teks="Buat Baru" />
            </div>

        </div>

    </x-slot>

    <x-slot name="cardBody">
        <section class="row">
            <div class="col-lg-10">

            </div>
            <div class="col-lg-2">
                <input type="text" class="form-control search" name="search" placeholder="Cari...">
            </div>
            <input type="hidden" class="halaman" value="1">
            <div id="load_menu"></div>

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
                                <select name="id_kategori" id="" class="form-control select">
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
                                <select name="id_handicap" id="" class="form-control select">
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
                                <input type="text" name="nm_menu" class="form-control" placeholder="Nama Menu">
                            </div>
                            <div class="col-lg-2 mb-2">
                                <label for="">
                                    <dt>Tipe</dt>
                                </label>
                                <Select class="form-control select" name="tipe">
                                    <option value="">-Pilih tipe-</option>
                                    <option value="food">Food</option>
                                    <option value="drink">Drink</option>
                                </Select>
                            </div>
                            <div class="col-lg-4 mb-2">
                                <label for="">
                                    <dt>Station</dt>
                                </label>
                                <Select class="form-control select" name="id_station">
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
                                <select name="id_distribusi[]" id="" class="form-control select">
                                    <option value="">-Pilih distribusi-</option>
                                    <option value="1">DINE-IN / TAKEWAY</option>
                                    <option value="2">GOJEK</option>
                                    <option value="3">DELIVERY</option>
                                </select>
                            </div>
                            <div class="col-lg-5 mb-2">
                                <label for="">
                                    <dt>Harga</dt>
                                </label>
                                <input type="text" name="harga[]" class="form-control" placeholder="Harga">
                            </div>

                            <div class="col-lg-2 mb-2">
                                <label for="">
                                    <dt>Aksi</dt>
                                </label> <br>
                                <button href="" id="tambah_distribusi" type="button"
                                    class="btn btn-sm btn-info "><i class="fas fa-plus"></i></button>
                            </div>

                        </div>
                        <div id="p_pakan">

                        </div>
                    </div>
                </div>
            </x-theme.modal>

        </section>
        @section('scripts')
            <script>
                function toast(pesan) {
                    Toastify({
                        text: pesan,
                        duration: 3000,
                        position: 'center',
                        style: {
                            background: "#EAF7EE",
                            color: "#7F8B8B"
                        },
                        close: true,
                        avatar: "https://cdn-icons-png.flaticon.com/512/190/190411.png"
                    }).showToast();
                }


                var hal = $('.halaman').val()
                var search = $('.search').val()
                load_menu(hal, search);

                function load_menu(page, search) {
                    $.ajax({
                        type: "get",
                        url: "{{ route('menu.get_menu') }}",
                        data: {
                            page: page,
                            search: search
                        },
                        success: function(response) {
                            $("#load_menu").html(response);
                        },
                        error: function(error) {
                            console.error('Error fetching menu:', error);
                        }
                    });
                }
                $('body').on('click', '.pagination a', function(e) {
                    e.preventDefault();

                    var page = $(this).attr('href').split('page=')[1];
                    var search = $('.search').val();
                    var halaman = $('.halaman').val(page)

                    load_menu(page, search);
                });

                $(document).on('keyup', '.search', function() {
                    search = $(this).val();
                    var hal = $('.halaman').val()
                    load_menu(hal, search)
                });



                $(document).on('change', '.chekstatus', function() {
                    var isChecked = $(this).prop('checked');
                    var id_menu = $(this).attr('id_menu');

                    // Kirim permintaan AJAX
                    $.ajax({
                        url: "{{ route('menu.aktif') }}", // Ganti dengan URL yang sesuai
                        type: 'GET',
                        data: {
                            status: isChecked ? 'on' : 'off',
                            id_menu: id_menu
                        },
                        success: function(response) {
                            toast('Menu berhasil di update')
                        },
                        error: function(error) {
                            console.error(error);
                        }
                    });
                });
            </script>
        @endsection
    </x-slot>


</x-theme.app>
