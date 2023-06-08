<x-theme.app title="{{ $title }}" nav="Y" rot1="bahan_baku.index" rot2="bahan_baku.stok_masuk"
    rot3="bahan_baku.opname" table="Y" sizeCard="12">
    <x-slot name="cardHeader">
        <hr class="mt-3">
        <div class="col-lg-6">
            <h6 class="float-start mt-1">{{ $title }}
            </h6>

        </div>
        <div class="row justify-content-end">
            <div class="col-lg-4">
                <select name="example" class="form-control float-end select-gudang" id="select2">
                    <option value="" selected>All Warehouse </option>
                    @foreach ($gudang as $g)
                        <option {{ Request::segment(3) == $g->id_gudang ? 'selected' : '' }} value="{{ $g->id_gudang }}">
                            {{ ucwords($g->nm_gudang) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3">
                @if (!empty($create))
                    <a href="{{ route('bahan_baku.stok_masuk_add') }}" class="btn btn-primary float-end"> <i
                            class="fas fa-plus"></i> Tambah</a>
                @endif

                <x-theme.akses :halaman="$halaman" route="bahan_baku.stok_masuk" />
            </div>
        </div>
    </x-slot>
    <x-slot name="cardBody">

        <section class="row">
            <table class="table table-hover" id="table">
                <thead>
                    <tr>
                        <th width="2">#</th>
                        <th class="text-center">Tanggal</th>
                        <th>No Nota</th>
                        <th>Status</th>
                        <th class="text-center">Jumlah Barang</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stok as $no => $d)
                        <tr class="tbl" data-href="javascript:void(0)">
                            <td class="td-href">{{ $no + 1 }}</td>
                            <td class="td-href" align="center">{{ tanggal($d->tgl) }}</td>
                            <td class="td-href">{{ $d->no_nota }}</td>
                            <td class="td-href">
                                <div class="btn btn-sm btn-{{ $d->jenis == 'draft' ? 'warning' : 'success' }}">
                                    {{ ucwords($d->jenis) }}</div>
                            </td>
                            <td class="td-href" align="center">{{ $d->debit }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <span class="btn btn-sm" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v text-primary"></i>
                                    </span>
                                    <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                        @php
                                            $emptyKondisi = [$edit, $delete, $detail, $print];
                                        @endphp
                                        <x-theme.dropdown_kosong :emptyKondisi="$emptyKondisi" />
                                        @if ($d->jenis == 'draft')
                                            @if (!empty($edit))
                                                <li>
                                                    <a class="dropdown-item text-primary edit"
                                                        href="{{ route('stok_masuk.add', ['no_nota' => encrypt($d->no_nota)]) }}"><i
                                                            class="me-2 fas fa-pen"></i>
                                                        Edit</a>
                                                </li>
                                            @endif

                                            @if (!empty($delete))
                                                <li>
                                                    <a class="dropdown-item text-danger"
                                                        onclick="return confirm('Yakin dihapus ?')"
                                                        href="{{ route('stok_masuk.delete', $d->no_nota) }}"><i
                                                            class="me-2 fas fa-trash"></i> Delete</a>
                                                </li>
                                            @endif
                                        @endif
                                        @if (!empty($detail))
                                            <li>
                                                <a class="dropdown-item text-info detail_nota"
                                                    no_nota="{{ $d->no_nota }}" href="#" data-bs-toggle="modal"
                                                    data-bs-target="#detail"><i class="me-2 fas fa-search"></i>
                                                    Detail</a>
                                            </li>
                                        @endif
                                        @if (!empty($print))
                                            <li>
                                                <a class="dropdown-item text-info"
                                                    href="{{ route('stok_masuk.cetak', ['no_nota' => encrypt($d->no_nota)]) }}"><i
                                                        class="me-2 fas fa-print"></i>
                                                    Cetak</a>
                                            </li>
                                            
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>


    </x-slot>

    @section('scripts')
        <script>
            $(document).ready(function() {
                inputChecked('checkAll', 'checkItem')

                $(".select-gudang").change(function(e) {
                    e.preventDefault();
                    var gudang_id = $(this).val()
                    document.location.href = `/bahan_baku/stok_masuk/${gudang_id}`
                });
                edit('detail_nota', 'no_nota', 'stok_masuk/edit', 'load-edit')

                pencarian('pencarian', 'tableProduk')

                document.querySelectorAll('tbody .tbl').forEach(function(row) {
                    row.addEventListener('click', function() {
                        window.location.href = row.getAttribute('data-href');
                    });
                });
            });
        </script>
    @endsection
</x-theme.app>
