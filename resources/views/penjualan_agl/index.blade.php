<x-theme.app title="{{ $title }}" table="Y" sizeCard="10">
    <x-slot name="cardHeader">
        <div class="row">
            <div class="col-lg-6">
                <h6 class="float-start mt-1">{{ $title }}: {{tanggal($tgl1)}} ~ {{tanggal($tgl2)}}</h6>
            </div>
            <div class="col-lg-6">
                <x-theme.button modal="T" href="{{ route('tbh_invoice_telur') }}" icon="fa-plus" addClass="float-end"
                    teks="Buat Invoice" />
                <x-theme.btn_filter />
            </div>
        </div>
    </x-slot>
    <x-slot name="cardBody">
        <section class="row">
            <table class="table table-hover" id="table">
                <thead>
                    <tr>
                        <th width="5">#</th>
                        <th>Tanggal</th>
                        <th>No Nota</th>
                        <th>Customer</th>
                        <th style="text-align: right">Total Rp</th>
                        <th>Status</th>
                        <th>Tipe Jual</th>
                        <th>Admin</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoice as $no => $i)
                    <tr>
                        <td>{{$no+1}}</td>
                        <td>{{tanggal($i->tgl)}}</td>
                        <td>{{$i->no_nota}}</td>
                        <td>{{$i->nm_customer}}{{$i->urutan_customer}}</td>
                        <td align="right">Rp {{number_format($i->ttl_rp,0)}}</td>
                        <td>
                            <span
                                class="badge {{ $i->debit_bayar - $i->kredit_bayar != '0' ? 'bg-warning' : 'bg-success' }}">
                                {{ $i->debit_bayar - $i->kredit_bayar != '0' ? 'Unpaid' : 'Paid' }}
                            </span>
                        </td>
                        <td>{{$i->tipe}}</td>
                        <td>{{$i->admin}}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <span class="btn btn-sm" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v text-primary"></i>
                                </span>
                                <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                    <li><a class="dropdown-item text-primary edit_akun" href=""><i
                                                class="me-2 fas fa-pen"></i>Edit</a>
                                    </li>

                                    <li>
                                        <a class="dropdown-item text-danger delete_nota" no_nota="" href="#"
                                            data-bs-toggle="modal" data-bs-target="#delete"><i
                                                class="me-2 fas fa-trash"></i>Delete
                                        </a>
                                    </li>
                                    <li><a class="dropdown-item  text-info detail_nota" href="#" href="#"
                                            data-bs-toggle="modal" no_nota="{{ $i->no_nota }}"
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

        {{-- sub akun --}}
        <x-theme.modal title="Edit Akun" idModal="sub-akun" size="modal-lg">
            <div id="load-sub-akun">
            </div>
        </x-theme.modal>

        <x-theme.modal title="Detail Invoice" btnSave='T' size="modal-lg-max" idModal="detail">
            <div class="row">
                <div class="col-lg-12">
                    <div id="detail_invoice"></div>
                </div>
            </div>

        </x-theme.modal>
        {{-- end sub akun --}}
    </x-slot>
    @section('scripts')
    <script>
        $(document).ready(function() {
            $(document).on("click", ".detail_nota", function() {
                var no_nota = $(this).attr('no_nota');
                $.ajax({
                    type: "get",
                    url: "/detail_invoice_telur?no_nota=" + no_nota,
                    success: function(data) {
                        $("#detail_invoice").html(data);
                    }
                });

            });
        });
    </script>
    @endsection
</x-theme.app>