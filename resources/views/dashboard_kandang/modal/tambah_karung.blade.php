<form action="{{ route('dashboard_kandang.tambah_karung') }}" method="post">
    @csrf
    <x-theme.modal size="modal-lg" title="Tambah Karung" idModal="tambah_karung">
        <div class="row">
            <div class="col-lg-12">
                <table class="table">
                    <tr>
                        <th class="dhead">Tanggal</th>
                        <th class="dhead">Kandang</th>
                        <th class="dhead">Karung</th>
                    </tr>
                    <tr>
                        <td>
                            <input type="date" readonly value="{{ date('Y-m-d') }}" name="tgl"
                                class="form-control">
                        </td>
                        <td>
                            <input type="hidden" value="1" name="id_kandang" id="id_kandang_tambah_karung">
                            <input readonly type="text" class="form-control" value="Kandang A"
                                id="nm_kandang_tambah_karung">
                        </td>
                        <td>
                            <input autofocus type="text" class="form-control" name="karung">
                        </td>
                     
                    </tr>
                </table>

            </div>


        </div>
    </x-theme.modal>
</form>
