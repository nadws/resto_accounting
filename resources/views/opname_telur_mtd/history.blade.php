<style>
    .dhead {
        background-color: #435EBE !important;
        color: white;
    }
</style>
<div class="row">
    <div class="col-lg-6">
        <form id="history_serach_opname_mtd">
            <div class="row">
                <div class="col-lg-5">
                    <label for="">Dari</label>
                    <input type="date" class="form-control tgl1" name="tgl1" value="{{$tgl1}}">
                </div>
                <div class="col-lg-5">
                    <label for="">Sampai</label>
                    <input type="date" class="form-control tgl2" name="tgl2" value="{{$tgl2}}">
                </div>
                <div class="col-lg-2">
                    <label for="">Aksi</label>
                    <br>
                    <button type="submit" class="btn btn-sm btn-primary">Search</button>
                </div>
            </div>
        </form>
    </div>

</div>
<br>
<br>
<table class="table table-bordered">
    <thead>
        <tr>
            <th class="dhead" width="5">#</th>
            <th class="dhead">Tanggal</th>
            <th class="dhead">No Nota</th>
            <th class="dhead">Produk</th>
            <th class="dhead">Pcs</th>
            <th class="dhead">Kg</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($invoice as $no => $i)
        <tr>
            <td>{{$no+1}}</td>
            <td>{{tanggal($i->tgl)}}</td>
            <td>{{$i->nota_transfer}}</td>
            <td>{{$i->nm_telur}}</td>
            <td>{{number_format($i->pcs,0) }}</td>
            <td>{{number_format($i->kg,2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>