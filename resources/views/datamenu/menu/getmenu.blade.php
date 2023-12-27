<div class="row">
    <div class="col-lg-12 mt-2">
        <table class="table">
            <thead>
                <tr>
                    <th class="dhead">No</th>
                    <th class="dhead">Katgeori</th>
                    <th class="dhead">Level</th>
                    <th class="dhead">Kode Menu</th>
                    <th class="dhead">Nama Menu</th>
                    <th class="dhead">Tipe</th>
                    <th class="dhead">Station</th>
                    <th class="dhead">Distribusi</th>
                    <th class="dhead"></th>
                    <th class="dhead text-center">Aktif</th>
                    <th class="dhead text-center">Resep</th>
                    <th class="dhead text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($menu as $no => $m)
                    @php
                        $harga = DB::table('tb_harga')
                            ->select('tb_harga.*', 'tb_distribusi.*')
                            ->join('tb_distribusi', 'tb_harga.id_distribusi', '=', 'tb_distribusi.id_distribusi')
                            ->where('id_menu', $m->id_menu)
                            ->get();
                    @endphp
                    <tr>
                        <td>{{ $no + $menu->firstItem() }}</td>
                        <td>{{ $m->kategori }}</td>
                        <td>{{ $m->handicap }} ({{ $m->point }} Point)</td>
                        <td>{{ $m->kd_menu }}</td>
                        <td>{{ $m->nm_menu }}</td>
                        <td>{{ $m->tipe }}</td>
                        <td>{{ $m->nm_station }}</td>
                        <td style="white-space: nowrap;">
                            @foreach ($harga as $h)
                                {{ $h->nm_distribusi }} <br>
                            @endforeach
                        </td>

                        <td>
                            @foreach ($harga as $h)
                                :{{ number_format($h->harga, 0) }} <br>
                            @endforeach
                        </td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input chekstatus" type="checkbox" id="flexSwitchCheckDefault"
                                    style="transform: scale(1.8);" {{ $m->aktif == 'on' ? 'checked' : '' }}
                                    id_menu="{{ $m->id_menu }}">
                            </div>
                        </td>
                        <td class="text-center">
                            <a href="#" class="btn btn-sm btn-primary resep" id_menu="{{ $m->id_menu }}"
                                data-bs-toggle="modal" data-bs-target="#resep"><i class="fas fa-clipboard"></i></a>
                        </td>
                        <td class="text-center">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#edit"
                                class="btn btn-sm btn-warning edit_menu" id_menu="{{ $m->id_menu }}"><i
                                    class="fas fa-edit"></i></a>

                            <a href="#" data-bs-toggle="modal" data-bs-target="#delete"
                                id_menu="{{ $m->id_menu }}" class="btn btn-sm btn-danger delete_menu"><i
                                    class="fas fa-trash-alt"></i></a>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
        <div class="float-start">
            <span>Total data : {{ $menu->total() }} Baris</span>
        </div>
        <div class="float-end mt-2">
            {!! $menu->links() !!}
        </div>
    </div>
</div>
