<style>
    .dhead {
        background-color: #435EBE !important;
        color: white;
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <table class="table table-bordered" id="table2">
            <thead>
                <tr>
                    <th class="dhead">No</th>
                    <th class="dhead">Nama Akun</th>
                    <th class="dhead">Ket</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($akun as $no => $a)
                <tr>
                    <td>{{$no+1}}</td>
                    <td>{{$a->nm_akun}}</td>
                    <td>
                        <span class="badge {{ $a->id_klasifikasi == '1' ? 'bg-danger' : 'bg-success' }}">
                            {{ $a->id_klasifikasi == '1' ? 'Tidak Masuk' : 'Masuk' }}
                        </span>
                    </td>
                </tr>
                @endforeach

            </tbody>
        </table>
    </div>
</div>