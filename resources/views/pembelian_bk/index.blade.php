<x-theme.app title="{{ $title }}" table="Y" sizeCard="12">
    <x-slot name="cardHeader">
        <div class="row justify-content-end">
            <div class="col-lg-6">
                <x-theme.button modal="T" href="{{ route('pembelian_bk.add') }}" icon="fa-plus" addClass="float-end"
                    teks="Buat Baru" />
                <x-theme.button modal="Y" idModal="view" icon="fa-filter" addClass="float-end" teks="" />
            </div>
        </div>
    </x-slot>
    <x-slot name="cardBody">
        <form action="{{route('approve_invoice_bk')}}" method="post">
            @csrf
            <button class="float-end btn btn-primary btn-sm"><i class="fas fa-check"></i> Approve</button>
            <br>
            <br>
            <section class="row">
                <div class="table-responsive">


                    <table class="table table-hover" id="nanda">
                        <thead>
                            <tr>
                                <th width="5">#</th>
                                <th>Tanggal</th>
                                <th>No Nota</th>
                                <th>Suplier Awal</th>
                                <th>Suplier Akhir</th>
                                <th style="text-align: right">Total Harga</th>
                                {{-- <th style="text-align: right">Terbayar</th>
                                <th style="text-align: right">Sisa</th> --}}
                                <th style="text-align: center">Status</th>
                                <th style="text-align: center">Grading</th>
                                <th style="text-align: center">Approve <br> <input type="checkbox" name="" id="checkAll"
                                        id="">
                                </th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pembelian as $no => $p)
                            <tr>
                                <td>{{$no+1}}</td>
                                <td>{{date('d-m-Y',strtotime($p->tgl))}}</td>
                                <td>{{$p->no_nota}}</td>
                                <td>{{ucwords(strtolower($p->nm_suplier))}}</td>
                                <td>{{ucwords(strtolower($p->suplier_akhir))}}</td>
                                <td align="right">Rp. {{number_format($p->total_harga,0)}}</td>
                                {{-- <td align="right">Rp. {{number_format($p->kredit,0)}}</td>
                                <td align="right">Rp. {{number_format($p->total_harga + $p->debit - $p->kredit,0)}}</td>
                                --}}
                                <td align="center">
                                    <span
                                        class="badge {{$p->lunas == 'D' ? 'bg-warning' :  ($p->total_harga + $p->debit - $p->kredit == 0 ? 'bg-success' : 'bg-danger')}}">
                                        {{$p->lunas == 'D' ? 'Draft' : ($p->total_harga + $p->debit - $p->kredit == 0 ?
                                        'Paid' :
                                        'Unpaid')}}
                                    </span>
                                </td>
                                <td align="center">
                                    @if (empty($p->nota_grading))
                                    <i class="fas fa-times text-danger"></i>
                                    @else
                                    <a href="#" class="btn btn-sm btn-success grading_nota" no_nota="{{ $p->no_nota }}"
                                        data-bs-toggle="modal" data-bs-target="#viewgrading"><i
                                            class="fas fa-eye"></i></a>
                                    @endif

                                </td>
                                <td style="text-align: center">
                                    @if ($p->approve == 'Y')
                                    <i class="fas fa-check text-success"></i>
                                    <input type="hidden" name="ceknota[]" id="" value="Y">
                                    @else
                                    <input type="checkbox" name="ceknota[]" class="checkbox-item" id=""
                                        value="{{$p->no_nota}}">
                                    @endif

                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <span class="btn btn-sm" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v text-primary"></i>
                                        </span>
                                        <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                            @if ($p->approve == 'Y')

                                            @else
                                            <li>
                                                <a class="dropdown-item text-primary edit_akun"
                                                    href="{{route('edit_pembelian_bk',['nota' =>$p->no_nota ])}}">
                                                    <i class="me-2 fas fa-pen"></i> Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item  text-danger delete_nota"
                                                    no_nota="{{ $p->no_nota }}" href="#" data-bs-toggle="modal"
                                                    data-bs-target="#delete"><i class="me-2 fas fa-trash"></i>Delete
                                                </a>
                                            </li>
                                            @endif
                                            {{-- <li>
                                                <a class="dropdown-item  text-info detail_nota" target="_blank"
                                                    href="{{route('print_bk',['no_nota' => $p->no_nota])}}"><i
                                                        class="me-2 fas fa-print"></i>Detail
                                                </a>
                                            </li> --}}
                                            <li>
                                                <a class="dropdown-item  text-info detail_nota" target="_blank"
                                                    href="{{route('print_bk',['no_nota' => $p->no_nota])}}"><i
                                                        class="me-2 fas fa-print"></i>Print
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#" class="dropdown-item  text-info grading_notatambah"
                                                    no_nota="{{ $p->no_nota }}" data-bs-toggle="modal"
                                                    data-bs-target="#grading"><i
                                                        class="me-2 fas fa-balance-scale-right"></i>Grading
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </form>

        <form action="{{route('grading')}}" method="post">
            @csrf
            <x-theme.modal title="Campur BKIN" idModal="grading">
                <div id="grading_nota2"></div>
            </x-theme.modal>
        </form>


        <x-theme.modal title="Campur BKIN" size="modal-lg" idModal="viewgrading" btnSave="T">
            <div id="grading_nota"></div>

        </x-theme.modal>


        <form action="" method="get">
            <x-theme.modal title="Filter Jurnal Umum" idModal="view">
                <div class="row">
                    <div class="col-lg-3">Tanggal :</div>
                    <div class="col-lg-1">:</div>
                    <div class="col-lg-8">
                        <select name="period" id="" class="form-control filter_tgl">
                            <option value="daily">Hari ini</option>

                            <option value="mounthly">Bulan ini</option>
                            <option value="costume">Custom</option>
                        </select>
                    </div>
                    <div class="col-lg-4 mt-2"></div>
                    <div class="col-lg-4 costume_muncul mt-2">
                        <label for="">Dari</label>
                        <input type="date" name="tgl1" class="form-control tgl">
                    </div>
                    <div class="col-lg-4 costume_muncul mt-2">
                        <label for="">Sampai</label>
                        <input type="date" name="tgl2" class="form-control tgl">
                    </div>
                    <div class="col-lg-4 bulan_muncul mt-2">
                        <label for="">Bulan</label>
                        <select name="bulan" id="bulan" class="selectView bulan">
                            @foreach($listbulan as $l)
                            <option value="{{ $l->bulan }}" {{ (int) date('m')==$l->bulan ? 'selected' : ''
                                }}>{{
                                $l->nm_bulan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4 bulan_muncul mt-2">
                        <label for="">Tahun</label>
                        <select name="tahun" id="" class="selectView bulan">
                            <option value="2023">2023</option>
                        </select>
                    </div>
                </div>

            </x-theme.modal>
        </form>

        <form action="{{ route('delete_bk') }}" method="get">
            <div class="modal fade" id="delete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="row">
                                <h5 class="text-danger ms-4 mt-4"><i class="fas fa-trash"></i> Hapus Data</h5>
                                <p class=" ms-4 mt-4">Apa anda yakin ingin menghapus ?</p>
                                <input type="hidden" class="no_nota" name="no_nota">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>





    </x-slot>
    @section('scripts')
    <script>
        $(document).ready(function() {
            $(document).on('click', '.delete_nota', function(){
                    var no_nota = $(this).attr('no_nota');
                    $('.no_nota').val(no_nota);
            })
            $(document).on('click', '.grading_nota', function(){
                var no_nota = $(this).attr('no_nota');
                $.ajax({
                    type: "get",
                    url: "/get_grading?no_nota=" + no_nota,
                    success: function (data) {
                        $('#grading_nota').html(data);
                        $('.nota_grading').val(no_nota);
                        $('.nota_grading_text').text(no_nota);
                    }
                });
                
            });
            $(document).on('click', '.grading_notatambah', function(){
                var no_nota = $(this).attr('no_nota');

                $.ajax({
                    type: "get",
                    url: "/get_grading2?no_nota=" + no_nota,
                    success: function (data) {
                        $('#grading_nota2').html(data);
                        $('.nota_grading').val(no_nota);
                        $('.nota_grading_text').text(no_nota); 
                    }
                });
                
                
            });

            $(document).on('click', '#checkAll', function(){
                // Setel properti checked dari kotak centang individu sesuai dengan status "cek semua"
                $('.checkbox-item').prop('checked', $(this).prop('checked'));
            });

        });
    </script>
    @endsection
</x-theme.app>