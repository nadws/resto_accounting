<div class="card">
    <div class="card-header">
        <form id="history_neraca">
            <div class="row">
                <div class="col-lg-6">
                    <h6 class="text-success"> Neraca </h6>
                </div>
                <div class="col-lg-3">

                </div>
                <div class="col-lg-2">
                    <select name="" class="select_cashflow">
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
        {{-- <div class="row">
            <div class="col-lg-12">
                <div class="text-center">
                    <div id="loading_cashflow" class="spinner-border text-center text-success "
                        style="width: 6rem; height: 6rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="row" id="show_neraca">
            {{-- ini koding sum kalsifikasi --}}
            @php
                $klasifkasi = [
                    5 => 'kas',
                    6 => 'bank',
                    14 => 'persediaan',
                ];

                $total_per_bulan = [];
                foreach ($bulans as $d) {
                    $bln = $d->bulan;
                    $tgl1 = '2020-01-01';
                    $tgl2 = date('Y-m-t', strtotime("$thn-$bln-1"));
                    foreach ($klasifkasi as $k => $i) {
                        $kas = \App\Models\NeracaModel::GetKas($tgl1, $tgl2, $k);
                        $debit_kas = $kas->debit ?? 0;
                        $kredit_kas = $kas->kredit ?? 0;

                        $total_per_bulan[$bln][$i] = $debit_kas - $kredit_kas;
                    }
                }

                // Menambahkan total keseluruhan untuk KAS dan BANK
                $ttlKas = array_sum(array_column($total_per_bulan, 'kas'));

                $ttlBank = array_sum(array_column($total_per_bulan, 'bank'));
                $ttlPersediaan = array_sum(array_column($total_per_bulan, 'persediaan'));
                $totalPerBulan = [];
                foreach ($bulans as $d) {
                    $bln = $d->bulan;
                    $totalPerBulan[$bln] = 0; // Setiap bulan diinisialisasi dengan nilai 0
                }

                // Hitung total per bulan
                foreach ($total_per_bulan as $bulan => $nilai) {
                    $totalPerBulan[$bulan] += $nilai['kas'] + $nilai['bank'] + $nilai['persediaan'];
                }
                $totalSemuaLancar = $ttlKas + $ttlBank + $ttlPersediaan;

            @endphp

            {{-- ini koding per akun nya --}}
            @php
                $totalPerAkun = [];
                foreach ($bulans as $d) {
                    $bln = $d->bulan;
                    $tgl1 = '2020-01-01';
                    $tgl2 = date('Y-m-t', strtotime("$thn-$bln-1"));

                    foreach ($klasifkasi as $k => $i) {
                        $akun = \App\Models\NeracaModel::getAkun($tgl1, $tgl2, $k);
                        foreach ($akun as $a) {
                            $totalPerAkun[$bln][$i][$a->nm_akun] = $a->debit - $a->kredit;
                        }
                    }
                }

            @endphp

            <table class="table table-bordered" x-data="{
                open1: false,
                open2: false,
                open3: false,
                open4: false,
            }">
                <thead>
                    <tr>
                        <th class="dhead freeze-cell1_th">
                            Aktiva
                        </th>
                        @foreach ($bulans as $d)
                            <th class="dhead text-center freeze-cell1_th">{{ $d->nm_bulan }}</th>
                        @endforeach
                        <th class="dhead text-center freeze-cell1_th">Total</th>
                    </tr>
                    <tr>
                        <th class="dhead ps-3" colspan="14">Aktiva Lancar</th>
                    </tr>
                    <tr>
                        <th class="ps-4">
                            <div style="cursor: pointer" @click="open1 = ! open1">
                                KAS
                                <i class=" fas fa-caret-down float-end"></i>
                            </div>
                        </th>
                        @foreach ($bulans as $d)
                            <th class="text-end">{{ number_format($total_per_bulan[$d->bulan]['kas'], 0) }}</th>
                        @endforeach
                        <th class="text-end">{{ number_format($ttlKas, 0) }}</th>
                    </tr>

                    @foreach ($totalPerAkun[1]['kas'] as $d => $i)
                        <tr x-show="open1">
                            <td class="ps-4">{{ $d }}</td>
                            @php
                                $total = 0;
                            @endphp
                            @foreach ($bulans as $b)
                                <td class="ps-4 text-end">
                                    @php

                                        $duit = $totalPerAkun[$b->bulan]['kas'][$d];
                                        $total += $duit;
                                    @endphp
                                    {{ number_format($duit, 0) }}
                                </td>
                            @endforeach
                            <td class="text-end">
                                {{ number_format($total, 0) }}
                            </td>
                        </tr>
                    @endforeach

                    <tr>

                        <th class="ps-4">
                            <div style="cursor: pointer" @click="open2 = ! open2">
                                BANK
                                <i class=" fas fa-caret-down float-end"></i>

                            </div>
                        </th>
                        @foreach ($bulans as $d)
                            <th class="text-end">{{ number_format($total_per_bulan[$d->bulan]['bank'], 0) }}</th>
                        @endforeach
                        <th class="text-end">{{ number_format($ttlBank, 0) }}</th>
                    </tr>
                    @foreach ($totalPerAkun[1]['bank'] as $d => $i)
                        <tr x-show="open2">
                            @php
                                $total = 0;
                            @endphp
                            <td class="ps-4">{{ $d }}</td>
                            @foreach ($bulans as $b)
                                @php
                                    $total += $totalPerAkun[$b->bulan]['bank'][$d];
                                @endphp
                                <td class="ps-4 text-end">
                                    {{ number_format($totalPerAkun[$b->bulan]['bank'][$d], 0) }}
                                </td>
                            @endforeach
                            <td class="text-end">
                                {{ number_format($total, 0) }}
                            </td>
                        </tr>
                    @endforeach
                    <tr>

                        <th class="ps-4">
                            <div style="cursor: pointer" @click="open4 = ! open4">
                                PERSEDIAAN
                                <i class=" fas fa-caret-down float-end"></i>
                            </div>
                        </th>
                        @foreach ($bulans as $d)
                            <th class="text-end">{{ number_format($total_per_bulan[$d->bulan]['persediaan'], 0) }}</th>
                        @endforeach
                        <th class="text-end">{{ number_format($ttlPersediaan, 0) }}</th>
                    </tr>
                    @foreach ($totalPerAkun[1]['persediaan'] as $d => $i)
                        <tr x-show="open4">
                            <td class="ps-4">{{ $d }}</td>
                            @php
                                $total = 0;
                            @endphp
                            @foreach ($bulans as $b)
                                @php
                                    $total += $totalPerAkun[$b->bulan]['persediaan'][$d];
                                @endphp
                                <td class="ps-4 text-end">
                                    {{ number_format($totalPerAkun[$b->bulan]['persediaan'][$d], 0) }}
                                </td>
                            @endforeach
                            <td class="text-end">
                                {{ number_format($total, 0) }}
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <th class="dhead ps-3">Total Aktiva Lancar</th>
                        @foreach ($bulans as $d)
                            <th class="text-end dhead">{{ number_format($totalPerBulan[$d->bulan], 0) }}</th>
                        @endforeach
                        <th class="text-end dhead">{{ number_format($totalSemuaLancar, 0) }}</th>
                    </tr>

                    {{-- aktiva tetap --}}
                    @php
                        $klasifkasi = [
                            34 => 'peralatan',
                            32 => 'aktiva',
                            33 => 'aktivaGantung',
                            35 => 'peralatanGantung',
                            26 => 'akumulasiAktiva',
                            28 => 'akumulasiPeralatan',
                        ];

                        $total_per_bulan = [];
                        foreach ($bulans as $d) {
                            $bln = $d->bulan;
                            $tgl1 = '2020-01-01';
                            $tgl2 = date('Y-m-t', strtotime("$thn-$bln-1"));
                            foreach ($klasifkasi as $k => $i) {
                                $kas = \App\Models\Neracamodel::Getakumulasi($tgl1, $tgl2, $k);
                                $debit_kas = $kas->debit ?? 0;
                                $kredit_kas = $kas->kredit ?? 0;
                                $debit_kas_saldo = $kas->debit_saldo ?? 0;
                                $kredit_kas_saldo = $kas->kredit_saldo ?? 0;

                                $total_per_bulan[$bln][$i] = $debit_kas + $debit_kas_saldo - $kredit_kas - $kredit_kas_saldo;
                            }
                        }

                        // Menambahkan total keseluruhan untuk KAS dan BANK
                        $ttlPeralatan = array_sum(array_column($total_per_bulan, 'peralatan'));
                        $ttlAktiva = array_sum(array_column($total_per_bulan, 'aktiva'));
                        $ttlAktivaGantung = array_sum(array_column($total_per_bulan, 'aktivaGantung'));
                        $ttlPeralatanGantung = array_sum(array_column($total_per_bulan, 'peralatanGantung'));
                        $ttlAkumulasiAktiva = array_sum(array_column($total_per_bulan, 'akumulasiAktiva'));
                        $ttlAkumulasiPeralatan = array_sum(array_column($total_per_bulan, 'akumulasiPeralatan'));

                        $totalPerBulanTetap = [];
                        foreach ($bulans as $d) {
                            $bln = $d->bulan;
                            $totalPerBulanTetap[$bln] = 0; // Setiap bulan diinisialisasi dengan nilai 0
                        }

                        // Hitung total per bulan
                        foreach ($total_per_bulan as $bulan => $nilai) {
                            $totalPerBulanTetap[$bulan] += $nilai['peralatan'] + $nilai['aktiva'] + $nilai['aktivaGantung'] + $nilai['peralatanGantung'] + ($nilai['akumulasiAktiva'] + $nilai['akumulasiPeralatan']);
                        }

                        $totalSemua = $ttlPeralatan + $ttlAktiva + $ttlAktivaGantung + $ttlPeralatanGantung + ($ttlAkumulasiAktiva + $ttlAkumulasiPeralatan);
                    @endphp

                    <tr>
                        <th class="dhead ps-3" colspan="14">Aktiva Tetap</th>
                    </tr>
                    @foreach ($klasifkasi as $i => $k)
                        <tr>
                            <th class="ps-4">
                                <div style="cursor: pointer">
                                    {{ ucwords(preg_replace('/([a-z])([A-Z])/', '$1 $2', $k)) }}
                                </div>
                            </th>
                            @foreach ($bulans as $d)
                                <th class="text-end">{{ number_format($total_per_bulan[$d->bulan][$k], 0) }}</th>
                            @endforeach
                            <th class="text-end">{{ number_format(${'ttl' . ucwords($k)}, 0) }}</th>
                        </tr>
                    @endforeach
                    <tr>
                        <th class="dhead ps-3">Nilai Buku</th>
                        @foreach ($bulans as $d)
                            <th class="text-end dhead">{{ number_format($totalPerBulanTetap[$d->bulan], 0) }}</th>
                        @endforeach
                        <th class="text-end dhead">{{ number_format($totalSemua, 0) }}</th>
                    </tr>
                    <tr>
                        <th class="dhead"><b>Jumlah Aktiva</b></th>
                        @php
                            $totalSemuaAktiva = 0;
                        @endphp
                        @foreach ($bulans as $d)
                            @php
                                $totalSemuaAktiva += $totalPerBulanTetap[$d->bulan] + $totalPerBulan[$d->bulan];
                            @endphp
                            <th class="text-end dhead">
                                {{ number_format($totalPerBulanTetap[$d->bulan] + $totalPerBulan[$d->bulan], 0) }}</th>
                        @endforeach
                        <th class="text-end dhead">{{ number_format($totalSemuaAktiva, 0) }}</th>
                    </tr>
                    <tr>
                        <th colspan="14"></th>
                    </tr>
                    {{-- pasiva --}}

                    <tr>
                        <th class="dhead" colspan="14"><b>Passiva</b></th>

                    </tr>
                    <tr>
                        <th class="dhead ps-3" colspan="14">Hutang</th>
                    </tr>
                    @php

                        $totalPerAkun = [];
                        foreach ($bulans as $d) {
                            $bln = $d->bulan;
                            $tgl1 = '2020-01-01';
                            $tgl2 = date('Y-m-t', strtotime("$thn-$bln-1"));

                            $akun = \App\Models\NeracaModel::GetAkun($tgl1, $tgl2, 10);
                            foreach ($akun as $a) {
                                $totalPerAkun[$bln][$i][$a->nm_akun] = $a->kredit - $a->debit;
                            }
                        }

                        $totalPerBulanHutang = [];
                        foreach ($bulans as $d) {
                            $bln = $d->bulan;
                            $totalPerBulanHutang[$bln] = 0; // Setiap bulan diinisialisasi dengan nilai 0
                        }
                        foreach ($totalPerAkun as $bulan => $nilai) {
                            $totalPerBulanHutang[$bulan] += array_sum($nilai[28]);
                        }

                    @endphp
                    @foreach ($totalPerAkun[1]['28'] as $d => $i)
                        <tr>

                            <th class="ps-4">{{ $d }}</th>

                            @php
                                $total = 0;
                            @endphp
                            @foreach ($bulans as $b)
                                @php
                                    $total += $totalPerAkun[$b->bulan]['28'][$d];
                                @endphp
                                <td class="ps-4 text-end">
                                    {{ number_format($totalPerAkun[$b->bulan]['28'][$d], 0) }}
                                </td>
                            @endforeach
                            <td class="text-end">
                                {{ number_format($total, 0) }}
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <th class="dhead"><b>Jumlah Kewajiban Lancar</b></th>
                        @php
                            $totalSemuaHutang = 0;
                        @endphp
                        @foreach ($bulans as $d)
                            @php
                                $totalSemuaHutang += $totalPerBulanHutang[$d->bulan];
                            @endphp
                            <th class="text-end dhead">
                                {{ number_format($totalPerBulanHutang[$d->bulan], 0) }}</th>
                        @endforeach
                        <th class="text-end dhead">{{ number_format($totalSemuaHutang, 0) }}</th>
                    </tr>
                    <tr>
                        <th class="dhead ps-3" colspan="14">Ekuitas</th>
                    </tr>

                    @php
                        $totalPerAkun = [];
                        foreach ($bulans as $d) {
                            $bln = $d->bulan;
                            $tgl1 = '2020-01-01';
                            $tgl2 = date('Y-m-t', strtotime("$thn-$bln-1"));

                            $akun = \App\Models\NeracaModel::GetKas2($tgl1, $tgl2);
                            foreach ($akun as $a) {
                                $totalPerAkun[$bln][$i][$a->nm_akun] = $a->kredit + $a->kredit_saldo - $a->debit - $a->debit_saldo;
                            }
                        }

                    @endphp

                    @foreach ($totalPerAkun[1]['0'] as $d => $i)
                        <tr>

                            <th class="ps-4">{{ ucwords($d) }}</th>

                            @php
                                $total = 0;
                            @endphp
                            @foreach ($bulans as $b)
                                @php
                                    $total += $totalPerAkun[$b->bulan]['0'][$d];
                                @endphp
                                <td class="ps-4 text-end">
                                    {{ number_format($totalPerAkun[$b->bulan]['0'][$d], 0) }}
                                </td>
                            @endforeach
                            <td class="text-end">
                                {{ number_format($total, 0) }}
                            </td>
                        </tr>
                    @endforeach

                    @php
                        $totalPerAkunEkuitas2 = [];
                        foreach ($bulans as $d) {
                            $bln = $d->bulan;
                            $tgl1 = '2020-01-01';
                            $tgl2 = date('Y-m-t', strtotime("$thn-$bln-1"));

                            $ekuitas2 = \App\Models\NeracaModel::GetKas3($tgl1, $tgl2);
                            $laba_pendapatan = \App\Models\NeracaModel::laba_berjalan_pendapatan($tgl1, $tgl2);
                            $laba_biaya = \App\Models\NeracaModel::laba_berjalan_biaya($tgl1, $tgl2);

                            $laba_berjalan_sebelum_penutup = $laba_pendapatan->pendapatan - $laba_biaya->biaya;
                            $totalPerAkunEkuitas2[$bln]['total'] = $laba_berjalan_sebelum_penutup;
                            // $totalPerAkun[$bln]['labaBerjalan'] = $ekuitas2->kredit - $ekuitas2->debit + $laba_berjalan_sebelum_penutup;
                        }

                        $totalPerBulanEkuitas = [];
                        foreach ($bulans as $d) {
                            $bln = $d->bulan;
                            $totalPerBulanEkuitas[$bln] = 0; // Setiap bulan diinisialisasi dengan nilai 0
                        }
                        foreach ($totalPerAkun as $bulan => $nilai) {
                            $totalPerBulanEkuitas[$bulan] += array_sum($nilai[0]);
                        }
                    @endphp
                    <tr>

                        <th class="ps-4">{{ ucwords($ekuitas2->nm_akun) }} </th>

                        @php
                            $total = 0;
                        @endphp
                        @foreach ($bulans as $b)
                            @php
                                $total += $totalPerAkunEkuitas2[$b->bulan]['total'];
                            @endphp
                            <td class="ps-4 text-end">
                                {{ number_format($totalPerAkunEkuitas2[$b->bulan]['total'], 0) }}
                            </td>
                        @endforeach
                        <td class="text-end">
                            {{ number_format($total, 0) }}
                        </td>
                    </tr>

                    <tr>
                        <th class="dhead"><b>Total Ekuitas</b></th>
                        @php
                            $totalSemuaEkuitas = 0;
                        @endphp
                        @foreach ($bulans as $b)
                            @php
                                $totalSemuaEkuitas += $totalPerAkunEkuitas2[$b->bulan]['total'] + $totalPerBulanEkuitas[$b->bulan];
                            @endphp
                            <th class="text-end dhead">
                                {{ number_format($totalPerAkunEkuitas2[$b->bulan]['total'] + $totalPerBulanEkuitas[$b->bulan], 0) }}
                            </th>
                        @endforeach
                        <th class="text-end dhead">{{ number_format($totalSemuaEkuitas - -100000, 0) }}</th>
                    </tr>

                    <tr>
                        <th class="dhead"><b>Total Passiva</b></th>
                        @php
                            $totalSemuaPassiva = 0;
                        @endphp
                        @foreach ($bulans as $b)
                            @php
                                $totalSemuaPassiva += $totalPerBulanHutang[$b->bulan] + $totalPerAkunEkuitas2[$b->bulan]['total'] + $totalPerBulanEkuitas[$b->bulan];
                            @endphp
                            <th class="text-end dhead">
                                {{ number_format($totalPerBulanHutang[$b->bulan] + $totalPerAkunEkuitas2[$b->bulan]['total'] + $totalPerBulanEkuitas[$b->bulan], 0) }}
                            </th>
                        @endforeach
                        <th class="text-end dhead">{{ number_format($totalSemuaPassiva, 0) }}</th>
                    </tr>

                </thead>
            </table>
        </div>
    </div>
</div>
