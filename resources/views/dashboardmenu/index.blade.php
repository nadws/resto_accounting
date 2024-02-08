<x-theme.app title="{{ $title }}" table="T">
    <x-slot name="slot">
        <div class="row">
            @php
                $id_user = auth()->user()->id;
                $navbar = DB::table('navbar')->get();

            @endphp
            @foreach ($navbar as $i => $n)
                @php
                    $data = DB::table('sub_navbar as a')
                        ->join('permission_navbar as b', 'a.id_sub_navbar', 'b.id_sub_navbar')
                        ->where([['a.navbar', $i + 1], ['b.id_user', $id_user]])
                        ->get();
                @endphp
                @if($data->isNotEmpty())
                <h6>{{ strtoupper($n->nama) }}</h6>
                <hr>
                @foreach ($data as $d)
                    <div class="col-lg-3">
                        <a href="{{ route($d->route) }}">
                            <div style="cursor:pointer;background-color: #8ca3f3"
                                class="card border card-hover text-white">
                                <div class="card-front">
                                    <div class="card-body">
                                        <h4 class="card-title text-white text-center"><img
                                                src="{{ asset('img/' . $d->img) }}" width="128"
                                                alt=""><br><br>
                                            {{ ucwords($d->judul) }}
                                        </h4>
                                    </div>
                                </div>
                                <div class="card-back">
                                    <div class="card-body">
                                        <h5 class="card-text text-white">{{ ucwords($d->judul) }}</h5>
                                        <p class="card-text">{{ ucwords($d->deskripsi) }}</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
                @endif
            @endforeach

    </x-slot>

</x-theme.app>
