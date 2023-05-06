<header class="mb-5">
    @include('components.theme.header')
    <nav class="main-navbar">
        <div class="container font-bold">
            <ul>
                <li class="menu-item">
                    <a href="dashboard"
                        class='menu-link {{ request()->route()->getName() == ' dashboard'
                            ? 'active'
                            : '' }}'>
                        <span>Dashboard</span>
                    </a>
                </li>
                @php
                    
                    $nav = [
                        [
                            'nama' => 'data master',
                            'route' => 'data_master',
                            'isi' => ['data_master', 'gudang', 'proyek'],
                        ],
                        [
                            'nama' => 'Buku Besar',
                            'route' => 'buku_besar',
                            'isi' => ['buku_besar', 'akun', 'jurnal', 'jurnal.add', 'summary_buku_besar.index', 'saldo_awal', 'summary_buku_besar.detail', 'profit', 'cashflow.index', 'penutup.index', 'jurnal_penyesuaian', 'jurnal_aktiva', 'jurnal_penyesuaian'],
                        ],
                        [
                            'nama' => 'Penjualan',
                            'route' => 'penjualan',
                            'isi' => ['penjualan', 'faktur_penjualan'],
                        ],
                        [
                            'nama' => 'Pembelian',
                            'route' => 'pembelian',
                            'isi' => ['pembelian', 'po.index'],
                        ],
                        [
                            'nama' => 'persediaan barang',
                            'route' => 'persediaan_barang',
                            'isi' => ['persediaan_barang', 'produk', 'opname.index', 'opname.add', 'stok_masuk.index', 'stok_masuk.add', 'bahan_baku.index', 'bahan_baku.stok_masuk', 'bahan_baku.stok_masuk_segment'],
                        ],
                        [
                            'nama' => 'Asset',
                            'route' => 'asset',
                            'isi' => ['asset', 'aktiva'],
                        ],
                    ];
                @endphp
                @foreach ($nav as $d)
                    <li class="menu-item">
                        <a href="{{ route($d['route']) }}"
                            class='menu-link 
                    {{ in_array(request()->route()->getName(),$d['isi'])? ' active': '' }}'>
                            <span>{{ ucwords($d['nama']) }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </nav>

</header>
