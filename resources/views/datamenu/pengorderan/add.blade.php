<x-theme.app title="{{ $title }}" table="Y" sizeCard="12">

    <x-slot name="cardHeader">
        <div class="row justify-content-end">

            <div class="col-lg-6">
                <h6 class="float-start mt-1">{{ $title }} </h6>
            </div>
            <div class="col-lg-6">
                {{-- <a href="{{ route('controlflow') }}" class="btn btn-primary float-end"><i class="fas fa-home"></i></a> --}}
            </div>
        </div>

    </x-slot>

    <style>
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #000000;
            line-height: 36px;
            /* font-size: 12px; */
            width: 150px;

        }
    </style>

    <x-slot name="cardBody">
        <form action="{{ route('pengorderan.create') }}" method="post" id="myForm" class="save_jurnal container"
            x-data="{
                openRows: [],
            }">
            @csrf
            <div class="row">
                <div class="col-lg-2 col-sm-6 col-md-6">
                    <div class="form-group">
                        <label for="" class="">Tanggal</label>
                        <input value="{{ date('Y-m-d') }}" type="date" name="tgl" class="form-control">
                    </div>
                </div>
                <div class="col-lg-2 col-sm-6 col-md-6">
                    <div class="form-group">
                        <label for="" class="">No Nota</label>
                        <input readonly type="text" value="PO-{{ $no_po }}" name=""
                            class="form-control">
                        <input type="hidden" value="{{ $no_po }}" name="no_nota" class="form-control">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-md-6">
                    <div class="form-group">
                        <label for="" class="">Keterangan</label>
                        <input type="text" name="ket" placeholder="keterangan" class="form-control">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-md-6">
                    <div class="form-group">
                        <label for="" class="">Aksi</label><br>
                        <button class="btn btn-primary" onclick="submitForm()">Simpan</button>

                    </div>
                </div>

            </div>
            <hr>
            <h6>Kategori</h6>
            @foreach ($kategori as $i => $k)
                <div class="row ">
                    <div class="col-lg-3">
                        <table class="table table-stripped">
                            <thead>
                                <tr
                                    x-on:click="openRows.includes({{ $i }}) ? openRows = openRows.filter(item => item !== {{ $i }}) : openRows.push({{ $i }})">
                                    <th class="dhead">{{ strtoupper($k->nm_kategori) }}</th>
                                    <th class="dhead text-end"><i class="fas fa-caret-down"></i></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="col-lg-3" x-show="openRows.includes({{ $i }})">
                        <div class="form-group">
                            <input id="inputBarang" type="text" class="form-control" placeholder="pencarian">
                        </div>
                    </div>
                </div>

                @php
                    $barang = DB::select("SELECT (d.debit - d.kredit) as stok,a.nm_bahan, b.nm_satuan, a.id_list_bahan as id_bahan
                            FROM tb_list_bahan a
                            JOIN tb_satuan b ON a.id_satuan = b.id_satuan
                            LEFT join (
                                SELECT b.id_bahan, SUM(b.debit) as debit, sum(b.kredit) as kredit
                                FROM stok_bahan as b 
                                group by b.id_bahan
                            ) as d on d.id_bahan = a.id_list_bahan
                            WHERE a.id_kategori = '$k->id_kategori_bahan';");

                @endphp
                <div class="row " x-transition x-show="openRows.includes({{ $i }})">

                    <div class="col-lg-6">
                        <table class="table table-stripped" id="tblBarang">
                            <thead>
                                <tr>
                                    <th class="dhead">No</th>
                                    <th class="dhead">Nama Barang</th>
                                    <th class="dhead text-end" width="90">Qty Stok</th>
                                    <th class="dhead text-center" width="80">Satuan Resep</th>
                                    <th class="dhead text-end" width="90">Qty</th>
                                    <th class="dhead text-center" width="80">Satuan Beli</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($barang as $ib => $b)
                                    <tr>
                                        <td>{{ $ib + 1 }}</td>
                                        <td>{{ ucwords($b->nm_bahan) }}</td>
                                        <td align="right">
                                            {{ $b->stok ?? 0 }}
                                        </td>
                                        <td align="center">{{ strtoupper($b->nm_satuan) }}</td>
                                        <td align="right">
                                            <input type="hidden" name="id_bahan[]" value="{{ $b->id_bahan }}">
                                            <input type="text" name="qty[]" class="form-control text-end selectAll"
                                                value="0">
                                        </td>
                                        <td>
                                            <div class="load_selectSatuan"></div>

                                        </td>


                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </form>

    </x-slot>
    <x-slot name="cardFooter">


        <a href="{{ route('pengorderan.index') }}" class="float-end btn btn-outline-primary me-2">Batal</a>



        <form id="form_tbh_satuan">
            <x-theme.modal title="Tambah Satuan" idModal="modal_tbh_satuan">
                <div class="form-group">
                    <label for="">Nama Satuan</label>
                    <input type="text" class="form-control nm_satuan">
                </div>
            </x-theme.modal>
        </form>
    </x-slot>
    @section('scripts')
        <script>
            $('.select2sbeli').select2()
            pencarian('inputBarang', 'tblBarang')

            function submitForm() {
                // Iterate through each "qty" input and remove rows where qty is 0
                $('input[name="qty[]"]').each(function() {
                    var qtyValue = $(this).val();
                    if (qtyValue === "0") {
                        $(this).closest('tr').remove();
                    }
                });

                // Now, submit the form
                $('#myForm').submit();
            }


            function loadSelectSatuan() {
                $.ajax({
                    type: "GET",
                    url: "{{ route('pengorderan.load_selectSatuan') }}",
                    success: function(r) {
                        $('.load_selectSatuan').html(r);
                        $('.select23').select2()

                    }
                });
            }
            loadSelectSatuan()
            $(document).on('change', '.selectSatuan', function() {
                const nilai = $(this).val()
                if (nilai == 'tambah') {
                    $('#modal_tbh_satuan').modal('show')

                }
            })
            $('#form_tbh_satuan').submit(function(e) {
                e.preventDefault();
                const nm_satuan = $('.nm_satuan').val()
                $.ajax({
                    type: "GET",
                    url: "{{ route('pengorderan.createSatuan') }}",
                    data: {
                        nm_satuan: nm_satuan
                    },
                    success: function(r) {
                        loadSelectSatuan()
                        $('#modal_tbh_satuan').modal('hide')
                    }
                });
            });
        </script>
    @endsection
</x-theme.app>
