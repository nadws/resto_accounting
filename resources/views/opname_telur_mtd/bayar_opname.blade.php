<x-theme.app title="{{$title}}" table="Y" sizeCard="12">

    <x-slot name="cardHeader">
        <div class="row justify-content-end">
            <div class="col-lg-2">

            </div>
        </div>

    </x-slot>


    <x-slot name="cardBody">
        <form action="{{route('save_bayar_opname')}}" method="post" class="save_jurnal">
            @csrf
            <section class="row">
                <div class="col-lg-2 col-6">
                    <label for="">Tanggal</label>
                    <input type="date" class="form-control tgl_nota" name="tgl" value="{{$invoice2->tgl}}">
                </div>
                <div class="col-lg-2 col-6">
                    <label for="">No Nota</label>
                    <input type="text" class="form-control nota_bk" name="no_nota" value="{{$nota}}" readonly>
                </div>
                <div class="col-lg-2 col-6">
                    <label for="">Customer</label>
                    <input type="text" class="form-control" name="customer" value="Opname{{$invoice2->urutan_customer}}"
                        readonly>
                </div>

                <div class="col-lg-12">
                    <hr style="border: 1px solid black">
                </div>
                <div class="col-lg-12">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="dhead" width="2%">#</th>
                                <th class="dhead" width="10%">Produk</th>
                                <th class="dhead" width="10%" style="text-align: right">Pcs</th>
                                <th class="dhead" width="10%" style="text-align: right">Kg</th>
                                <th class="dhead" width="10%" style="text-align: right">Ikat</th>
                                <th class="dhead" width="10%" style="text-align: right">Kg(-rak)</th>
                                <th class="dhead" width="10%" style="text-align: right">Rp Satuan</th>
                                <th class="dhead" width="10%" style="text-align: right">Total Rp</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invoice as $no => $a)
                            <tr class="baris{{$no+1}}">
                                <td>{{$no+1}}</td>
                                <td>
                                    <select name="id_produk[]" class="form-control" required disabled>
                                        <option value="">-Pilih Produk-</option>
                                        @foreach ($produk as $p)
                                        <option value="{{$p->id_produk_telur}}" {{$a->id_produk == $p->id_produk_telur ?
                                            'selected' :
                                            ''}}>{{$p->nm_telur}}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="id_invoice_telur[]" value="{{$a->id_invoice_telur}}">
                                </td>
                                <td align="right">
                                    {{number_format($a->pcs,0,',','.')}}
                                </td>
                                <td align="right">
                                    {{number_format($a->kg,2,',','.')}}
                                </td>
                                <td align="right" class="ikat{{$no+1}}">{{number_format($a->pcs / 180,1)}}</td>
                                <td align="right" class="kgminrak{{$no+1}}">{{number_format($a->kg_jual,1)}}</td>
                                <td align="right">
                                    <input type="text" class="form-control rp_satuan rp_satuan{{$no+1}}"
                                        count="{{$no+1}}" style="text-align: right" required
                                        value="Rp {{number_format($a->rp_satuan,0,',','.')}}">

                                    <input type="hidden" class="kgminrakbiasa{{$no+1}}" name="kg[]" value="{{$a->kg}}">
                                    <input type="hidden" class="rp_satuanbiasa{{$no+1}}" name="rp_satuan[]"
                                        value="{{$a->rp_satuan}}">
                                    <input type="hidden" class="ttl_rpbiasa ttl_rpbiasa{{$no+1}}" name="total_rp[]"
                                        value="{{$a->total_rp}}">


                                </td>
                                <td align="right" class="ttl_rp{{$no+1}}">Rp {{number_format($a->total_rp,2,',','.')}}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="col-lg-5">

                </div>
                <div class="col-lg-7">

                    <hr style="border: 1px solid blue">
                    <div class="row">
                        <div class="col-lg-6">
                            <h6>Total</h6>
                        </div>
                        <div class="col-lg-6">
                            <h6 class="total float-end">Rp {{number_format($invoice2->total_rp,2,',','.')}}</h6>
                            <input type="hidden" class="total_semua_biasa" name="total_penjualan"
                                value="{{$invoice2->total_rp}}">
                        </div>
                    </div>





                </div>
            </section>
    </x-slot>
    <x-slot name="cardFooter">
        <button type="submit" class="float-end btn btn-primary button-save ">Simpan</button>
        <button class="float-end btn btn-primary btn_save_loading" type="button" disabled hidden>
            <span class="spinner-border spinner-border-sm " role="status" aria-hidden="true"></span>
            Loading...
        </button>
        <a href="{{route('jurnal')}}" class="float-end btn btn-outline-primary me-2">Batal</a>
        </form>
    </x-slot>



    @section('scripts')


    <script>
        $(document).ready(function () {
            $(document).on("keyup", ".rp_satuan", function () {
                var count = $(this).attr("count");
                var input = $(this).val();		
                input = input.replace(/[^\d\,]/g, "");
                input = input.replace(".", ",");
                input = input.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
                
                if (input === "") {
                    $(this).val("");
                    $('.rp_satuanbiasa' + count).val(0)
                } else {
                    $(this).val("Rp " + input);
                    input = input.replaceAll(".", "");
                    input2 = input.replace(",", ".");
                    $('.rp_satuanbiasa' + count).val(input2) 
                }
                var kg_jual = $('.kgminrakbiasa' + count).val();
                total = parseFloat(input2) * parseFloat(kg_jual);
                var totalRupiah = total.toLocaleString("id-ID", {
                    style: "currency",
                    currency: "IDR",
                });
                $('.ttl_rp' + count).text(totalRupiah);
                
                $('.ttl_rpbiasa' + count).val(total);

                var total_all = 0;
                $(".ttl_rpbiasa").each(function () {
                    total_all += parseFloat($(this).val());
                });
                var totalRupiahall = total_all.toLocaleString("id-ID", {
                    style: "currency",
                    currency: "IDR",
                });

                var total_kredit = 0;
                $(".kredit_biasa").each(function(){
                    total_kredit += parseFloat($(this).val());
                });
                var total_all_kredit = total_all + total_kredit;


                var totalkreditall = total_all_kredit.toLocaleString("id-ID", {
                    style: "currency",
                    currency: "IDR",
                });
                $(".total").text(totalRupiahall)
                $(".total_kredit").text(totalkreditall)
                $(".total_semua_biasa").val(total_all)


                
                
                    
            });
            aksiBtn("form");
            $("form").on("keypress", function (e) {
                if (e.which === 13) {
                    e.preventDefault();
                    return false;
                }
            });
            



        });
    </script>


    @endsection
</x-theme.app>