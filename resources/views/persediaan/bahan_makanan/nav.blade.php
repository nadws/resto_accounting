<ul class="nav nav-pills float-start">
    <li class="nav-item">
        <a class="nav-link {{ request()->route()->getName() == 'bahan.index'? 'active': '' }}" aria-current="page"
            href="{{ route('bahan.index') }}">Bahan & Barang</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->route()->getName() == 'bahan.opname'? 'active': '' }}"
            href="{{ route('bahan.opname') }}">Opname</a>
    </li>
</ul>
