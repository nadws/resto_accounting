<div class="card">
    <div class="card-header">
        <form id="history_neraca">
            <div class="row">
                <div class="col-lg-6">
                    <h6 class="text-primary">Laporan Neraca </h6>
                </div>
                <div class="col-lg-3">
                    <select name="" id="bulan" class="select">
                        @foreach ($bulan as $b)
                            <option value="{{ $b->bulan }}" {{ $bulan_values == $b->bulan ? 'selected' : '' }}>
                                {{ $b->nm_bulan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2">
                    <select name="" id="tahun" class="select">
                        <option value="2023">2023</option>
                    </select>
                </div>
                <div class="col-lg-1">
                    <button class="btn btn-sm btn-primary"><i class="fas fa-search"></i></button>
                </div>
            </div>
        </form>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="text-center">
                    <div id="loading2" class="spinner-border text-center text-success "
                        style="width: 6rem; height: 6rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="show2" style="display: none;">

            <div class="col-lg-6">
                @php
                    $ttl_kas = 0;
                    foreach ($kas as $k) {
                        $ttl_kas += $k->debit - $k->kredit;
                    }

                    $ttl_bank = 0;
                    foreach ($bank as $k) {
                        $ttl_bank += $k->debit - $k->kredit;
                    }
                    $ttl_piutang = 0;
                    foreach ($piutang as $k) {
                        $ttl_piutang += $k->debit - $k->kredit;
                    }
                    $ttl_persediaan = 0;
                    foreach ($persediaan as $k) {
                        $ttl_persediaan += $k->debit - $k->kredit;
                    }
                    $ttl_aktiva_tetap = 0;
                    foreach ($aktiva_tetap as $k) {
                        $ttl_aktiva_tetap += $k->debit - $k->kredit;
                    }
                    $ttl_akumlasi_aktiva = 0;
                    foreach ($akumlasi_aktiva as $k) {
                        $ttl_akumlasi_aktiva += $k->debit - $k->kredit;
                    }
                    $ttl_hutang = 0;
                    foreach ($hutang as $k) {
                        $ttl_hutang += $k->kredit - $k->debit;
                    }
                    $ttl_ekuitas = 0;
                    foreach ($ekuitas as $k) {
                        $ttl_ekuitas += $k->kredit - $k->debit;
                    }
                @endphp
                <table class="table table-bordered" x-data="{
                    openkas: false,
                    openbank: false,
                    openpiutang: false,
                    openpersediaan: false,
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
                        <td>BANK <a href="javascript:void(0);" class="float-end" @click="openbank = ! openbank"><i
                                    class=" fas fa-caret-down"></i></a></td>
                        <td class="text-end">Rp {{ number_format($ttl_bank, 0) }}</td>
                    </tr>
                    @foreach ($bank as $k)
                        <tr x-show="openbank">
                            <td>&nbsp;&nbsp; <a href="">{{ $k->nm_akun }}</a></td>
                            <td class="text-end">Rp {{ number_format($k->debit - $k->kredit) }} </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td>PIUTANG DAGANG <a href="javascript:void(0);" class="float-end"
                                @click="openpiutang = ! openpiutang"><i class=" fas fa-caret-down"></i></a></td>
                        <td class="text-end">Rp {{ number_format($ttl_piutang, 0) }}</td>
                    </tr>
                    @foreach ($piutang as $k)
                        <tr x-show="openpiutang">
                            <td>&nbsp;&nbsp; <a href="">{{ $k->nm_akun }}</a></td>
                            <td class="text-end">Rp {{ number_format($k->debit - $k->kredit) }} </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td>PERSEDIAN<a href="javascript:void(0);" class="float-end"
                                @click="openpersediaan = ! openpersediaan"><i class=" fas fa-caret-down"></i></a></td>
                        <td class="text-end">Rp {{ number_format($ttl_persediaan, 0) }}</td>
                    </tr>
                    @foreach ($persediaan as $k)
                        <tr x-show="openpersediaan">
                            <td>&nbsp;&nbsp; <a href="">{{ $k->nm_akun }}</a></td>
                            <td class="text-end">Rp {{ number_format($k->debit - $k->kredit) }} </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Jumlah Aktiva Lancar</td>
                        <td class="text-end fw-bold">Rp
                            {{ number_format($ttl_kas + $ttl_bank + $ttl_persediaan + $ttl_piutang, 0) }}</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="fw-bold">Aktiva Tetap</td>
                    </tr>
                    @foreach ($aktiva_tetap as $k)
                        <tr>
                            <td>&nbsp;&nbsp; <a href="">{{ $k->nm_akun }}</a></td>
                            <td class="text-end">Rp {{ number_format($k->debit - $k->kredit) }} </td>
                        </tr>
                    @endforeach
                    @foreach ($akumlasi_aktiva as $k)
                        <tr>
                            <td>&nbsp;&nbsp; <a href="">{{ $k->nm_akun }}(-)</a></td>
                            <td class="text-end">Rp {{ number_format($k->debit - $k->kredit) }} </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Nilai Buku</td>
                        <td class="text-end fw-bold">Rp
                            {{ number_format($ttl_akumlasi_aktiva - $ttl_aktiva_tetap, 0) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Jumlah Aktiva</td>
                        <td class="text-end fw-bold">Rp
                            {{ number_format($ttl_akumlasi_aktiva - $ttl_aktiva_tetap + ($ttl_kas + $ttl_bank + $ttl_persediaan + $ttl_piutang), 0) }}
                        </td>
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
                    @foreach ($hutang as $k)
                        <tr>
                            <td>&nbsp;&nbsp; <a href="">{{ $k->nm_akun }}</a></td>
                            <td class="text-end">Rp {{ number_format($k->kredit - $k->debit) }} </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>

                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Jumlah Kewajiban Lancar</td>
                        <td class="text-end fw-bold">Rp {{ number_format($ttl_hutang, 0) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Ekuitas</td>
                        <td class="text-end fw-bold">Rp </td>
                    </tr>
                    @foreach ($ekuitas as $k)
                        <tr>
                            <td>&nbsp;&nbsp; <a href="">{{ $k->nm_akun }}</a></td>
                            <td class="text-end">Rp {{ number_format($k->kredit - $k->debit) }} </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Total Ekuitas</td>
                        <td class="text-end fw-bold">Rp {{ number_format($ttl_ekuitas, 0) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Jumlah Passiva</td>
                        <td class="text-end fw-bold">Rp Rp {{ number_format($ttl_hutang + $ttl_ekuitas, 0) }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
