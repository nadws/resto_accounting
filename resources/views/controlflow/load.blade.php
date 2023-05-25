<style>
    .dhead {
        background-color: #435EBE !important;
        color: white;
    }
</style>
<div class="row">

    <div class="col-lg-12">
        <table class="table table-bordered">
            <tr>
                <th class="dhead">Akun</th>
                <th class="dhead" style="text-align: right">Rupiah</th>
            </tr>
            <tr>
                <td colspan="2" class="fw-bold">Pendapatan</td>
            </tr>
            <tr>
                <td>
                    <a href="#" onclick="event.preventDefault();" class="tmbhakun" id_kategori_akun="3" jenis='1'>Kas
                        Kecil
                        Alpa</a>
                </td>
                <td align="right">Rp. </td>
            </tr>
            <tr>
                <td class="fw-bold">Total Pendapatan</td>
                <td class="fw-bold" align="right">Rp. 0</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            {{-- <tr>
                <td colspan="2"><a href="#" jenis='2' onclick="event.preventDefault();"
                        class="input_pendapatan fw-bold">Pengeluaran</a></td>
            </tr>
            <tr>
                <td>
                    <a href="#" onclick="event.preventDefault();" style="padding-left: 20px">ss</a>
                </td>
                <td align="right">Rp. 0</td>
            </tr>
            <tr>
                <td class="fw-bold">Total Pengeluaran</td>
                <td class="fw-bold" align="right">Rp. {{ number_format($total_p, 0) }}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr> --}}
            {{-- <tr>
                <td class="fw-bold">Arus Kas Bersih</td>
                <td class="fw-bold" align="right">Rp. {{ number_format($total_cash - $total_p, 0) }}</td>
            </tr> --}}
        </table>
    </div>
</div>