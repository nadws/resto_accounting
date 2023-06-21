<form action="{{ route('dashboard_kandang.tambah_telur') }}" method="post">
    @csrf
    <x-theme.modal title="Tambah Telur" size="modal-lg" idModal="tambah_telur">
        <div class="row">
            <div class="col-lg-6">
                <table class="table">
                    <tr>
                        <th class="dhead">Tanggal</th>
                        <th class="dhead">Kandang</th>
                    </tr>
                    <tr>
                        <td>
                            <input type="date" readonly value="{{ date('Y-m-d') }}" name="tgl" class="form-control">
                        </td>
                        <td>
                            <input type="hidden" value="1" name="id_kandang" id="id_kandang_tambah">
                            <input readonly type="text" class="form-control" value="Kandang A" id="nm_kandang_tambah">
                        </td>
                    </tr>
                </table>
                
            </div>
            <div class="col-lg-12">
                <table class="table">
                    <tr>
                        <th class="dhead" width="25%">Produk</th>
                        <th class="dhead" width="15%">Pcs</th>
                        <th class="dhead" width="15%">Kg</th>
                        <th class="dhead" width="15%">Ikat</th>
                        <th class="dhead" width="15%">Rak</th>
                        <th class="dhead" width="15%">Potongan</th>
                    </tr>
                    @foreach ($telur as $i => $d)
                    <input type="hidden" name="id_telur[]" value="{{ $d->id_produk_telur }}">
                        <tr>
                            <td>{{ ucwords($d->nm_telur) }}</td>
                            <td>
                                <input value="0" type="text" name="pcs[]" class="form-control pcs pcs{{$i+1}}" count="{{$i+1}}">
                            </td>
                            <td>
                                <input value="0" type="text" name="kg[]" class="form-control">
                            </td>
                            <td>
                                <input type="text" value="0" class="form-control ikat{{$i+1}}" name="ikat[]">
                            </td>
                            <td>
                                <input type="text" name="rak[]" value="0" class="form-control rak rak{{$i+1}}"  count="{{$i+1}}">
                            </td>
                            <td>
                                <input type="text" value="0" readonly class="form-control potongan{{$i+1}}" name="potongan[]">
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </x-theme.modal>
</form>