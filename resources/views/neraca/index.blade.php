<div class="card">
    <div class="card-header">
        <h6 class="text-primary">Laporan Neraca</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-6">
                @php
                    $ttl_kas = 0;
                    foreach ($kas as $k) {
                        $ttl_kas += $k->debit - $k->kredit;
                    }
                @endphp
                <table class="table table-bordered" x-data="{
                    openkas: false,
                }">
                    <tr>
                        <th colspan="2" class="text-center">Aktiva</th>
                    </tr>
                    <tr>
                        <td colspan="2" class="fw-bold">Aktiva lancar</td>
                    </tr>
                    <tr>
                        <td>KAS <a href="javascript:void(0);" class="float-end" @click="openkas = ! openkas"><i
                                    class=" fas fa-caret-down"></i></a></td>
                        <td class="text-end">Rp {{ number_format($ttl_kas, 0) }}</td>
                    </tr>
                    @foreach ($kas as $k)
                        <tr x-show="openkas">
                            <td>&nbsp;&nbsp; <a href="">{{ $k->nm_akun }}</a></td>
                            <td class="text-end">Rp {{ number_format($k->debit - $k->kredit) }} </td>
                        </tr>
                    @endforeach

                    <tr>
                        <td>BANK</td>
                        <td class="text-end">Rp 100,000</td>
                    </tr>
                    <tr>
                        <td>PIUTANG DAGANG</td>
                        <td class="text-end">Rp 100,000</td>
                    </tr>
                    <tr>
                        <td>PERSEDIAN</td>
                        <td class="text-end">Rp 100,000</td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Jumlah Aktiva Lancar</td>
                        <td class="text-end fw-bold">Rp 400,000</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="fw-bold">Aktiva Tetap</td>
                    </tr>
                </table>
            </div>
            <div class="col-lg-6">
                <table class="table table-bordered">
                    <tr>
                        <th colspan="2" class="text-center">Passiva</th>
                    </tr>
                    <tr>
                        <td class="fw-bold">Hutang</td>
                    </tr>
                    <tr>
                        <td>Hutang 1</td>
                        <td class="text-end">Rp 100,000</td>
                    </tr>
                    <tr>
                        <td>Hutang 2</td>
                        <td class="text-end">Rp 100,000</td>
                    </tr>
                    <tr>
                        <td>Hutang 3</td>
                        <td class="text-end">Rp 100,000</td>
                    </tr>
                    <tr>
                        <td>hutang 4</td>
                        <td class="text-end">Rp 100,000</td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Jumlah Kewajiban Lancar</td>
                        <td class="text-end fw-bold">Rp 400,000</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
