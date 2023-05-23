<style>
    .dhead {
        background-color: #435EBE !important;
        color: white;
    }
</style>
<table class="table" id="todo-table">
    <thead>
        <tr>
            <th class="dhead" colspan="2">Akun dan Kategori</th>
            <th class="dhead">Rp</th>
        </tr>
        <tr>
            <th colspan="2"><a href="#" class="btnSubKategori" jenis="1">Pemasukan</a></th>
            <th></th>
            {{-- <th>
                <button jenis="1" class="btn btn-sm btn-primary btnSubKategori"><i class="bi bi-plus-circle"></i>
                    Kategori</button>
            </th> --}}
        </tr>
    </thead>
    <tbody>
        <input type="hidden" class="id_sub_kategori" value="1">
        @foreach ($pemasukan as $n => $p)
        <tr class="todo-item" data-id="{{$n+1}}">
            <td width="2%"></td>
            <td><a href="#" class="btnSubKategoriAkun" id="{{$p->id}}">{{$p->sub_kategori}}</a></td>
            <td>Rp. 0</td>
            {{-- <td><button class="btn btn-sm btn-primary  btnSubKategoriAkun" id="1"><i class="bi bi-plus-circle"></i>
                    Akun</button></td> --}}
        </tr>
        @endforeach
    </tbody>
</table>