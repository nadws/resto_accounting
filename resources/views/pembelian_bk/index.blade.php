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
        <section class="row">
            <table class="table table-hover" id="table1">
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
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pembelian as $no => $p)
                    <tr>
                        <td>{{$no+1}}</td>
                        <td>{{date('d-m-Y',strtotime($p->tgl))}}</td>
                        <td>{{$p->no_nota}}</td>
                        <td>{{$p->nm_suplier}}</td>
                        <td>{{$p->suplier_akhir}}</td>
                        <td align="right">Rp. {{number_format($p->total_harga,0)}}</td>
                        {{-- <td align="right">Rp. {{number_format($p->kredit,0)}}</td>
                        <td align="right">Rp. {{number_format($p->total_harga + $p->debit - $p->kredit,0)}}</td> --}}
                        <td align="center">
                            <span
                                class="badge {{$p->lunas == 'D' ? 'bg-warning' :  ($p->total_harga + $p->debit - $p->kredit == 0 ? 'bg-success' : 'bg-danger')}}">
                                {{$p->lunas == 'D' ? 'Draft' : ($p->total_harga + $p->debit - $p->kredit == 0 ? 'Paid' :
                                'Unpaid')}}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <span class="btn btn-sm" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v text-primary"></i>
                                </span>
                                <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                    <li>
                                        <a class="dropdown-item text-primary edit_akun"
                                            href="{{route('edit_pembelian_bk',['nota' =>$p->no_nota ])}}">
                                            <i class="me-2 fas fa-pen"></i> Edit
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item  text-danger delete_nota" no_nota="{{ $p->no_nota }}"
                                            href="#" data-bs-toggle="modal" data-bs-target="#delete"><i
                                                class="me-2 fas fa-trash"></i>Delete
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item  text-info detail_nota" target="_blank"
                                            href="{{route('print_bk',['no_nota' => $p->no_nota])}}"><i
                                                class="me-2 fas fa-print"></i>Print
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </section>

        <form action="" method="get">
            <x-theme.modal title="Filter Jurnal Umum" idModal="view">
                <div class="row">
                    <div class="col-lg-12">

                        <table width="100%" cellpadding="10px">
                            <tr>
                                <td>Tanggal</td>
                                <td colspan="2">
                                    <select name="period" id="" class="form-control filter_tgl">
                                        <option value="daily">Hari ini</option>
                                        <option value="weekly">Minggu ini</option>
                                        <option value="mounthly">Bulan ini</option>
                                        <option value="costume">Custom</option>
                                    </select>
                                </td>
                            </tr>
                            <tr class="costume_muncul">
                                <td></td>
                                <td>
                                    <label for="">Dari</label>
                                    <input type="date" name="tgl1" class="form-control tgl">
                                </td>
                                <td>
                                    <label for="">Sampai</label>
                                    <input type="date" name="tgl2" class="form-control tgl">
                                </td>
                            </tr>
                        </table>
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

        });
    </script>
    @endsection
</x-theme.app>