<form action="" method="get">
    <x-theme.modal title="Add Akun" idModal="tambah-profit" size="modal-lg">
        <table class="table table-striped" id="table">
            <thead>
                <tr>
                    <th width="1%">#</th>
                    <th>Nama Akun</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td width="15%">
                        <input type="text" min="0" class="form-control" id="urutan">
                    </td>
                    <td>
                        <select id="id_akun" class="form-control select2-profit">
                            <option value="">- Pilih Akun -</option>
                            @foreach ($akun as $d)
                                <option value="{{ $d->id_akun }}">{{ ucwords($d->nm_akun) }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td width="15%">
                        <button type="button" class="btn btn-primary btn-sm" id="btnSave"><i class="fas fa-check"></i> Simpan</button>
                    </td>
                </tr>
            </tbody>
            <tbody>
                @foreach ($akunProfit as $a)
                <tr>
                    <td width="15%">
                        {{ $a->urutan }}
                    </td>
                    <td>
                        <select disabled id="id_akun" class="form-control">
                            <option value="">- Pilih Akun -</option>
                            @foreach ($akun as $d)
                                <option {{$a->id_akun == $d->id_akun ? 'selected' : ''}} value="{{ $d->id_akun }}">{{ ucwords($d->nm_akun) }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td width="15%">
                        <button type="button" class="btn btn-primary btn-sm" id="btnSave"><i class="fas fa-pen"></i></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </x-theme.modal>
</form>