<x-theme.app title="{{ $title }}" table="T">
    <x-slot name="slot">

        <div class="row">
            <div class="col-lg-8 mb-2">
                <h6>{{ $title }}</h6>
            </div>
            <div class="col-lg-12"></div>
            <div class="col-lg-3">
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

            </div>
            <div class="col-lg-3">
                <a href="{{ route('bahan.singkron') }}">

                    <div class="card" style="cursor:pointer;background-color: #3caba9">
                        <div class="card-body">
                            <h4 class=" text-white text-center"><img src="/img/sand-clock.png" width="128"
                                    {{-- <h4 class=" text-white text-center"><img src="/img/cloud-computing.png" width="128" --}} alt=""><br><br> Sikron Stok Bahan
                            </h4>
                        </div>
                    </div>
                </a>
            </div>
        </div>

    </x-slot>

    @section('scripts')
    @endsection
</x-theme.app>
