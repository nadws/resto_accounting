<div class="card">
    <div class="card-header">
        <form id="history_profit">
            <div class="row">
                <div class="col-lg-6">
                    <h6 class="text-success float-start">{{ $title }}</h6>
                </div>
                <div class="col-lg-3"></div>
                <div class="col-lg-2">
                    <select name="" id="tahun_profit" class="select_profit">
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
                    <div id="loading_profit" class="spinner-border text-center text-success "
                        style="width: 6rem; height: 6rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="show_profit">
            @php
                function sumTotal($data)
                {
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

                    return ['totalsPerMonth' => $totalsPerMonth, 'total_seluruh' => $total_seluruh];

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

                    return ['totalsPerMonth2' => $totalsPerMonth2, 'total_seluruh2' => $total_seluruh2];

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

                    return ['totalsPerMonth3' => $totalsPerMonth3, 'total_seluruh3' => $total_seluruh3];

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

                    return ['totalsPerMonth4' => $totalsPerMonth4, 'total_seluruh4' => $total_seluruh4];
                }

                $totalsData1 = sumTotal($data);
                $totalsData2 = sumTotal($data2);
                $totalsData3 = sumTotal($data3);
                $totalsData4 = sumTotal($data4);
            @endphp
            <table class="table table-bordered" x-data="{
                open_biaya: false,
                open_pendapatan: false,
                open_penyesuaian: false,
                open_disusutkan: false,
            }">
                <thead>
                    <tr>
                        <th class="dhead freeze-cell1_th">Akun</th>
                        @foreach ($bulan as $d)
                            <th class="dhead text-end freeze-cell1_th">{{ $d->nm_bulan }}</th>
                        @endforeach
                        <th class="dhead text-end freeze-cell1_th">Total</th>
                    </tr>
                </thead>
                <tbody>

                    <tr>
                        <td class="fw-bold"><a href="#" data-bs-target="#tbhPendapatan" class="setKlasifikasi"
                                id_klasifikasi="1" data-bs-toggle="modal">Pendapatan</a> <span
                                @click="open_pendapatan = ! open_pendapatan" class="badge bg-primary float-end"
                                style="cursor: pointer"><i class="fas fa-caret-down"></i></button>
                        </td>
                        @foreach (array_keys(reset($data)) as $month)
                            <td class="fw-bold text-end">{{ number_format($totalsData1['totalsPerMonth'][$month], 0) }}
                            </td>
                        @endforeach
                        <td class="fw-bold text-end">{{ number_format($totalsData1['total_seluruh'], 0) }}</td>
                    </tr>

                    @foreach ($data as $akun => $months)
                        <tr x-show="open_pendapatan">
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
                                @php
                                    $tgl1 = $thn . '-' . $loop->iteration . '-01';
                                    $tgl2 = date('Y-m-t', strtotime($tgl1));
                                @endphp
                                <td class="text-end">
                                    <a target="_blank"
                                        href="{{ route('bukubesar.detail_buku_besar', ['id_akun' => $akun, 'tgl1' => $tgl1, 'tgl2' => $tgl2]) }}">{{ number_format($nominal, 0) }}</a>
                                </td>
                                @php
                                    $totalPerAkun2 += $nominal;
                                @endphp
                            @endforeach
                            <td class="text-end">{{ number_format($totalPerAkun2, 0) }}</td>
                        </tr>
                    @endforeach

                    <tr>
                        <td class="fw-bold"><a href="#" data-bs-target="#tbhBiaya"
                                data-bs-toggle="modal">Biaya</a> <span @click="open_biaya = ! open_biaya"
                                class="badge bg-primary float-end" style="cursor: pointer"><i
                                    class="fas fa-caret-down"></i></span>

                        </td>
                        @foreach (array_keys(reset($data2)) as $month)
                            <td class="fw-bold text-end">{{ number_format($totalsData2['totalsPerMonth'][$month], 0) }}
                            </td>
                        @endforeach
                        <td class="text-end fw-bold">{{ number_format($totalsData2['total_seluruh'], 0) }}</td>
                    </tr>
                    @foreach ($data2 as $akun => $months)
                        <tr x-show="open_biaya">
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
                                @php
                                    $tgl1 = $thn . '-' . $loop->iteration . '-01';
                                    $tgl2 = date('Y-m-t', strtotime($tgl1));
                                @endphp
                                <td class="text-end">

                                    <a target="_blank"
                                        href="{{ route('bukubesar.detail_buku_besar', ['id_akun' => $akun, 'tgl1' => $tgl1, 'tgl2' => $tgl2]) }}">{{ number_format($nominal, 0) }}</a>
                                </td>
                                @php
                                    $totalPerAkun3 += $nominal;
                                @endphp
                            @endforeach
                            <td class="text-end">{{ number_format($totalPerAkun3, 0) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="fw-bold"><a href="#" data-bs-target="#tbhBiaya" data-bs-toggle="modal">Biaya
                                Penyesuaian</a> <span @click="open_penyesuaian = ! open_penyesuaian"
                                class="badge bg-primary float-end" style="cursor: pointer"><i
                                    class="fas fa-caret-down"></i></span>

                        </td>
                        @foreach (array_keys(reset($data3)) as $month)
                            <td class="fw-bold text-end">{{ number_format($totalsData3['totalsPerMonth'][$month], 0) }}
                            </td>
                        @endforeach
                        <td class="text-end fw-bold">{{ number_format($totalsData3['total_seluruh'], 0) }}</td>
                    </tr>
                    @foreach ($data3 as $akun => $months)
                        <tr x-show="open_penyesuaian">
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
                                @php
                                    $tgl1 = $thn . '-' . $loop->iteration . '-01';
                                    $tgl2 = date('Y-m-t', strtotime($tgl1));
                                @endphp
                                <td class="text-end">
                                    <a target="_blank"
                                        href="{{ route('bukubesar.detail_buku_besar', ['id_akun' => $akun, 'tgl1' => $tgl1, 'tgl2' => $tgl2]) }}">{{ number_format($nominal, 0) }}</a>
                                </td>
                                @php
                                    $totalPerAkun5 += $nominal;
                                @endphp
                            @endforeach
                            <td class="text-end">{{ number_format($totalPerAkun5, 0) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="dhead fw-bold">Laba Kotor</td>
                        @foreach (array_keys(reset($data)) as $month)
                            <td class="fw-bold text-end dhead">
                                {{ number_format($totalsData1['totalsPerMonth'][$month] - $totalsData2['totalsPerMonth'][$month] - $totalsData3['totalsPerMonth'][$month], 0) }}
                            </td>
                        @endforeach
                        <td class="fw-bold text-end dhead">
                            {{ number_format($totalsData1['total_seluruh'] - $totalsData2['total_seluruh'] - $totalsData3['total_seluruh'], 0) }}
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold"><a href="#" data-bs-target="#tbhBiaya" data-bs-toggle="modal">Biaya
                                Disusutkan</a> <span @click="open_disusutkan = ! open_disusutkan"
                                class="badge bg-primary float-end" style="cursor: pointer"><i
                                    class="fas fa-caret-down"></i></span>

                        </td>
                        @foreach (array_keys(reset($data4)) as $month)
                            <td class="fw-bold text-end">{{ number_format($totalsData4['totalsPerMonth'][$month], 0) }}
                            </td>
                        @endforeach
                        <td class="text-end fw-bold">{{ number_format($totalsData4['total_seluruh'], 0) }}</td>
                    </tr>
                    @foreach ($data4 as $akun => $months)
                        <tr x-show="open_disusutkan">
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
                                @php
                                    $tgl1 = $thn . '-' . $loop->iteration . '-01';
                                    $tgl2 = date('Y-m-t', strtotime($tgl1));
                                @endphp
                                <td class="text-end">
                                    <a target="_blank"
                                        href="{{ route('bukubesar.detail_buku_besar', ['id_akun' => $akun, 'tgl1' => $tgl1, 'tgl2' => $tgl2]) }}">{{ number_format($nominal, 0) }}</a>
                                </td>
                                @php
                                    $totalPerAkun4 += $nominal;
                                @endphp
                            @endforeach
                            <td class="text-end">{{ number_format($totalPerAkun4, 0) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="dhead fw-bold">Laba Bersih</td>
                        @foreach (array_keys(reset($data)) as $month)
                            <td class="fw-bold text-end dhead">
                                {{ number_format($totalsData1['totalsPerMonth'][$month] - $totalsData2['totalsPerMonth'][$month] - $totalsData3['totalsPerMonth'][$month] - $totalsData4['totalsPerMonth'][$month], 0) }}
                            </td>
                        @endforeach
                        <td class="fw-bold text-end dhead">
                            {{ number_format($totalsData1['total_seluruh'] - $totalsData2['total_seluruh'] - $totalsData3['total_seluruh'] - $totalsData4['total_seluruh'], 0) }}
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>

@php
    $form = [
        1 => ['tbhPendapatan', 'Pendapatan'],
        2 => ['tbhBiaya', 'Biaya'],
        3 => ['tbhBiayaPenyesuaian', 'Biaya Penyesuaian'],
        4 => ['tbhBiayaDisusutkan', 'Biaya Disusutkan'],
    ];
@endphp
@foreach ($form as $d => $i)
    <form id="save_akun_profit">
        <x-theme.modal title="Tambah Akun Profit Pendapatan" size="modal-lg" idModal="{{ $i[0] }}">

            <div class="row">
                <div class="col-lg-4">
                    <label for="">Nama akun</label>
                    <input type="text" class="form-control" name="nm_akun[]" required>
                </div>
                <div class="col-lg-3">
                    <label for="">Nomer akun</label>
                    <input type="text" class="form-control" name="kode_akun[]" required>
                </div>
                <div class="col-lg-3">
                    <label for="">Kategori</label>
                    <input type="hidden" name="id_klasifikasi[]" value="{{ $d }}">
                    <input type="text" class="form-control" readonly value="{{ $i[1] }}">
                </div>
            </div>
            <x-theme.multiple-input>
                <div class="col-lg-4">
                    <label for="">Nama akun</label>
                    <input type="text" class="form-control" name="nm_akun[]" required>
                </div>
                <div class="col-lg-3">
                    <label for="">Nomer akun</label>
                    <input type="text" class="form-control" name="kode_akun[]" required>
                </div>
                <div class="col-lg-3">
                    <label for="">Kategori</label>
                    <input type="hidden" name="id_klasifikasi[]" value="{{ $d }}">
                    <input type="text" class="form-control" readonly value="{{ $i[1] }}">
                </div>
            </x-theme.multiple-input>
        </x-theme.modal>
    </form>
@endforeach

<x-theme.modal title="List Akun Profit" btnSave="T" size="modal-lg" idModal="listAkunProfit">
    <div id="loadListAkunProfit"></div>
</x-theme.modal>

<form id="formEditAkunProfit">
    <x-theme.modal title="Edit Akun Profit" size="modal-lg" idModal="editAkunProfit">
        <div id="loadEditAkunProfit"></div>
    </x-theme.modal>
</form>
