<x-theme.app title="{{ $title }}" table="Y" sizeCard="12">
    <x-slot name="cardHeader">
        <div class="row">
            <div class="col-lg-2">
                <h6 class="float-start">{{ $title }}</h6>
            </div>
            <div class="col-lg-4">

            </div>
            <div class="col-lg-6 ">


            </div>

        </div>
    </x-slot>

    <x-slot name="cardBody">
        <form action="{{ route('permission.create') }}" method="post">
            @csrf
            <section class="row">
                <div class="col-lg-6">
                    <table>
                        <tr>
                            <td>
                                <label for="">Pencarian :</label>
                            </td>
                            <td>
                                <input autofocus type="text" id="pencarian" class="form-control float-end">
                            </td>
                        </tr>
                    </table>

                </div>
                <div class="col-lg-6">
                    <button class="btn btn-primary btn-md float-end" type="submit"><i
                            class="fas fa-save me-1"></i>Simpan</button>
                </div>
                <table class="table mt-3 table-bordered table-striped" id="tablealdi">
                    <thead>
                        <tr>
                            <th class="text-center dhead">Nama</th>
                            <th class="text-center dhead">Data Master</th>
                            <th class="text-center dhead">Pembukuan</th>
                            <th class="text-center dhead">Persediaan & Penyesuaian</th>
                            <th class="text-center dhead">Data Menu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            function getSubNavbar($idUser, $idSub)
                            {
                                return DB::table('permission_navbar')
                                    ->where([['id_user', $idUser], ['id_sub_navbar', $idSub]])
                                    ->first();
                            }
                        @endphp
                        @foreach ($users as $i => $d)
                            <tr>
                                <td>{{ ucwords($d->name) }}</td>
                                <td>
                                    @foreach ($data_master as $h)
                                        @php
                                            $cek = getSubNavbar($d->id, $h->id_sub_navbar);
                                        @endphp

                                        <input name="data_{{ $d->id }}[]" {{ $cek ? 'checked' : '' }}
                                            value="{{ $h->id_sub_navbar }}" class="form-check-input" type="checkbox"
                                            id="data_{{ $i }}_{{ $loop->index }}">
                                        <label class="form-check-label"
                                            for="data_{{ $i }}_{{ $loop->index }}">{{ strtoupper($h->judul) }}</label>
                                        <br>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($pembukuan as $h)
                                        @php
                                            $cek = getSubNavbar($d->id, $h->id_sub_navbar);
                                        @endphp

                                        <input name="pembukuan_{{ $d->id }}[]" {{ $cek ? 'checked' : '' }}
                                            value="{{ $h->id_sub_navbar }}" class="form-check-input" type="checkbox"
                                            id="pembukuan_{{ $i }}_{{ $loop->index }}">
                                        <label class="form-check-label"
                                            for="pembukuan_{{ $i }}_{{ $loop->index }}">{{ strtoupper($h->judul) }}</label>
                                        <br>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($persediaan as $h)
                                        @php
                                            $cek = getSubNavbar($d->id, $h->id_sub_navbar);
                                        @endphp

                                        <input name="persediaan_{{ $d->id }}[]" {{ $cek ? 'checked' : '' }}
                                            value="{{ $h->id_sub_navbar }}" class="form-check-input" type="checkbox"
                                            id="persediaan_{{ $i }}_{{ $loop->index }}">
                                        <label class="form-check-label"
                                            for="persediaan_{{ $i }}_{{ $loop->index }}">{{ strtoupper($h->judul) }}</label>
                                        <br>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($dataMenu as $h)
                                        @php
                                            $cek = getSubNavbar($d->id, $h->id_sub_navbar);
                                        @endphp

                                        <input name="dataMenu_{{ $d->id }}[]" {{ $cek ? 'checked' : '' }}
                                            value="{{ $h->id_sub_navbar }}" class="form-check-input" type="checkbox"
                                            id="dataMenu_{{ $i }}_{{ $loop->index }}">
                                        <label class="form-check-label"
                                            for="dataMenu_{{ $i }}_{{ $loop->index }}">{{ strtoupper($h->judul) }}</label>
                                        <br>
                                    @endforeach
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
        </form>
        </section>
        @section('scripts')
            <script>
                pencarian('pencarian', 'tablealdi')
            </script>
        @endsection
    </x-slot>
</x-theme.app>
