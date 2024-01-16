<x-theme.app title="{{ $title }}" table="Y" sizeCard="9">
    <x-slot name="cardHeader">
        <div class="row">
            <div class="col-lg-12">
                @include('datamenu.po.nav', ['tgl1' => $tgl1, 'tgl2' => $tgl2])
            </div>
            <div class="col-lg-12">
                <hr>
            </div>
        </div>
        <h6 class="float-start">{{ $title }}</h6>


    </x-slot>


    <x-slot name="cardBody">
        <section class="row">
            <table class="table" id="table1">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>No Nota</th>
                        <th>Suplier</th>
                        <th>Tanggal</th>
                        <th>Total Rp</th>
                        <th>Status</th>
                        <th>Admin</th>
                        <th width="20%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($po as $i => $d)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td><a href="{{ route('po.detail', $d->no_nota) }}">PO/{{ $d->no_nota }}</a></td>
                            <td>{{ $d->nm_suplier }}</td>
                            <td>{{ tanggal($d->tgl) }}</td>
                            <td>{{ number_format($d->sub_total, 0) }}</td>
                            @php
                                $warna = [
                                    'disetujui' => 'warning',
                                    'hutang' => 'danger',
                                    'lunas' => 'primary',
                                    'selesai' => 'primary',
                                ];
                            @endphp
                            <td class="text-{{ $warna[$d->status] }}">
                                <span class="badge badge-sm bg-{{$warna[$d->status]}}">{{ ucwords($d->status) }}</span>
                            </td>
                            <td>{{ $d->admin }}</td>
                            <td align="right">

                                <a target="_blank" href="{{ route('po.print', $d->no_nota) }}"
                                    class="btn btn-sm btn-primary"><i class="fas fa-print"></i></a>
                                <a href="{{ route('po.detail', $d->no_nota) }}" class="btn btn-sm btn-primary"><i
                                        class="fas fa-eye"></i></a>
                                <a onclick="return confirm('Yakin dihapus ? ')"
                                    href="{{ route('po.delete', $d->no_nota) }}" class="btn btn-sm btn-danger"><i
                                        class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </section>

    </x-slot>
</x-theme.app>
