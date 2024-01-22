<ul class="nav nav-pills float-start">
    <li class="nav-item">
        <a class="nav-link {{ request()->route()->getName() == 'menu.index'? 'active': '' }}" aria-current="page"
            href="{{ route('menu.index') }}">Data Menu</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->route()->getName() == 'bahan.index'? 'active': '' }}" aria-current="page"
            href="{{ route('bahan.index') }}">Bahan & Barang</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->route()->getName() == 'bahan.stok'? 'active': '' }}" aria-current="page"
            href="{{ route('bahan.stok') }}">Stok Masuk</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->route()->getName() == 'bahan.opname'? 'active': '' }}"
            href="{{ route('bahan.opname') }}">Opname</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->route()->getName() == 'bahan.history'? 'active': '' }}"
            href="{{ route('bahan.history') }}">History</a>
    </li>
</ul>
