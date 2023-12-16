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
                    <table class="table table-hover">
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
                                    <select name="id_atk[]" id="" class="select2_add pilihProduk">
                                        <option value="">Pilih Produk</option>
                                        @foreach ($atk as $a)
                                            <option value="{{ $a->id_atk }}">{{ $a->nm_atk }}
                                                ({{ $a->nm_satuan }})
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="text" class="form-control" name="debit[]"></td>
                                <td><input type="text" name="total_rp[]" class="form-control"></td>
                                {{-- <td style="vertical-align: top;">
                                    <button type="button" class="btn rounded-pill remove_baris" count="1"><i
                                            class="fas fa-trash text-danger"></i>
                                    </button>
                                </td> --}}
                            </tr>

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



        @section('scripts')
            <script>
                $(document).ready(function () {
                    $(document).on('change', '.pilihProduk', function(){
                        // alert(1)
                    })

                    
                });
            </script>
        @endsection
    </x-slot>


</x-theme.app>
