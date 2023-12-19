<div class="card">
    <div class="card-header">
        <form id="history_cashflow">
            <div class="row">
                <div class="col-lg-6">
                    <h6 class="text-success"> Cashflow </h6>
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
        <div class="row">
            <div class="col-lg-12">
                <div class="text-center">
                    <div id="loading_cashflow" class="spinner-border text-center text-success "
                        style="width: 6rem; height: 6rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="show_cashflow" style="display: none;">
            <div class="col-lg-12">
                @php
                    $totalsPerMonth = array_fill(0, count(array_keys(reset($data))), 0);
                    $total_seluruh = 0;

                    foreach ($data as $akun => $months) {
                        $totalPerAkun = 0;
                        foreach ($months as $month => $nominal) {
                            $totalPerAkun += $nominal;
                            $totalsPerMonth[$month] = ($totalsPerMonth[$month] ?? 0) + $nominal;
                        }
                        $total_seluruh += $totalPerAkun;
                    }

                    $totalsPerMonth2 = array_fill(0, count(array_keys(reset($data2))), 0);
                    $total_seluruh2 = 0;

                    foreach ($data2 as $akun => $months) {
                        $totalPerAkun2 = 0;
                        foreach ($months as $month => $nominal) {
                            $totalPerAkun2 += $nominal;
                            $totalsPerMonth2[$month] = ($totalsPerMonth2[$month] ?? 0) + $nominal;
                        }
                        $total_seluruh2 += $totalPerAkun2;
                    }

                    $totalsPerMonth3 = array_fill(0, count(array_keys(reset($data3))), 0);
                    $total_seluruh3 = 0;

                    foreach ($data3 as $akun => $months) {
                        $totalPerAkun3 = 0;
                        foreach ($months as $month => $nominal) {
                            $totalPerAkun3 += $nominal;
                            $totalsPerMonth3[$month] = ($totalsPerMonth3[$month] ?? 0) + $nominal;
                        }
                        $total_seluruh3 += $totalPerAkun3;
                    }

                    $totalsPerMonth4 = array_fill(0, count(array_keys(reset($data4))), 0);
                    $total_seluruh4 = 0;
                    foreach ($data4 as $akun => $months) {
                        $totalPerAkun4 = 0;
                        foreach ($months as $month => $nominal) {
                            $totalPerAkun4 += $nominal;
                            $totalsPerMonth4[$month] = ($totalsPerMonth4[$month] ?? 0) + $nominal;
                        }
                        $total_seluruh4 += $totalPerAkun4;
                    }
                    $totalsPerMonth5 = array_fill(0, count(array_keys(reset($data5))), 0);
                    $total_seluruh5 = 0;
                    foreach ($data5 as $akun => $months) {
                        $totalPerAkun5 = 0;
                        foreach ($months as $month => $nominal) {
                            $totalPerAkun5 += $nominal;
                            $totalsPerMonth5[$month] = ($totalsPerMonth5[$month] ?? 0) + $nominal;
                        }
                        $total_seluruh5 += $totalPerAkun4;
                    }
                @endphp
                <table class="table table-bordered" x-data="{
                    open_pendapatan: false,
                    open_penjualan: false,
                    open_hutang: false,
                    open_bayarhutang: false,
                    open_biayacost: false,
                    open_biayaproyek: false,
                
                }">
                    <thead>
                        <tr>
                            <th class="dhead freeze-cell1_th">Akun</th>
                            @foreach (array_keys(reset($data)) as $month)
                                <th class="dhead text-end freeze-cell1_th">{{ $month }}</th>
                            @endforeach
                            <th class="dhead text-end freeze-cell1_th">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="fw-bold">Uang Masuk <a type="button" class="badge bg-primary float-end"
                                    @click="open_pendapatan = ! open_pendapatan"><i class="fas fa-caret-down"></i></a>
                            </td>
                            @foreach (array_keys(reset($data)) as $month)
                                <td class="fw-bold text-end">
                                    {{ number_format($totalsPerMonth[$month], 0) }}
                                </td>
                            @endforeach
                            <td class="fw-bold text-end">
                                {{ number_format($total_seluruh, 0) }}</td>
                        </tr>
                        <tr x-show="open_pendapatan">
                            <td class="fw-bold">&nbsp; &nbsp;Penjualan <a type="button"
                                    class="badge bg-primary float-end" @click="open_penjualan = ! open_penjualan"><i
                                        class="fas fa-caret-down"></i></a>
                            </td>
                            @foreach (array_keys(reset($data)) as $month)
                                <td class=" text-end">{{ number_format($totalsPerMonth[$month], 0) }}</td>
                            @endforeach
                            <td class=" text-end">{{ number_format($total_seluruh, 0) }}</td>
                        </tr>
                        @foreach ($data as $akun => $months)
                            <tr x-show="open_penjualan && open_pendapatan">
                                <td>
                                    @php
                                        $nm_akun = DB::table('akun')
                                            ->where('id_akun', $akun)
                                            ->first();
                                    @endphp
                                    {{ $nm_akun->nm_akun }}
                                </td>
                                @php
                                    $totalPerAkun = 0;
                                @endphp
                                @foreach ($months as $month => $nominal)
                                    <td class="text-end">

                                        @php
                                            $tgl1 = $thn . '-' . $loop->iteration . '-01';
                                            $tgl2 = date('Y-m-t', strtotime($tgl1));
                                        @endphp
                                        <a target="_blank" href="#">{{ number_format($nominal, 0) }}</a>
                                    </td>
                                    @php
                                        $totalPerAkun += $nominal;
                                    @endphp
                                @endforeach
                                <td class="text-end">{{ number_format($totalPerAkun, 0) }}</td>
                            </tr>
                        @endforeach


                        <tr>
                            <td class="fw-bold">Hutang <a type="button" class="badge bg-primary float-end"
                                    @click="open_hutang = ! open_hutang"><i class="fas fa-caret-down"></i></a>
                            </td>
                            @foreach (array_keys(reset($data2)) as $month)
                                <td class="fw-bold text-end">{{ number_format($totalsPerMonth2[$month], 0) }}</td>
                            @endforeach
                            <td class="fw-bold text-end">{{ number_format($total_seluruh2, 0) }}</td>
                        </tr>
                        @foreach ($data2 as $akun => $months)
                            <tr x-show="open_hutang">
                                <td>
                                    @php
                                        $nm_akun = DB::table('akun')
                                            ->where('id_akun', $akun)
                                            ->first();
                                    @endphp
                                    {{ $nm_akun->nm_akun }}
                                </td>
                                @php
                                    $totalPerAkun2 = 0;
                                @endphp
                                @foreach ($months as $month => $nominal)
                                    <td class="text-end">

                                        @php
                                            $tgl1 = $thn . '-' . $loop->iteration . '-01';
                                            $tgl2 = date('Y-m-t', strtotime($tgl1));
                                        @endphp
                                        <a target="_blank" href="#">{{ number_format($nominal, 0) }}</a>
                                    </td>
                                    @php
                                        $totalPerAkun2 += $nominal;
                                    @endphp
                                @endforeach
                                <td class="text-end">{{ number_format($totalPerAkun2, 0) }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="dhead fw-bold freeze-cell1_th">Total Pemasukan</td>
                            @foreach (array_keys(reset($data)) as $month)
                                <td class="dhead fw-bold text-end freeze-cell1_th">
                                    {{ number_format($totalsPerMonth[$month] + $totalsPerMonth2[$month], 0) }}</td>
                            @endforeach
                            <td class="dhead fw-bold text-end freeze-cell1_th">
                                {{ number_format($total_seluruh + $total_seluruh2, 0) }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Bayar Hutang <a type="button" class="badge bg-primary float-end"
                                    @click="open_bayarhutang = ! open_bayarhutang"><i class="fas fa-caret-down"></i></a>
                            </td>
                            @foreach (array_keys(reset($data3)) as $month)
                                <td class="fw-bold text-end">{{ number_format($totalsPerMonth3[$month], 0) }}</td>
                            @endforeach
                            <td class="fw-bold text-end">{{ number_format($total_seluruh3, 0) }}</td>
                        </tr>
                        @foreach ($data3 as $akun => $months)
                            <tr x-show="open_bayarhutang">
                                <td>
                                    @php
                                        $nm_akun = DB::table('akun')
                                            ->where('id_akun', $akun)
                                            ->first();
                                    @endphp
                                    {{ $nm_akun->nm_akun }}
                                </td>
                                @php
                                    $totalPerAkun3 = 0;
                                @endphp
                                @foreach ($months as $month => $nominal)
                                    <td class="text-end">

                                        @php
                                            $tgl1 = $thn . '-' . $loop->iteration . '-01';
                                            $tgl2 = date('Y-m-t', strtotime($tgl1));
                                        @endphp
                                        <a target="_blank" href="#">{{ number_format($nominal, 0) }}</a>
                                    </td>
                                    @php
                                        $totalPerAkun3 += $nominal;
                                    @endphp
                                @endforeach
                                <td class="text-end">{{ number_format($totalPerAkun3, 0) }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="fw-bold">Biaya Cost <a type="button" class="badge bg-primary float-end"
                                    @click="open_biayacost = ! open_biayacost"><i class="fas fa-caret-down"></i></a>
                            </td>
                            @foreach (array_keys(reset($data4)) as $month)
                                <td class="fw-bold text-end">{{ number_format($totalsPerMonth4[$month], 0) }}</td>
                            @endforeach
                            <td class="fw-bold text-end">{{ number_format($total_seluruh4, 0) }}</td>
                        </tr>
                        @foreach ($data4 as $akun => $months)
                            <tr x-show="open_biayacost">
                                <td>
                                    @php
                                        $nm_akun = DB::table('akun')
                                            ->where('id_akun', $akun)
                                            ->first();
                                    @endphp
                                    {{ $nm_akun->nm_akun }}
                                </td>
                                @php
                                    $totalPerAkun4 = 0;
                                @endphp
                                @foreach ($months as $month => $nominal)
                                    <td class="text-end">

                                        @php
                                            $tgl1 = $thn . '-' . $loop->iteration . '-01';
                                            $tgl2 = date('Y-m-t', strtotime($tgl1));
                                        @endphp
                                        <a target="_blank" href="#">{{ number_format($nominal, 0) }}</a>
                                    </td>
                                    @php
                                        $totalPerAkun4 += $nominal;
                                    @endphp
                                @endforeach
                                <td class="text-end">{{ number_format($totalPerAkun4, 0) }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="fw-bold">Biaya Proyek <a type="button" class="badge bg-primary float-end"
                                    @click="open_biayaproyek = ! open_biayaproyek"><i class="fas fa-caret-down"></i></a>
                            </td>
                            @foreach (array_keys(reset($data5)) as $month)
                                <td class="fw-bold text-end">{{ number_format($totalsPerMonth5[$month], 0) }}</td>
                            @endforeach
                            <td class="fw-bold text-end">{{ number_format($total_seluruh5, 0) }}</td>
                        </tr>
                        @foreach ($data5 as $akun => $months)
                            <tr x-show="open_biayaproyek">
                                <td>
                                    @php
                                        $nm_akun = DB::table('akun')
                                            ->where('id_akun', $akun)
                                            ->first();
                                    @endphp
                                    {{ $nm_akun->nm_akun }}
                                </td>
                                @php
                                    $totalPerAkun5 = 0;
                                @endphp
                                @foreach ($months as $month => $nominal)
                                    <td class="text-end">

                                        @php
                                            $tgl1 = $thn . '-' . $loop->iteration . '-01';
                                            $tgl2 = date('Y-m-t', strtotime($tgl1));
                                        @endphp
                                        <a target="_blank" href="#">{{ number_format($nominal, 0) }}</a>
                                    </td>
                                    @php
                                        $totalPerAkun5 += $nominal;
                                    @endphp
                                @endforeach
                                <td class="text-end">{{ number_format($totalPerAkun5, 0) }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="dhead fw-bold freeze-cell1_th">Total Pengeluaran</td>
                            @foreach (array_keys(reset($data)) as $month)
                                <td class="dhead fw-bold text-end freeze-cell1_th">
                                    {{ number_format($totalsPerMonth3[$month] + $totalsPerMonth4[$month] + $totalsPerMonth5[$month], 0) }}
                                </td>
                            @endforeach
                            <td class="dhead fw-bold text-end freeze-cell1_th">
                                {{ number_format($total_seluruh3 + $total_seluruh4 + $total_seluruh5, 0) }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold dhead">Net Cashflow</td>
                            @php
                                $net_cashflow = 0;
                            @endphp
                            @foreach (array_keys(reset($data)) as $month)
                                @php
                                    $net_cashflow += $totalsPerMonth[$month] + $totalsPerMonth2[$month] - $totalsPerMonth3[$month] - $totalsPerMonth4[$month] - $totalsPerMonth5[$month];
                                @endphp
                                <td class="fw-bold text-end dhead">
                                    {{ number_format($net_cashflow, 0) }}
                                </td>
                            @endforeach
                            <td class="fw-bold text-end dhead">
                                {{ number_format($total_seluruh + $total_seluruh2 - ($total_seluruh3 + $total_seluruh4 + $total_seluruh5), 0) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
