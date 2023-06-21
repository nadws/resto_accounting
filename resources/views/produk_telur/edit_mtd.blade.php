<style>
    .dhead {
        background-color: #435EBE !important;
        color: white;
    }
</style>
<div class="row">
    <div class="col-lg-3">
        <label for="">Tanggal</label>
        <input type="date" class="form-control" value="{{$tgl}}">
    </div>
    <div class="col-lg-3">
        <label for="">Kandang</label>
        <input type="text" class="form-control" value="{{$kandang->nm_kandang}}" readonly>
    </div>
    <div class="col-lg-12">
        <hr style="border: 1px solid #435EBE">
    </div>
    <div class="col-lg-12">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="dhead" width="30%">Produk</th>
                    <th class="dhead" style="text-align: right" width="15%">Pcs</th>
                    <th class="dhead" style="text-align: right" width="15%">Kg</th>
                    <th class="dhead" style="text-align: right" width="10%">Ikat</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoice as $no => $i)
                <tr>
                    <td>{{$i->nm_telur}}</td>
                    <td>
                        <input type="text" style="text-align: right"
                            class="form-control pcs_mtd pcs_mtd{{$i->id_produk_telur}}"
                            id_produk_telur="{{$i->id_produk_telur}}" value="{{number_format($i->pcs,0,',','.')}}">
                    </td>
                    <td>
                        <input type="text" style="text-align: right" class="form-control"
                            value="{{number_format($i->kg,2,',','.')}}">
                    </td>
                    <td align="right"><input type="text" class="form-control ikat_mtd{{$i->id_produk_telur}}" value="{{empty($i->pcs) ? '0' :
                        number_format($i->pcs / 180,1)}}">
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>