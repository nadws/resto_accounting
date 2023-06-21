<div class="row">
    <div class="col-lg-12">
        <table class="table">
            <tr>
                <th class="dhead">Tanggal</th>
                <th class="dhead">Kandang</th>
                <th class="dhead">Mati / Death</th>
                <th class="dhead">Jual / Culling</th>
            </tr>
            <tr>
                <td>
                    <input type="date" readonly value="{{ date('Y-m-d') }}" name="tgl"
                        class="form-control">
                </td>
                <td>
                    <input name="id_kandang" type="hidden" value="{{ $kandang->id_kandang }}"
                        >
                    <input readonly class="form-control" type="text" value="{{ $kandang->nm_kandang }}"
                        >
                </td>
                <td>
                    <input autofocus value="{{ $populasi->mati ?? 0 }}" type="text" class="form-control" name="mati">
                </td>
                <td>
                    <input value="{{ $populasi->jual ?? 0 }}" type="text" class="form-control" name="jual">
                </td>
            </tr>
        </table>

    </div>


</div>