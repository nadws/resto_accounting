<x-theme.app title="{{ $title }}" table="Y" sizeCard="8">

    <x-slot name="cardHeader">
        <div class="col-lg-6">
            <h6 class="float-start mt-1">{{ $title }} Penjualan</h6>
        </div>

    </x-slot>


    <x-slot name="cardBody">
        <style>
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                color: #000000;
                line-height: 36px;
                /* font-size: 12px; */
                width: 170px;
            }

            .dhead {
                background-color: #435EBE !important;
                color: white;
            }
        </style>
        <form action="{{ route('penyetoran.save_perencanaan') }}" method="post" class="save_jurnal">
            @csrf
            <section class="row">
                <div class="col-lg-12">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th width="1%" class="dhead">Tanggal</th>
                                <th width="5%" class="dhead">No Nota</th>
                                <th width="10%" class="dhead">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <input type="date" value="{{ date('Y-m-d') }}" class="form-control"
                                        name="tgl">
                                </td>
                                <td>
                                    <input readonly value="SAGL-{{ $no_nota }}" type="text" required
                                        class="form-control">
                                    <input value="SAGL-{{ $no_nota }}" type="hidden" required class="form-control"
                                        name="nota_setor">
                                    <input value="{{ $no_nota }}" type="hidden" required class="form-control"
                                        name="urutan">
                                </td>
                                <td>
                                    <input type="text" name="ket" class="form-control">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-striped" width="10%">
                        <thead>
                            <tr>
                                <th width="5%" class="dhead">No Nota</th>
                                <th width="15%" class="dhead">Pembayaran</th>
                                <th width="10%" class="dhead text-end">Total Rp</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $ttlDebit = 0;
                            @endphp
                            @foreach ($setor as $d)
                                @php
                                    $ttlDebit += $d->debit;
                                @endphp
                                <tr>
                                    <td>
                                        <input readonly type="text" name="no_nota[]" value="{{ $d->no_nota }}" class="form-control">
                                    </td>
                                    <td>
                                        <input readonly type="text" value="{{ $d->nm_akun }}" class="form-control">
                                        <input readonly type="hidden" name="id_jurnal[]" value="{{ $d->id_jurnal }}" class="form-control">
                                        <input readonly type="hidden" name="id_akun[]" value="{{ $d->id_akun }}" class="form-control">
                                        <input readonly type="hidden" name="debit[]" value="{{ $d->debit }}" class="form-control">
                                    </td>
                                    <td align="right">
                                        <input readonly type="text" value="Rp. {{ number_format($d->debit, 0)}}" class="form-control text-end">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2" class="fs-5">Total</th>
                                <input readonly type="hidden" name="total_debit" value="{{ $ttlDebit }}" class="form-control">
                                <th class="text-end fs-5">Rp. {{ number_format($ttlDebit, 0) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
              
            </section>
    </x-slot>
    <x-slot name="cardFooter">
        <button type="submit" class="float-end btn btn-primary button-save">Simpan</button>
        <button class="float-end btn btn-primary btn_save_loading" type="button" disabled hidden>
            <span class="spinner-border spinner-border-sm " role="status" aria-hidden="true"></span>
            Loading...
        </button>
        <a href="{{ route('penyetoran.index') }}" class="float-end btn btn-outline-primary me-2">Batal</a>
        </form>
    </x-slot>
    @section('scripts')
        <script>
            $(".select2").select2()
        </script>
    @endsection
</x-theme.app>
