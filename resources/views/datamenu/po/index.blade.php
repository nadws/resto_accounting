<x-theme.app title="{{ $title }}" table="Y" sizeCard="8">
    <x-slot name="cardHeader">
        <h6 class="float-start">{{ $title }}</h6>
        <a href="{{ route('po.add') }}" class="btn btn-sm btn-primary float-end"><i class="fas fa-plus"></i> Tambah</a>
    </x-slot>

    <x-slot name="cardBody">
        <section class="row">
            <table class="table" id="table1">
                <thead>
                    <tr>
                        <th width="5">#</th>
                        <th>Tanggal</th>
                        <th>No Po</th>
                        <th>Total Rp</th>
                        <th>Admin</th>
                        <th width="20%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>{{ tanggal(date('Y-m-d')) }}</td>
                        <td>1,000</td>
                        <td>1,000</td>
                        <td>Aldi</td>
                        <td align="right">
                            <a href="{{ route('po.print') }}" class="btn btn-sm btn-primary"><i class="fas fa-print"></i></a>
                            <a href="#" class="btn btn-sm btn-primary"><i class="fas fa-pen"></i></a>
                            <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>

                </tbody>
            </table>
        </section>

    </x-slot>
</x-theme.app>
