<x-theme.app title="{{ $title }}" table="Y" sizeCard="10">

    <x-slot name="cardHeader">
        <div class="row justify-content-end">

            <div class="col-lg-6">
                <h5 class="float-start mt-1">{{ $title }} </h5>
            </div>
            <div class="col-lg-6">
                {{-- <a href="{{ route('controlflow') }}" class="btn btn-primary float-end"><i class="fas fa-home"></i></a> --}}
            </div>
        </div>

    </x-slot>
    <style>
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #000000;
            line-height: 36px;
            /* font-size: 12px; */
            width: 150px;

        }
    </style>

    <x-slot name="cardBody">
        <div class="row align-items-center">
            <div class="col">
                <h6>Status : {{ ucwords($poDetail->status) }}</h6>
            </div>
            <div class="col text-end">
                <a href="{{ route('po.index') }}" class="btn btn-sm btn-primary"><i class="fas fa-arrow-left"></i>
                    Kembali</a>
                <a href="{{ route('po.add') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Tambah
                    Baru</a>
                <a target="_blank" href="{{ route('po.print', $poDetail->no_nota) }}"
                    class="btn btn-sm btn-outline-primary"><i class="fas fa-print"></i> Cetak</a>
            </div>
        </div>
        <hr>
        <div class="row float-center">
            <div class="col">
                <label for="">Nomor : </label>
                <h5>PO/{{ $poDetail->no_nota }}</h5>
            </div>
            <div class="col">
                <label for="">Suplier : </label>
                <h6>{{ strtoupper($poDetail->nm_suplier) }}</h6>
            </div>
            <div class="col">
                <label for="">Tgl Transaksi : </label>
                <h6>{{ tanggal($poDetail->tgl) }}</h6>
            </div>
        </div>
        <div class="row mt-3">
            @include('datamenu.po.components.tbl_item')
        </div>
        <form action="{{ route('po.bayar') }}" method="post">

            <div class="row">
                @php
                    $total = $poDetail->sub_total - $poDetail->potongan + $poDetail->biaya + $poDetail->ttl_pajak;
                    $sisaTagihan = $total - $bayarSum->ttlBayar;
                @endphp
                @include('datamenu.po.components.tbl_sub', [
                    'sisaTagihan' => $sisaTagihan,
                    'total' => $total,
                ])
            </div>
            <hr>
            <div class="row" x-data="{}">
                <div class="col">
                    @if (ceil($bayarSum->ttlBayar) < ceil($poDetail->sub_total - $poDetail->potongan + $poDetail->ttl_pajak))
                        <h6>Terima Pembayaran</h6>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="dhead">Tgl Transaksi </th>
                                    <th class="dhead" width="170">Total Bayar</th>
                                    <th class="dhead">Dibayar Pakai</th>
                                    <th class="dhead">Catatan</th>
                                    <th class="dhead text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @csrf
                                <tr>
                                    <td>
                                        <input type="hidden" name="no_nota" value="{{ $poDetail->no_nota }}">
                                        <input value="{{ date('Y-m-d') }}" type="date" name="tgl_transaksi"
                                            class="form-control">
                                    </td>
                                    <td>
                                        <input value="{{ $sisaTagihan }}" type="text"
                                            x-mask:dynamic="$money($input)" name="ttl_dibayar"
                                            class="text-end form-control selectAll ttlBayar">
                                    </td>
                                    <td>
                                        <select required name="id_akun" id="" class="select22">
                                            <option value="">Pilih Akun</option>
                                            @foreach ($akunPembayaran as $d)
                                                <option value="{{ $d->id_akun }}">{{ strtoupper($d->nm_akun) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="catatan"
                                            placeholder="catatan jika ada">
                                    </td>
                                    <td align="center">
                                        <span class="text-danger d-none errorTtlBayar">Total Bayar tidak boleh lebih
                                            dari Sisa Tagihan</span>
                                        <button onclick="return confirm('Yakin ingin dibayar ? ')" type="submit"
                                            class="btn btn-sm btn-primary btnPembayaran"><i class="fas fa-plus"></i>
                                            Tambah
                                            Pembayaran</button>
                                    </td>
                                </tr>
        </form>

        </tbody>


        </table>
        @endif
        @if ($bayarSum->ttlBayar)
            <h6>Transaksi</h6>
            <table class="table">
                <thead>
                    <tr>
                        <th class="dhead" width="150">Tgl Transaksi</th>
                        <th class="dhead">Nama Transaksi</th>
                        <th class="dhead">Catatan</th>
                        <th class="dhead text-end">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cekSudahPernahBayar as $d)
                        <tr>
                            <td>{{ tanggal($d->tgl_transaksi) }}</td>
                            <td>{{ ucwords($d->nm_transaksi) }}</td>
                            <td>{{ $d->catatan }}</td>
                            <td align="right">{{ number_format($d->jumlah, 2) }}</td>
                        </tr>
                    @endforeach
                    @foreach ($getTbhBiaya as $d)
                        <tr>
                            <td>{{ tanggal($d->tgl_tambahan) }}</td>
                            <td>Biaya Tambahan Diluar Nota</td>
                            <td>{{ $d->ket }}</td>
                            <td align="right">{{ number_format($d->ttl_rp_biaya, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        </div>

        </div>
    </x-slot>
    <x-slot name="cardFooter">



    </x-slot>
    @section('scripts')
        <script>
            $('.select22').select2()
            $(document).on('keyup', '.ttlBayar', function() {
                const ttlBayar = parseFloat($(this).val().replace(/,/g, ''))
                const sisaBayar = parseFloat($('.sisaTagihanValue').val().replace(/,/g, ''))

                if (ttlBayar > sisaBayar) {
                    $('.btnPembayaran').addClass('d-none');
                    $('.errorTtlBayar').removeClass('d-none');
                } else {
                    $('.errorTtlBayar').addClass('d-none');
                    $('.btnPembayaran').removeClass('d-none');
                }
                console.log(ttlBayar)
            })
        </script>
    @endsection
</x-theme.app>
