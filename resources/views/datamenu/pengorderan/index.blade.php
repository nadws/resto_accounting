<x-theme.app title="{{ $title }}" table="Y" sizeCard="10">
    <x-slot name="cardHeader">

         
        <h6 class="float-start">{{ $title }}</h6>
        <a href="{{ route('pengorderan.add') }}" class="btn btn-primary btn-sm float-end "><i class="fas fa-plus"></i>
            Tambah</a>

    </x-slot>

    <x-slot name="cardBody">
        <section class="row">
            <table class="table table-hover" id="table1">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>No Nota</th>
                        <th>Tanggal</th>
                        <th class="text-center">Ttl Bahan</th>
                        <th class="text-center">Ttl Qty</th>
                        <th>Status</th>
                        <th>Admin</th>
                        <th width="10%" class="text-center"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($po as $i => $d)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>
                                <a target="_blank"
                                    href="{{ route('pengorderan.print', $d->no_nota) }}">PO/{{ $d->no_nota }}</a>
                            </td>
                            <td>{{ tanggal($d->tgl_order) }}</td>
                            <td align="center">{{ number_format($d->ttlBahan, 0) }}</td>
                            <td align="center">{{ number_format($d->qty, 0) }}</td>
                            <td>
                                {{ empty($d->ttl_rp) ? 'BELUM DICEK' : 'DICEK' }}
                            </td>
                            <td>{{ strtoupper($d->admin) }}</td>
                            <td>
                                <a href="{{ route('po.add') }}" class="btn btn-sm btn-primary">Belanja</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>

    </x-slot>
</x-theme.app>
