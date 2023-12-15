<x-theme.app title="{{ $title }}" table="Y" sizeCard="12">

    <x-slot name="cardHeader">
        <div class="row justify-content-end">
            <div class="col-lg-6">
                <h6 class="float-start mt-1">{{ $title }}</h6>
            </div>
            <div class="col-lg-6"></div>
        </div>

    </x-slot>


    <x-slot name="cardBody">
        <form action="{{ route('jurnal.update_jurnal') }}" method="post" class="save_jurnal">
            @csrf
            <section class="row">

                <div class="col-lg-3">
                    <label for="">Tanggal</label>
                    <input type="date" class="form-control" name="tgl" value="{{ $head_jurnal->tgl }}">
                </div>
                <div class="col-lg-3">
                    <label for="">No Urut Jurnal</label>
                    <input readonly type="text" class="form-control" name="no_nota" value="{{ $no_nota }}">
                    <input type="hidden" class="form-control" name="id_buku" value="{{ $head_jurnal->id_buku }}">
                </div>

                <div class="col-lg-12">
                    <hr style="border: 1px solid black">
                </div>
                <div class="col-lg-12">
                    <style>
                        .select2-container--default .select2-selection--single .select2-selection__rendered {
                            color: #000000;
                            line-height: 36px;
                            /* font-size: 12px; */
                            width: 150px;

                        }
                    </style>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="dhead" width="2%">#</th>
                                <th class="dhead" width="14%">Akun</th>
                                <th class="dhead" width="14%">Sub Akun</th>
                                <th class="dhead" width="19%">Keterangan</th>
                                <th class="dhead" width="12%" style="text-align: right;">Debit</th>
                                <th class="dhead" width="12%" style="text-align: right;">Kredit</th>
                                <th class="dhead" width="5%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jurnal as $no => $j)
                                @php
                                    $post = DB::table('tb_post_center')
                                        ->where('id_akun', $j->id_akun)
                                        ->get();
                                @endphp
                                <tr class="baris{{ $no + 1 }}">
                                    <td style="vertical-align: top;">
                                        {{-- <button type="button" data-bs-toggle="collapse" href=".join{{ $no + 1 }}"
                                        class="btn rounded-pill " count="{{ $no + 1 }}"><i class="fas fa-angle-down">
                                        </i>
                                    </button> --}}
                                    </td>
                                    <td style="vertical-align: top;">
                                        <select name="id_akun[]" id=""
                                            class="select2_add pilih_akun pilih_akun{{ $no + 1 }}"
                                            count="{{ $no + 1 }}" required>
                                            <option value="">Pilih</option>
                                            @foreach ($akun as $a)
                                                <option value="{{ $a->id_akun }}"
                                                    {{ $j->id_akun == $a->id_akun ? 'Selected' : '' }}>
                                                    {{ $a->nm_akun }}</option>
                                            @endforeach
                                        </select>
                                        <div class="">
                                            <label for="" class="mt-2 ">Urutan Pengeluaran</label>
                                            <input type="text" class="form-control " name="no_dokumen[]"
                                                value="{{ $j->no_dokumen }}">
                                        </div>
                                        {{-- <div class="collapse join{{ $no + 1 }}">
                                        <label for="" class="mt-2 ">No CFM</label>
                                        <input type="text" class="form-control " name="no_urut[]"
                                            value="{{ $j->no_urut }}">
                                    </div> --}}
                                        <input type="hidden" class="form-control " name="no_urut[]"
                                            value="{{ $j->no_urut }}">
                                        <input type="hidden" class="form-control " name="id_akun2[]"
                                            value="{{ $j->id_akun }}">
                                    </td>
                                    <td style="vertical-align: top;">
                                        <select name="id_post[]" id="" class="select2_add post{{ $no + 1 }}">
                                            <option value="">Pilih sub akun</option>
                                            @foreach ($post as $p)
                                                <option value="{{ $p->id_post_center }}"
                                                    {{ $p->id_post_center == $j->id_post_center ? 'selected' : '' }}>
                                                    {{ $p->nm_post }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td style="vertical-align: top;">
                                        <input type="text" name="keterangan[]" class="form-control"
                                            style="vertical-align: top" value="{{ $j->ket }}">
                                        <input type="hidden" name="id_jurnal[]" class="form-control"
                                            style="vertical-align: top" value="{{ $j->id_jurnal }}">
                                    </td>
                                    <td style="vertical-align: top;">
                                        <input onclick="selectAllText(this)" type="text" class="form-control debit_rupiah text-end"
                                            value="Rp {{ number_format($j->debit, 2, ',', '.') }}"
                                            count="{{ $no + 1 }}">
                                        <input type="hidden"
                                            class="form-control debit_biasa debit_biasa{{ $no + 1 }}"
                                            value="{{ $j->debit }}" name="debit[]">
                                    </td>
                                    <td style="vertical-align: top;">
                                        <input onclick="selectAllText(this)" type="text" class="form-control kredit_rupiah text-end"
                                            value="Rp {{ number_format($j->kredit, 2, ',', '.') }}"
                                            count="{{ $no + 1 }}">
                                        <input type="hidden"
                                            class="form-control kredit_biasa kredit_biasa{{ $no + 1 }}"
                                            value="{{ $j->kredit }}" name="kredit[]">
                                    </td>

                                    <td style="vertical-align: top;">
                                        <button type="button" class="btn rounded-pill remove_baris"
                                            count="{{ $no + 1 }}"><i class="fas fa-trash text-danger"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach


                        </tbody>
                        <tbody id="tb_baris">

                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="9">
                                    <button type="button" class="btn btn-block btn-lg tbh_baris"
                                        style="background-color: #F4F7F9; color: #8FA8BD; font-size: 14px; padding: 13px;">
                                        <i class="fas fa-plus"></i> Tambah Baris Baru

                                    </button>
                                </th>
                            </tr>
                        </tfoot>


                    </table>
                </div>
                <div class="col-lg-6">

                </div>
                <div class="col-lg-6">
                    <hr style="border: 1px solid blue">
                    <table class="" width="100%">
                        <tr>
                            <td width="20%">Total</td>
                            <td width="40%" class="total" style="text-align: right;">
                                Rp.{{ number_format($head_jurnal->debit, 2, ',', '.') }}</td>
                            <td width="40%" class="total_kredit" style="text-align: right;">
                                Rp.{{ number_format($head_jurnal->kredit, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="cselisih" colspan="2">Selisih</td>
                            <td style="text-align: right;" class="selisih cselisih">
                                Rp.{{ number_format($head_jurnal->debit - $head_jurnal->kredit, 2, ',', '.') }}</td>
                        </tr>
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
        <a href="{{ route('jurnal.index') }}" class="float-end btn btn-outline-primary me-2">Batal</a>
        </form>
    </x-slot>



    @section('scripts')
    <script src="{{ asset('theme') }}/assets/js/jurnal/format_rupiah.js"></script>
    <script src="{{ asset('theme') }}/assets/js/jurnal/load_menu.js"></script>
        <script>
            function selectAllText(input) {
                console.log(input)
                $(input).focus();
                $(input).select();
            }
            $(".select2suplier").select2()
        </script>
    @endsection
</x-theme.app>
