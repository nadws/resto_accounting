<x-theme.app title="{{$title}}" table="Y" sizeCard="12">

    <x-slot name="cardHeader">
        <div class="row justify-content-end">
            <div class="col-lg-2">

            </div>
        </div>

    </x-slot>


    <x-slot name="cardBody">
        <form action="{{route('edit_jurnal')}}" method="post" class="save_jurnal">
            @csrf
            <section class="row">

                <div class="col-lg-3">
                    <label for="">Tanggal</label>
                    <input type="date" class="form-control" name="tgl" value="{{$head_jurnal->tgl}}">
                </div>
                <div class="col-lg-3">
                    <label for="">No Nota</label>
                    <input type="text" class="form-control" name="no_nota" value="{{$no_nota}}">
                </div>
                <div class="col-lg-3">
                    <label for="">Proyek</label>
                    <select name="id_proyek" id="select2">
                        <option value="">Pilih</option>
                        @foreach ($proyek as $p)
                        <option value="{{$p->id_proyek}}" {{$head_jurnal->id_proyek == $p->id_proyek ?
                            'Selected':''}}>{{$p->nm_proyek}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-12">
                    <hr style="border: 1px solid black">
                </div>
                <div class="col-lg-12">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th width="2%">#</th>
                                <th width="25%">Akun</th>
                                <th width="28%">Keterangan</th>
                                <th width="20%" style="text-align: right;">Debit</th>
                                <th width="20%" style="text-align: right;">Kredit</th>
                                <th width="5%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jurnal as $no => $j)
                            <tr class="baris{{$no + 1}}">
                                <td style="vertical-align: top;">
                                    <button type="button" data-bs-toggle="collapse" href=".join{{$no + 1}}"
                                        class="btn rounded-pill " count="{{$no + 1}}"><i class="fas fa-angle-down"> </i>
                                    </button>
                                </td>
                                <td style="vertical-align: top;">
                                    <select name="id_akun[]" id="" class="select">
                                        <option value="">Pilih</option>
                                        @foreach ($akun as $a)
                                        <option value="{{$a->id_akun}}" {{$j->id_akun == $a->id_akun ? 'Selected' :
                                            ''}}>{{$a->nm_akun}}</option>
                                        @endforeach
                                    </select>
                                    <div class="collapse join{{$no + 1}}">
                                        <label for="" class="mt-2 ">No Dokumen</label>
                                        <input type="text" class="form-control " name="no_urut[]"
                                            value="{{$j->no_dokumen}}">
                                    </div>

                                </td>
                                <td style="vertical-align: top;">
                                    <input type="text" name="keterangan[]" class="form-control"
                                        style="vertical-align: top" value="{{$j->ket}}">

                                </td>
                                <td style="vertical-align: top;">
                                    <input type="text" class="form-control debit_rupiah text-end"
                                        value="Rp {{number_format($j->debit,0,'.','.')}}" count="{{$no + 1}}">
                                    <input type="hidden" class="form-control debit_biasa debit_biasa{{$no + 1}}"
                                        value="{{$j->debit}}" name="debit[]">
                                </td>
                                <td style="vertical-align: top;">
                                    <input type="text" class="form-control kredit_rupiah text-end"
                                        value="Rp {{number_format($j->kredit,0,'.','.')}}" count="{{$no + 1}}">
                                    <input type="hidden" class="form-control kredit_biasa kredit_biasa{{$no + 1}}"
                                        value="{{$j->kredit}}" name="kredit[]">
                                </td>
                                <td style="vertical-align: top;">
                                    <button type="button" class="btn rounded-pill remove_baris" count="{{$no + 1}}"><i
                                            class="fas fa-trash text-danger"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach


                        </tbody>
                        <tbody id="tb_baris">

                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="7">
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
                    <div class="row">
                        <x-theme.toggle name="Pilihan Lainnya">

                        </x-theme.toggle>
                        <div class="col-lg-12"></div>
                        <div class="col-lg-6 pilihan_l">
                            <label for="">No Dokumen</label>
                            <input type="text" class="form-control inp-lain" name="no_dokumen"
                                value="{{$head_jurnal->no_dokumen}}">
                        </div>
                        <div class="col-lg-6 pilihan_l">
                            <label for="">Tanggal Dokumen</label>
                            <input type="date" class="form-control inp-lain" name="tgl_dokumen"
                                value="{{$head_jurnal->tgl_dokumen}}">
                        </div>

                    </div>
                </div>
                <div class="col-lg-6">
                    <hr style="border: 1px solid blue">
                    <table class="" width="100%">
                        <tr>
                            <td width="20%">Total</td>
                            <td width="40%" class="total" style="text-align: right;">
                                Rp.{{number_format($head_jurnal->debit,0,'.','.')}}</td>
                            <td width="40%" class="total_kredit" style="text-align: right;">
                                Rp.{{number_format($head_jurnal->kredit,0,'.','.')}}</td>
                        </tr>
                        <tr>
                            <td class="cselisih" colspan="2">Selisih</td>
                            <td style="text-align: right;" class="selisih cselisih">
                                Rp.{{number_format($head_jurnal->debit - $head_jurnal->kredit,0,'.','.')}}</td>
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
        <a href="{{route('jurnal')}}" class="float-end btn btn-outline-primary me-2">Batal</a>
        </form>
    </x-slot>



    @section('scripts')
    <script src="/js/jurnal.js"></script>
    @endsection
</x-theme.app>