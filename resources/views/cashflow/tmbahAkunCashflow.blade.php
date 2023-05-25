<div class="row">
    <div class="col-lg-10">
        <label for="">Akun</label>
        <input type="hidden" class="id_subklasifikasi" name="id_sub_klasifikasi_akun" value="{{$id_sub}}">
        <select name="id_akun" id="" class="select">
            <option value="">-Pilih Akun-</option>
            @foreach ($akun as $a)
            <option value="{{$a->id_akun}}">{{$a->nm_akun}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-lg-2">
        <label for="">Aksi</label> <br>
        <button type="submit" class="btn btn-sm btn-primary">Save</button>
    </div>
    <div class="col-lg-12">
        <hr style="border: 1px solid black">
    </div>
    <div class="col-lg-12">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Akun</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($akun_cashflow as $a)
                <tr>
                    <td>{{$a->nm_akun}}</td>
                    <td><a href="#" class="btn btn-sm btn-danger delete_akun" id_akun="{{$a->id_akun}}"
                            id_sub="{{$id_sub}}"><i class="fas fa-trash-alt"></i></a></td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>
</div>