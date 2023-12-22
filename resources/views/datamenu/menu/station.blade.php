<div class="row">

    <div class="col-lg-12">
        <table class="table">
            <thead>
                <tr>
                    <th class="dhead">#</th>
                    <th class="dhead">Station</th>
                    <th class="dhead">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $no = 1;
                    $station = DB::table('tb_station')
                        ->where('id_lokasi', 1)
                        ->get();

                @endphp
                @foreach ($station as $s)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $s->nm_station }}</td>
                        <td>
                            <a id_station="{{ $s->id_station }}" class="btn btn-sm btn-danger delStation"><i
                                    class="fa fa-trash"></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
