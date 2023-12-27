<ul class="nav nav-pills float-start">
    <li class="nav-item">
        <a class="nav-link {{ request()->route()->getName() == 'penjualan.index'? 'active': '' }}" aria-current="page"
            href="{{ route('penjualan.index') }}">Penjualan</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->route()->getName() == 'penjualan.history'? 'active': '' }}" aria-current="page"
            href="{{ route('penjualan.history') }}">History Bahan Keluar</a>
    </li>
</ul>
