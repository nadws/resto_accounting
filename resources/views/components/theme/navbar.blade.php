@auth
    <header class="mb-5">
        @include('components.theme.header2')
        <nav class="main-navbar ">
            <div class="container font-bold">
                <ul>
                    <li class="menu-item ">
                        <a href="{{ route('dashboard.index') }}"
                            class='menu-link {{ request()->route()->getName() == 'dashboard.index'
                                ? 'active_navbar_new'
                                : '' }}'>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    @php
                        $id_user = auth()->user()->id;

                        $navbar = DB::table('navbar')
                            ->orderBy('urutan', 'ASC')
                            ->get();
                    @endphp
                    @foreach ($navbar as $i => $d)
                        @php
                            $string = $d->isi;
                            $string = str_replace(['[', ']', "'"], '', $string);
                            $array = explode(', ', $string);
                            $data = DB::table('sub_navbar as a')
                                ->join('permission_navbar as b', 'a.id_sub_navbar', 'b.id_sub_navbar')
                                ->where([['a.navbar', $i + 1], ['b.id_user', $id_user]])
                                ->get();
                        @endphp
                        @if ($data->isNotEmpty())
                            <li class="menu-item">
                                <a href="{{ route($d->route) }}"
                                    class='menu-link 
                    {{ in_array(request()->route()->getName(),$array)? 'active_navbar_new': '' }}'>
                                    <span> {{ ucwords($d->nama) }}</span>
                                </a>
                            </li>
                        @endif
                    @endforeach

                </ul>
            </div>
        </nav>

    </header>
@endauth
