<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>{{ $title }}</title>
    <style>
        .table>:not(caption)>*>* {
            border-bottom-width: 0px;
        }

        .bdr {
            border-radius: 16px;
            overflow: hidden;
        }

        .rounded-tfoot th:first-child {
            border-bottom-left-radius: 16px;
        }

        .rounded-tfoot th:last-child {
            border-bottom-right-radius: 16px;
        }
    </style>
</head>

<body>
    <div class="container py-3 text-center">
        <div class="row align-items-center">
            <div class="col text-start">
                <img src="{{ app('gambarLogo') }}" width="60" alt="">
            </div>
            <div class="col">
                <h1 class="text-center">{{ app('nm_lokasi') }}</h1>

            </div>
            <div class="col text-end">
                <p class="text-success fst-italic" style="font-size: 11px">Tanggal Cetak :
                    {{ tanggal(date('Y-m-d')) . ', ' . auth()->user()->name }}</p>

            </div>
        </div>
        <div class="row mt-3">
            <div class="col text-start">
                <h4 class="text-primary">Informasi Suplier</h4>
                <div class="row">
                    <span><b>{{ strtoupper($poDetail->nm_suplier) }}</b></span>
                    <span>{{ $poDetail->npwp }}</span>
                    <span>{{ ucwords($poDetail->alamat) }}</span>
                    <span>{{ $poDetail->email }} ({{ $poDetail->telepon }})</span>
                </div>
            </div>
            <div class="col text-end">
                <h3 class="text-primary">{{ $title }}</h3>
                <table class="table">
                    <tr>
                        <td>No Order</td>
                        <td width="5">:</td>
                        <th width="140"">#PO/{{ $poDetail->no_nota }}</th>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td width="5">:</td>
                        <th width="140"">{{ tanggal($poDetail->tgl) }}</th>
                    </tr>
                </table>
            </div>
        </div>
        <hr>
        <div class="row mt-3">
            <div class="col">
                <div class="tbl-container bdr">
                    <table class="table table-bordered">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>No</th>
                                <th>Nama Bahan</th>
                                <th class="text-end">Qty</th>
                                <th>Satuan</th>
                                <th>Keterangan</th>
                                <th class="text-end">Rp Satuan</th>
                                <th class="text-end">Total Rp</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($getBarang as $no => $d)
                                <tr>
                                    <td>{{ $no + 1 }}</td>
                                    <td>{{ ucwords($d->nm_bahan) }}</td>
                                    <td align="right">{{ number_format($d->qty, 0) }}</td>
                                    <td>{{ ucwords($d->nm_satuan) }}</td>
                                    <td>{{ $d->ket }}</td>
                                    <td align="right">{{ number_format($d->ttl_rp / $d->qty, 0) }}</td>
                                    <td align="right">{{ number_format($d->ttl_rp, 0) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        {{-- <tfoot class="rounded-tfoot text-white bg-info">
                        <tr>
                            <th class="text-center " colspan="2">Total</th>
                            <th class="text-end">8</th>
                            <th class="text-end" colspan="4">20,000</th>
                        </tr>
                    </tfoot> --}}
                    </table>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-6">
                <table class="table table-border">
                    <tr>
                        <td class="dhead  text-start">
                            <h6 class="text-white">Catatan Tambahan</h6>
                        </td>
                    </tr>
                    <tr>
                        <td align="left">{{ $poDetail->catatan ?? '-' }}</td>
                    </tr>
                </table>
            </div>

            <div class="col-6">
                <table class="table table-hover text-start" style="padding-bottom: 1px">
                    <tr>
                        <th>Sub Total</th>
                        <th>
                            <h6 class="subTotal text-end">{{ number_format($poDetail->sub_total, 0) }}</h6>
                        </th>
                    </tr>

                    @if ($poDetail->biaya)
                        <tr>
                            <th>
                                BIaya Pengiriman
                            </th>
                            <td align="right">
                                <h6>{{ number_format($poDetail->biaya, 0) }}</h6>
                            </td>
                        </tr>
                    @endif
                    @php
                        $total = $poDetail->sub_total + $poDetail->biaya;
                    @endphp
                    <tr>
                        <th>Total</th>
                        <th>
                            <h6 class="grandTotal text-end">{{ number_format($total, 0) }}</h6>
                        </th>
                    </tr>
                    {{-- @if ($poDetail->uang_muka)
                        <tr>
                            <th>
                                Uang Dimuka
                            </th>
                            <td align="right">
                                <h6>{{ number_format($poDetail->uang_muka, 0) }}</h6>
                            </td>
                        </tr>
                    @endif --}}

                    @if ($bayarSum->ttlBayar)
                        @foreach ($cekSudahPernahBayar as $i => $d)
                            <tr class="text-primary border">
                                <th><i class="fas fa-check me-2"></i>Pembayaran {{$d->status == 'dp' ? 'DP' : ''}} {{ strtoupper($d->nm_akun) }} ke -
                                    {{ $i + 1 }}</th>
                                <th>
                                    <h6 class="text-primary text-end"> {{ number_format($d->jumlah, 0) }}</h6>
                                </th>
                            </tr>
                        @endforeach

                    @endif
                    <tr>
                        <th>Sisa Tagihan</th>
                        <th>
                            @php
                                $sisaTagihan = $total  - $bayarSum->ttlBayar;
                            @endphp
                            <input type="hidden" name="sisaTagihan" class="sisaTagihanValue"
                                value="{{ $sisaTagihan }}">
                            <h5 class="text-end"><em class="sisaTagihan ">{{ number_format($sisaTagihan, 0) }}</em>
                            </h5>
                        </th>
                    </tr>
                </table>
            </div>
        </div>
    </div>



      

<script>window.print()</script>
</body>

</html>
