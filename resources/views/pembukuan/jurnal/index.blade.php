<x-theme.app cont="container-fluid" title="{{ $title }}" table="Y" sizeCard="12">
    <x-slot name="cardHeader">
        <div class="row justify-content-end">
            <div class="col-lg-6">
                <h6 class="float-start mt-1">{{ $title }} : {{ tanggal($tgl1) }}
                    ~ {{ tanggal($tgl2) }}</h6>
            </div>

            <div class="col-lg-6">
                <x-theme.button modal="T"
                    href="{{ $id_buku != '13' ? route('jurnal.add', ['id_buku' => $id_buku]) : route('add_balik_aktiva', ['id_buku' => $id_buku]) }}"
                    icon="fa-plus" addClass="float-end" teks="Buat Baru" />
                <x-theme.button modal="Y" idModal="view" icon="fa-calendar-week" addClass="float-end"
                    teks="View" />
            </div>
            <div class="col-lg-12">
                <hr style="border: 2px solid #435EBE">
            </div>
            @include('pembukuan.jurnal.nav')
        </div>
    </x-slot>
    <x-slot name="cardBody">
        <section class="row">
            @php
                $ttl_debit = 0;
                $ttl_kredit = 0;
            @endphp
            @foreach ($jurnal as $no => $a)
                @php
                    $ttl_debit += $a->debit;
                    $ttl_kredit += $a->kredit;
                @endphp
            @endforeach
            <table class="table table-hover" id="table1">
                <thead>
                    <tr>
                        <th width="5">#</th>
                        <th style="white-space: nowrap;">No Urut Jurnal</th>
                        <th style="white-space: nowrap;">No Urut Akun</th>
                        <th style="white-space: nowrap;">No Urutan Pengeluaran</th>
                        <th>Tanggal</th>
                        <th>Akun</th>
                        <th>Sub Akun</th>
                        <th>Keterangan</th>
                        <th style="text-align: right">Debit <br> ({{ number_format($ttl_debit, 0) }})</th>
                        <th style="text-align: right">Kredit <br> ({{ number_format($ttl_kredit, 0) }})</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jurnal as $no => $a)
                        <tr>
                            <td>{{ $no + 1 }}</td>
                            <td>{{ $a->no_nota }}</td>
                            <td>{{ $a->no_urut }}</td>
                            <td>{{ $a->no_dokumen }}</td>
                            <td class="nowrap">{{ date('d-m-Y', strtotime($a->tgl)) }}</td>
                            <td>{{ ucwords(strtolower($a->nm_akun)) }}
                            </td>
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
                                @if ($a->penutup == 'Y')
                                @else
                                    @if ($id_buku == 13 || $id_buku == '10')
                                        <div class="btn-group" role="group">
                                            <span class="btn btn-sm" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v text-primary"></i>
                                            </span>
                                            <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                <li><a class="dropdown-item  text-info detail_nota" href="#"
                                                        no_nota="{{ $a->no_nota }}" href="#"
                                                        data-bs-toggle="modal" data-bs-target="#detail"><i
                                                            class="me-2 fas fa-search"></i>Detail</a>
                                                </li>
                                            </ul>
                                        </div>
                                    @else
                                        <div class="btn-group" role="group">
                                            <span class="btn btn-sm" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v text-primary"></i>
                                            </span>
                                            <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                @if ($id_buku != '1')
                                                    <li><a class="dropdown-item text-primary edit_akun"
                                                            href="#"><i class="me-2 fas fa-pen"></i>Edit</a>
                                                    </li>
                                                @endif
                                                <li>
                                                    <a class="dropdown-item text-danger delete_nota"
                                                        no_nota="{{ $a->no_nota }}" href="#"
                                                        data-bs-toggle="modal" data-bs-target="#delete"><i
                                                            class="me-2 fas fa-trash"></i>Delete
                                                    </a>
                                                </li>
                                                <li><a class="dropdown-item  text-info detail_nota" href="#"
                                                        no_nota="{{ $a->no_nota }}" href="#"
                                                        data-bs-toggle="modal" data-bs-target="#detail"><i
                                                            class="me-2 fas fa-search"></i>Detail</a>
                                                </li>
                                            </ul>
                                        </div>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>

        <form action="" method="get">
            <x-theme.modal title="Filter Tanggal" idModal="view">
                <input type="hidden" name="id_buku" value="{{ $id_buku }}">
                <div class="row">
                    <div class="col-lg-3">Filter</div>
                    <div class="col-lg-1">:</div>
                    <div class="col-lg-8">
                        <select name="period" id="" class="form-control filter_tgl">
                            <option value="daily">Hari ini</option>
                            <option value="mounthly">Bulan </option>
                            <option value="years">Tahun</option>
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
                            @php
                                $listBulan = DB::table('bulan')->get();
                            @endphp
                            @foreach ($listBulan as $l)
                                <option value="{{ $l->bulan }}"
                                    {{ (int) date('m') == $l->bulan ? 'selected' : '' }}>
                                    {{ $l->nm_bulan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4 bulan_muncul mt-2">
                        <label for="">Tahun</label>
                        <select name="tahun" id="" class="selectView bulan">
                            <option value="2022">2022</option>
                            <option value="2023" selected>2023</option>
                        </select>
                    </div>
                    <div class="col-lg-8 tahun_muncul mt-2">
                        <label for="">Tahun</label>
                        <select name="tahunfilter" id="" class="selectView tahun">
                            <option value="2022">2022</option>
                            <option value="2023">2023</option>
                        </select>
                    </div>
                </div>

            </x-theme.modal>
        </form>


    </x-slot>

</x-theme.app>
