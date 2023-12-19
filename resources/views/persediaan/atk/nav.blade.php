<ul class="nav nav-pills float-start">
    <li class="nav-item">
        <a class="nav-link {{ request()->route()->getName() == 'atk.index'? 'active': '' }}" aria-current="page"
            href="{{ route('atk.index') }}">Data Persediaan</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->route()->getName() == 'atk.stok_masuk'? 'active': '' }}"
            href="{{ route('atk.stok_masuk') }}">Stok Masuk</a>
    </li>
</ul>
