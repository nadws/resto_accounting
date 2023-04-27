<style>
    .dhead {
        background-color: #435EBE !important;
        color: white;
    }

    .dborder {
        border-color: #435EBE
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <table class="table table-hover table-bordered dborder">
            <thead>
                <tr>
                    <th class="dhead">Nama Proyek</th>
                    <th class="dhead">Tanggal Proyek</th>
                    <th class="dhead">Tanggal Estimasi</th>
                    <th class="dhead">Manager</th>
                    <th class="dhead" style="text-align: right">Biaya Estimasi</th>
                    <th class="dhead" style="text-align: right">Biaya Real</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{$proyek->nm_proyek}}</td>
                    <td>{{date('d-m-Y',strtotime($proyek->tgl))}}</td>
                    <td>{{date('d-m-Y',strtotime($proyek->tgl_estimasi))}}</td>
                    <td>{{$proyek->manager_proyek}}</td>
                    <td style="text-align: right">{{number_format($proyek->biaya_estimasi,0)}}</td>
                    <td style="text-align: right">{{number_format($jurnal->debit,0)}}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="col-lg-8">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th></th>
                    <th>Nama Kelompok</th>
                    <th>Umur</th>
                    <th>Barang</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($kelompok as $k)
                <tr>
                    <td><input type="radio" name="id_kelompok" id=""></td>
                    <td>{{$k->nm_kelompok}}</td>
                    <td>{{$k->umur}}</td>
                    <td>{{$k->barang_kelompok}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>