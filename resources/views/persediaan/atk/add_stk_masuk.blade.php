<x-theme.app title="{{ $title }}" table="Y" sizeCard="10">
    <x-slot name="cardHeader">
        <h6 class="float-start mt-1">Atk {{ $title }}</h6>
    </x-slot>
    <x-slot name="cardBody">
        <form action="{{ route('atk.save_stk_masuk') }}" method="post">
            @csrf
            <section class="row">
                <div class="col-lg-3 mt-4">
                    <label for="">Tanggal</label>
                    <input type="date" name="tgl" class="form-control" id=""
                        value="{{ date('Y-m-d') }}">
                </div>
                <div class="col-lg-3 mt-4">
                    <label for="">Invoice</label>
                    <input type="text" name="" class="form-control" id=""
                        value="STKM-{{ $invoice }}" readonly>
                </div>
                <div class="col-lg-12 mt-4">
                    <table class="table table-hover" x-data="{
                        rows: [{id: 1, produk: ''}],
                        loadprodukjer: function(row) {
                            $.ajax({
                                type: 'GET',
                                url: '{{ route("atk.load_produk_stok") }}',
                                success: function(r) {
                                    $(`#load_produk_stok_${row.id}`).html(r);
                                    $('.select2_add').select2();
                                }
                            });
                        }
                    }">
                        <thead>
                            <tr>
                                <th class="dhead">Produk (Satuan)</th>
                                <th class="dhead" width="150">Stok Masuk</th>
                                <th class="dhead" width="250">Total Rp</th>
                                <th class="dhead">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(row, index) in rows" :key="index">
                                <tr>
                                    <td>
                                        <div :id="`load_produk_stok_${row.id}`" x-html="loadprodukjer(row)"></div>
                                    </td>
                                    <td><input type="text" class="form-control" name="debit[]"></td>
                                    <td><input type="text" name="total_rp[]" class="form-control"></td>
                                    <td style="vertical-align: top;">
                                        <button @click="rows.pop()" type="button" class="btn rounded-pill remove_baris" count="1"><i
                                                class="fas fa-trash text-danger"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4">
                                    <button @click="rows.push({id: rows.length + 1, produk: ''}); loadprodukjer(rows[rows.length - 1]);" type="button" class="btn btn-block btn-lg tbh_baris"
                                        style="background-color: #F4F7F9; color: #8FA8BD; font-size: 14px; padding: 13px;">
                                        <i class="fas fa-plus"></i> Tambah Baris Baru
                                    </button>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="btn-group float-end dropdown me-1 mb-1">

                        <button type="submit" name="simpan" value="simpan" class=" btn btn-primary button-save">
                            Simpan
                        </button>
                        <button class="float-end btn btn-primary btn_save_loading" type="button" disabled hidden>
                            <span class="spinner-border spinner-border-sm " role="status" aria-hidden="true"></span>
                            Loading...
                        </button>
                    </div>
                </div>
            </section>
        </form>

        <form action="{{ route('atk.save') }}" id="formTambah">
            @csrf
            <x-theme.modal title="Pilih Tahun" idModal="tambah" size="modal-lg">
                <div class="row">
                    <div class="col-lg-2">
                        <label for="">CFM</label>
                        <input type="text" class="form-control" name="cfm">
                    </div>
                    <div class="col-lg-4">
                        <label for="">Kategori</label>
                        <select name="kategori_id" id="" class="select2">
                            <option value="">Pilih Kategori</option>
                            @foreach ($kategori as $k)
                                <option value="{{ $k->id_kategori }}">{{ $k->nm_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <label for="">Nama Atk</label>
                        <input type="text" class="form-control" name="nm_atk">
                    </div>
                    <div class="col-lg-2">
                        <label for="">Satuan</label>
                        <select name="satuan_id" id="" class="select2">
                            <option value="">Pilih Satuan</option>
                            @foreach ($satuan as $s)
                                <option value="{{ $s->id_satuan }}">{{ $s->nm_satuan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4 mt-2">
                        <label for="">Upload Foto</label>
                        <input type="file" class="form-control" name="foto">
                    </div>
                    <div class="col-lg-4 mt-2">
                        <label for="">Kontrol Stok</label>
                        <br>
                        <input type="radio" class="mt-2" name="kontrol_stok" id="" value="Y"> Iya
                        &nbsp;
                        <input type="radio" class="mt-2" name="kontrol_stok" id="" value="T">
                        Tidak
                    </div>
                </div>

            </x-theme.modal>
        </form>

        @section('scripts')
            <script>
                $(document).ready(function() {
                    loadprodukjer()


                    $(document).on('change', '.pilihProduk', function(e) {
                        const val = $(this).val();
                        if (val == 'tambah') {
                            $("#tambah").modal('show')

                        }
                    })


                });

                function loadprodukjer() {
                    $.ajax({
                        type: "GET",
                        url: "{{ route('atk.load_produk_stok') }}",
                        success: function(r) {
                            $(".load_produk_stok").html(r);
                            $('.select2_add').select2()
                        }
                    });
                }
                $(document).on('submit', '#formTambah', function(e) {
                    e.preventDefault();
                    const link = $(this).attr('action')
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: "POST",
                        url: link,
                        data: $(this).serialize(),
                        success: function(r) {
                            loadprodukjer()
                            $("#tambah").modal('hide')
                        }
                    });

                })
            </script>
        @endsection
    </x-slot>


</x-theme.app>
