<x-theme.button modal="Y" idModal="view" icon="fa-filter" addClass="float-end" teks="" />
<form action="" method="get">
    <x-theme.modal title="Filter Jurnal Umum" idModal="view">
        <div class="row">
            <div class="col-lg-12">

                <table width="100%" cellpadding="10px">
                    <tr>
                        <td>Tanggal</td>
                        <td colspan="2">
                            <select name="period" id="" class="form-control filter_tgl">
                                <option value="daily">Hari ini</option>
                                <option value="weekly">Minggu ini</option>
                                <option value="mounthly">Bulan ini</option>
                                <option value="costume">Custom</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="costume_muncul">
                        <td></td>
                        <td>
                            <label for="">Dari</label>
                            <input type="date" name="tgl1" class="form-control tgl">
                        </td>
                        <td>
                            <label for="">Sampai</label>
                            <input type="date" name="tgl2" class="form-control tgl">
                        </td>
                    </tr>
                    <tr class="bulan_muncul">
                        <td></td>
                        <td>
                            <label for="">Bulan</label>
                            <select name="bulan" id="bulan" class="selectView bulan">
                                @php
                                    $listBulan = DB::table('bulan')->get();
                                @endphp

                                @foreach ($listBulan as $l)
                                    <option value="{{ $l->bulan }}"
                                        {{ (int) date('m') == $l->bulan ? 'selected' : '' }}>{{ $l->nm_bulan }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <label for="">Tahun</label>
                            <select name="tahun" id="" class="selectView bulan">
                                <option value="2023">2023</option>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

    </x-theme.modal>
</form>
