<div class="card">
    <div class="card-header">
        <h6 class="text-success float-start">{{ $title }}</h6>
        <x-theme.button modal="Y" idModal="listAkunProfit" icon="fa-plus" variant="primary" addClass="float-end"
            teks="Daftar Akun" />

    </div>
    <div class="card-body">
        <div class="row">
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
                                    {{ number_format($nominal, 0) }}
                                    {{-- <a target="_blank"
                                        href="{{ route('summary_buku_besar.detail', ['id_akun' => $akun, 'tgl1' => $tgl1, 'tgl2' => $tgl2]) }}">{{ number_format($nominal, 0) }}</a> --}}
                                </td>
                                @php
                                    $totalPerAkun2 += $nominal;
                                @endphp
                            @endforeach
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
                                $totalPerAkun2 = 0;
                            @endphp
                            @foreach ($months as $month => $nominal)
                                @php
                                    $tgl1 = $thn . '-' . $loop->iteration . '-01';
                                    $tgl2 = date('Y-m-t', strtotime($tgl1));
                                @endphp
                                <td class="text-end">
                                    {{ number_format($nominal, 0) }}
                                    {{-- <a target="_blank"
                                        href="{{ route('summary_buku_besar.detail', ['id_akun' => $akun, 'tgl1' => $tgl1, 'tgl2' => $tgl2]) }}">{{ number_format($nominal, 0) }}</a> --}}
                                </td>
                                @php
                                    $totalPerAkun2 += $nominal;
                                @endphp
                            @endforeach
                        </tr>
                    @endforeach

                    <tr>
                        <td class="fw-bold"><a href="#" data-bs-target="#tbhBiayaPenyesuaian"
                                data-bs-toggle="modal">Biaya Penyesuaian</a> <span
                                @click="open_penyesuaian = ! open_penyesuaian" class="badge bg-primary float-end"
                                style="cursor: pointer"><i class="fas fa-caret-down"></i></span>

                        </td>
                        @foreach (array_keys(reset($data3)) as $month)
                            <td class="fw-bold text-end">{{ number_format($totalsData3['totalsPerMonth'][$month], 0) }}
                            </td>
                        @endforeach
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
                                $totalPerAkun2 = 0;
                            @endphp
                            @foreach ($months as $month => $nominal)
                                @php
                                    $tgl1 = $thn . '-' . $loop->iteration . '-01';
                                    $tgl2 = date('Y-m-t', strtotime($tgl1));
                                @endphp
                                <td class="text-end">
                                    {{ number_format($nominal, 0) }}
                                    {{-- <a target="_blank"
                                        href="{{ route('summary_buku_besar.detail', ['id_akun' => $akun, 'tgl1' => $tgl1, 'tgl2' => $tgl2]) }}">{{ number_format($nominal, 0) }}</a> --}}
                                </td>
                                @php
                                    $totalPerAkun2 += $nominal;
                                @endphp
                            @endforeach
                        </tr>
                    @endforeach

                    <tr>
                        <td class="fw-bold dhead">LABA KOTOR</td>
                        @foreach (array_keys(reset($data)) as $month)
                            <td class="fw-bold text-end dhead">
                                {{ number_format($totalsData1['totalsPerMonth'][$month] - $totalsData2['totalsPerMonth'][$month] - $totalsData3['totalsPerMonth'][$month], 0) }}
                            </td>
                        @endforeach

                    </tr>
                    <tr>
                        <td class="fw-bold"><a href="#" data-bs-target="#tbhBiayaDisusutkan"
                                data-bs-toggle="modal">Biaya Disusutkan</a> <span
                                @click="open_disusutkan = ! open_disusutkan" class="badge bg-primary float-end"
                                style="cursor: pointer"><i class="fas fa-caret-down"></i>
                        </td>
                        @foreach (array_keys(reset($data4)) as $month)
                            <td class="fw-bold text-end">
                                {{ number_format($totalsData4['totalsPerMonth'][$month], 0) }}
                            </td>
                        @endforeach
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
                                $totalPerAkun2 = 0;
                            @endphp
                            @foreach ($months as $month => $nominal)
                                @php
                                    $tgl1 = $thn . '-' . $loop->iteration . '-01';
                                    $tgl2 = date('Y-m-t', strtotime($tgl1));
                                @endphp
                                <td class="text-end">
                                    {{ number_format($nominal, 0) }}
                                    {{-- <a target="_blank"
                                        href="{{ route('summary_buku_besar.detail', ['id_akun' => $akun, 'tgl1' => $tgl1, 'tgl2' => $tgl2]) }}">{{ number_format($nominal, 0) }}</a> --}}
                                </td>
                                @php
                                    $totalPerAkun2 += $nominal;
                                @endphp
                            @endforeach
                        </tr>
                    @endforeach
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
