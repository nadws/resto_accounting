<x-theme.app title="{{ $title }}" table="Y" sizeCard="8">
    <x-slot name="cardHeader">
        <x-theme.button modal="Y" idModal="tambahModal" icon="fa-plus" addClass="float-end" teks="Tambah" />
    </x-slot>

    <x-slot name="cardBody">
        <section class="row">
            <table class="table" id="table1">
                <thead>
                    <tr>
                        <th width="5">#</th>
                        <th>NPWP</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>1</td>
                        <td>1</td>
                        <td>
                            <x-theme.button hapus="Y" href="#" icon="fa-trash"
                                addClass="float-end" teks="" variant="danger" />
                            <x-theme.button modal="Y" idModal="edit-modal" icon="fa-pen"
                                addClass="me-1 float-end edit-btn" teks="" data="url=#" />
                        </td>
                    </tr>

                </tbody>
            </table>
        </section>

    </x-slot>
</x-theme.app>