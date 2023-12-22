<x-theme.app title="{{ $title }}" table="Y" sizeCard="10">
    <x-slot name="cardHeader">
        <h6 class="float-start mt-1">Atk {{ $title }}</h6>
    </x-slot>
    <x-slot name="cardBody">
        <form action="{{ route('bahan.save_stk_masuk') }}" method="post">
            @csrf
            <section class="row">
                <div class="col-lg-3 mt-4">
                    <label for="">Tanggal</label>
                    <input type="date" name="tgl" class="form-control" id=""
                        value="{{ date('Y-m-d') }}">
                </div>
                <div class="col-lg-3 mt-4">
                    <label for="">Invoice</label>
                    <input type="hidden" name="invoice"
                        value="{{ $invoice }}">
                    <input type="text" name="" class="form-control" id=""
                        value="BHNMSK-{{ $invoice }}" readonly>
                </div>
                <div class="col-lg-12 mt-4">
                    <table class="table table-hover" x-data="{
                        rows: ['1']
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
                            <tr>
                                <td>
                                    <div class="load_produk_stok1"></div>
                                </td>
                                <td><input x-mask:dynamic="$money($input)" type="text" class="form-control text-end" name="debit[]"></td>
                                <td><input x-mask:dynamic="$money($input)" type="text" name="total_rp[]" class="form-control text-end"></td>
                                <td style="vertical-align: top;">

                                </td>
                            </tr>
                        </tbody>
                        <tbody id="tb_baris">

                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4">
                                    <button type="button" class="btn btn-block btn-lg tbh_baris"
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


        <form action="{{ route('bahan.save') }}" id="formTambah">
            @csrf
            <x-theme.modal title="Tambah Bahan" idModal="tambah" size="modal-lg">
                <div class="row">
                    <div class="col-lg-4">
                        <label for="">Nama Bahan</label>
                        <input type="text" class="form-control" name="nm_bahan">
                    </div>
                    <div class="col-lg-4">
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

                </div>

            </x-theme.modal>
        </form>
        @section('scripts')
            <script>
                var count = 1

                $('.select2-prod').select2()
                    loadprodukjer(count)


                    $(document).on('change', '.pilihProduk', function(e) {
                        const val = $(this).val();
                        if (val == 'tambah') {
                            $("#tambah").modal('show')

                        }
                    })
                function loadprodukjer(count) {
                    $.ajax({
                        type: "GET",
                        url: "{{ route('bahan.load_produk_stok') }}",
                        success: function(r) {
                            $(".load_produk_stok"+count).html(r);
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

                $(document).on('click', '.tbh_baris', function() {
                    count += 1
                    $.ajax({
                        type: "GET",
                        url: "{{ route('bahan.stok_tbh_baris') }}?count=" + count,
                        success: function(r) {
                            $("#tb_baris").append(r);
                            loadprodukjer(count)
                        }
                    });
                })

                $(document).on('click', '.remove_baris', function() {
                    var delete_row = $(this).attr("count");
                    $(".baris" + delete_row).remove();

                })
            </script>
        @endsection
    </x-slot>


</x-theme.app>
