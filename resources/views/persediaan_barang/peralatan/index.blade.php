<x-theme.app title="{{ $title }}" table="Y" sizeCard="12">
    <x-slot name="cardHeader">
        <h6>{{ $title }}</h6>
        <div class="row justify-content-end">
            <div class="col-lg-6">
                <x-theme.button modal="T" href="{{ route('peralatan.add') }}" icon="fa-plus" addClass="float-end"
                    teks="Buat Baru" />
                <x-theme.button modal="Y" idModal="view" icon="fa-print" addClass="float-end" teks="Print" />
                {{--
                <x-theme.button modal="T" href="{{ route('print_aktiva') }}" icon="fa-print" addClass="float-end"
                    teks="Print" /> --}}
            </div>
        </div>
    </x-slot>
    <x-slot name="cardBody">
        <section class="row">
            <table class="table table-hover" id="table1">
                <thead>
                    <tr>
                        <th width="5">#</th>
                        <th>Tanggal Perolehan</th>
                        <th>Nama</th>
                        <th>Kelompok</th>
                        <th>Nilai Perolehan</th>
                        <th>Penysutan Perbulan</th>
                        <th>Akumulasi Penyusutan</th>
                        <th>Nilai Buku</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($peralatan as $no => $a)
                        <tr>
                            <td>{{ $no + 1 }}</td>
                            <td>{{ date('d-m-Y', strtotime($a->tgl)) }}</td>
                            <td>{{ $a->nm_aktiva }}</td>
                            <td>{{ $a->nm_kelompok }}</td>
                            <td align="right">Rp {{ number_format($a->h_perolehan, 0) }}</td>
                            <td align="right">Rp {{ number_format($a->biaya_depresiasi, 0) }}</td>
                            <td align="right">Rp {{ number_format($a->beban, 0) }}</td>
                            <td align="right">Rp {{ number_format($a->h_perolehan - $a->beban, 0) }}</td>
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
                                            <a class="dropdown-item text-danger delete_nota" no_nota="{{ $a->id_aktiva }}"
                                                href="#" data-bs-toggle="modal" data-bs-target="#delete"><i
                                                    class="me-2 fas fa-trash"></i>Delete
                                            </a>
                                        </li>
                                        <li><a class="dropdown-item  text-info detail_nota" href="#"
                                                no_nota="" href="#" data-bs-toggle="modal"
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
        <x-theme.btn_alert_delete route="peralatan.delete_peralatan" name="id_peralatan" :tgl1="$tgl1" :tgl2="$tgl2" :id_proyek="$id_proyek" />
    </x-slot>
    @section('scripts')
    @endsection
</x-theme.app>
