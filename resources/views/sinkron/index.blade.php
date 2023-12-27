<x-theme.app title="{{ $title }}" table="T">
    <x-slot name="slot">

        <div class="row">
            <div class="col-lg-8 mb-2">
                {{-- <h6>{{ $title }}</h6> --}}
            </div>
            <div class="col-lg-12"></div>
            {{-- <div class="col-lg-3">
                @if ($menu->ttl == 0)
                    <a href="{{ route('importapi.invoice', ['id_distribusi' => '1', 'tgl1' => $tgl, 'tgl2' => $tgl]) }}">
                        <div class="card " style="cursor:pointer;background-color: #3caba9">
                            <div class="card-body">
                                <h4 class=" text-white text-center"><img src="/img/cloud-computing.png" width="128"
                                        alt=""><br><br> Sikron Pemasukan
                                </h4>
                            </div>
                        </div>
                    </a>
                @else
                    <div class="card " style="background-color: #3caba9">
                        <div class="card-body">
                            <h4 class=" text-white text-center"><img src="/img/sand-clock.png" width="128"
                                    alt=""><br><br> Sikron Pemasukan
                            </h4>
                        </div>
                    </div>
                    </a>
                @endif

            </div> --}}
            <div class="col-lg-3">
                <a href="{{ route('bahan.singkron') }}" id="klikSingkron">
                    @php
                        $bgWarna = $countBelumExport == 0 ? '#61ab3c' : '#3caba9';

                    @endphp
                    <h6>Pendapatan Resto {{ app('nm_lokasi') }}</h6>
                    <div class="card" style="cursor:pointer;background-color: {{ $bgWarna }}">
                        <div class="card-body">
                            <button class="loading d-none btn btn-primary btn-block" type="button" disabled="">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Loading...
                            </button>
                            <div class="content">
                                <h5 class=" text-white text-center">
                                    Data belum singkron : <br>{{ $countBelumExport }} Hari
                                    <ul>
                                        @foreach ($tglTidakAda as $tanggal => $i)
                                            <li>{{ tanggal($tanggal) }}</li>
                                        @endforeach
                                    </ul>
                                </h5>
                                @if ($countBelumExport == 0)
                                    <h6 class="text-center text-white">Semua data sudah diambil <i
                                            class="fas fa-check"></i>
                                    </h6>
                                @endif
                            </div>
                        </div>
                    </div>
                </a>

            </div>
        </div>

    </x-slot>

    @section('scripts')
        <script>
            $("#klikSingkron").click(function(e) {
                $('.loading').removeClass('d-none');
                $('.content').addClass('d-none');
                $(this).css('pointer-events', 'none')
            });
        </script>
    @endsection
</x-theme.app>
