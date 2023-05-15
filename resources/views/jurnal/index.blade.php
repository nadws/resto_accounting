<x-theme.app title="{{ $title }}" table="Y" sizeCard="12">
    <x-slot name="cardHeader">
        <div class="row justify-content-end">
            <div class="col-lg-6">

                <x-theme.button modal="Y" idModal="import" icon="fa-upload" variant="success" addClass="float-end"
                    teks="Import" />
                <a href="{{ route('export_jurnal', ['tgl1' => $tgl1, 'tgl2' => $tgl2, 'id_proyek' => $id_proyek, 'id_buku' => '2']) }}"
                    class="float-end btn   btn-success me-2"><i class="fas fa-file-excel"></i> Export</a>
                <x-theme.button modal="T" href="{{ route('jurnal.add') }}" icon="fa-plus" addClass="float-end"
                    teks="Buat Baru" />
                <x-theme.button modal="Y" idModal="view" icon="fa-filter" addClass="float-end" teks="" />

                <x-theme.akses />
                <form action="{{ route('akses.save') }}" method="post">
                    @csrf
                    <div id="akses" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog  modal-lg-max" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myModalLabel33">
                                        Akses {{ $title }}
                                    </h4>
                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                        <i data-feather="x"></i>
                                    </button>
                                </div>
                                <input type="hidden" name="route" value="sistem_po">
                                <div class="modal-body">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Nama</th>
                                                <th>Halaman</th>
                                                <th>Create</th>
                                                <th>Read</th>
                                                <th>Update</th>
                                                <th>Delete</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bx bx-x d-block d-sm-none"></i>
                                        <span class="d-none d-sm-block">Save</span>
                                    </button>
                                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                        <i class="bx bx-x d-block d-sm-none"></i>
                                        <span class="d-none d-sm-block">Close</span>
                                    </button>
                                </div>
                
                            </div>
                        </div>
                    </div>
                </form>
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
                        <th>No Cfm</th>
                        <th>Akun</th>
                        <th>Sub Akun</th>
                        <th>Keterangan</th>
                        <th style="text-align: right">Debit</th>
                        <th style="text-align: right">Kredit</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jurnal as $no => $a)
                        <tr>
                            <td>{{ $no + 1 }}</td>
                            <td class="nowrap">{{ date('d-m-Y', strtotime($a->tgl)) }}</td>
                            <td>{{ $a->no_nota }}</td>
                            <td>{{ $a->no_urut }}</td>
                            <td>{{ ucwords(strtolower($a->nm_akun)) }}</td>
                            <td>{{ ucwords(strtolower($a->nm_post ?? '')) }}</td>
                            @if (strlen($a->ket) > 60)
                                <td>
                                    <span class="teksLimit{{ $a->id_jurnal }}">
                                        {{ Str::limit($a->ket, 30, '...') }}
                                        <a href="#" class="readMore" id="{{ $a->id_jurnal }}">read
                                            more</a>
                                    </span>
                                    <span class="teksFull{{ $a->id_jurnal }}" style="display:none">{{ $a->ket }}
                                        <a href="#" class="less" id="{{ $a->id_jurnal }}">less</a></span>
                                </td>
                            @else
                                <td>
                                    {{ $a->ket }}
                                </td>
                            @endif
                            <td align="right">{{ number_format($a->debit, 2) }}</td>
                            <td align="right">{{ number_format($a->kredit, 2) }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <span class="btn btn-sm" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v text-primary"></i>
                                    </span>
                                    <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                        <li><a class="dropdown-item text-primary edit_akun"
                                                href="{{ route('edit_jurnal', ['no_nota' => $a->no_nota]) }}"><i
                                                    class="me-2 fas fa-pen"></i>Edit</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item  text-danger delete_nota"
                                                no_nota="{{ $a->no_nota }}" href="#" data-bs-toggle="modal"
                                                data-bs-target="#delete"><i class="me-2 fas fa-trash"></i>Delete
                                            </a>
                                        </li>
                                        <li><a class="dropdown-item  text-info detail_nota" href="#"
                                                no_nota="{{ $a->no_nota }}" href="#" data-bs-toggle="modal"
                                                data-bs-target="#detail"><i class="me-2 fas fa-search"></i>Detail</a>
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
                            <tr>
                                <td>Proyek</td>
                                <td colspan="2">
                                    <select name="id_proyek" id="selectView" class="">
                                        <option value="0">All</option>
                                        @foreach ($proyek as $p)
                                            <option value="{{ $p->id_proyek }}">{{ $p->nm_proyek }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

            </x-theme.modal>
        </form>
        <form action="{{ route('import_jurnal') }}" method="post" enctype="multipart/form-data">
            @csrf
            <x-theme.modal title="Import Jurnal" idModal="import">
                <div class="row">
                    <div class="col-lg-12">
                        <label for="">File Excel (Format: @file.xlsx)</label>
                        <input type="file" name="file" id="" class="form-control">
                    </div>
                </div>

            </x-theme.modal>
        </form>

        <form action="{{ route('jurnal-delete') }}" method="get">
            <div class="modal fade" id="delete" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="row">
                                <h5 class="text-danger ms-4 mt-4"><i class="fas fa-trash"></i> Hapus Data</h5>
                                <p class=" ms-4 mt-4">Apa anda yakin ingin menghapus ?</p>
                                <input type="hidden" class="no_nota" name="no_nota">
                                <input type="hidden" name="tgl1" value="{{ $tgl1 }}">
                                <input type="hidden" name="tgl2" value="{{ $tgl2 }}">
                                <input type="hidden" name="id_proyek" value="{{ $id_proyek }}">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger"
                                data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <x-theme.modal title="Detail Jurnal" size="modal-lg-max" idModal="detail">
            <div class="row">
                <div class="col-lg-12">
                    <div id="detail_jurnal"></div>
                </div>
            </div>

        </x-theme.modal>




    </x-slot>
    @section('scripts')
        <script>
            $(document).ready(function() {
                function readMore() {
                    $(document).on('click', '.readMore', function(e) {
                        e.preventDefault()
                        var id = $(this).attr('id')
                        $(".teksLimit" + id).css('display', 'none')
                        $(".teksFull" + id).css('display', 'block')
                    })
                    $(document).on('click', '.less', function(e) {
                        e.preventDefault()
                        var id = $(this).attr('id')
                        $(".teksLimit" + id).css('display', 'block')
                        $(".teksFull" + id).css('display', 'none')
                    })
                }
                readMore()
                $(document).on('click', '.delete_nota', function() {
                    var no_nota = $(this).attr('no_nota');
                    $('.no_nota').val(no_nota);
                })
                $('.selectView').select2({
                    dropdownParent: $('#view .modal-content')
                });


                $(document).on("click", ".detail_nota", function() {
                    var no_nota = $(this).attr('no_nota');
                    $.ajax({
                        type: "get",
                        url: "/detail_jurnal?no_nota=" + no_nota,
                        success: function(data) {
                            $("#detail_jurnal").html(data);
                        }
                    });

                });



            });
        </script>
    @endsection
</x-theme.app>
