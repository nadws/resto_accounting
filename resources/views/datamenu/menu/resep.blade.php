<x-theme.app title="{{ $title }}" table="Y" sizeCard="8">
    <x-slot name="cardHeader">
        <div class="row">

        </div>
        <h6 class="float-start">{{ $title }}</h6>
        <div class="row justify-content-end">


        </div>

    </x-slot>

    <x-slot name="cardBody">
        <section class="row">
            <div class="col-lg-12">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Bahan</th>
                            <th width="20%">Qty</th>
                            <th>Satuan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="" id="" class="select2_add">
                                    <option value="">Pilih Bahan</option>
                                    @foreach ($atk as $a)
                                        <option value="{{ $a->id_atk }}">{{ $a->nm_atk }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="text" class="form-control"></td>
                            <td>Gr</td>
                            <td class="text-center"><a><i class="fas fa-trash text-danger fa-2x"></i></a></td>
                        </tr>
                        <tr>
                            <td>
                                <select name="" id="" class="select2_add">
                                    <option value="">Pilih Bahan</option>
                                    @foreach ($atk as $a)
                                        <option value="{{ $a->id_atk }}">{{ $a->nm_atk }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="text" class="form-control"></td>
                            <td>Gr</td>
                            <td class="text-center"><a><i class="fas fa-trash text-danger fa-2x"></i></a></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <td colspan="2"></td>
                        <td colspan="2"><button class="btn btn-primary float-end">Simpan</button></td>
                    </tfoot>
                </table>
            </div>


        </section>
        @section('scripts')
        @endsection
    </x-slot>


</x-theme.app>
