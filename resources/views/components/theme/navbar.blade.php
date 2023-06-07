<header class="mb-5">
    @include('components.theme.header')
    <nav class="main-navbar ">
        <div class="container font-bold">
            <ul>
                <li class="menu-item">
                    <a href="dashboard"
                        class='menu-link {{ request()->route()->getName() == ' dashboard'
                            ? 'active_navbar_new'
                            : '' }}'>
                        <span>Dashboard</span>
                    </a>
                </li>
                @php
                    
                    $nav = [
                        [
                            'nama' => 'data master',
                            'route' => 'data_master',
                            'isi' => ['data_master', 'gudang', 'proyek', 'suplier.index', 'user.index'],
                        ],
                        [
                            'nama' => 'Buku Besar',
                            'route' => 'buku_besar',
                            'isi' => ['buku_besar', 'akun', 'jurnal', 'jurnal.add', 'summary_buku_besar.index', 'saldo_awal', 'summary_buku_besar.detail', 'profit', 'cashflow.index', 'penutup.index', 'controlflow', 'neraca'],
                        ],
                        [
                            'nama' => 'Penjualan BK',
                            'route' => 'penjualan',
                            'isi' => ['penjualan', 'jual.index'],
                        ],
                        [
                            'nama' => 'Pembelian BK',
                            'route' => 'pembelian',
                            'isi' => ['pembelian', 'po.index', 'pembelian_bk', 'pembelian_bk.add'],
                        ],
                        [
                            'nama' => 'Pembayaran BK',
                            'route' => 'pembayaran',
                            'isi' => ['pembayaran', 'pembayaranbk', 'pembayaranbk.add'],
                        ],
                        [
                            'nama' => 'Penjualan',
                            'route' => 'penjualan_umum.index',
                            'isi' => ['pembayaran', 'pembayaranbk', 'pembayaranbk.add'],
                        ],
                        [
                            'nama' => 'persediaan dan penyesuaian',
                            'route' => 'persediaan_barang',
                            'isi' => ['persediaan_barang', 'produk', 'opname.index', 'opname.add', 'stok_masuk.index', 'stok_masuk.add', 'bahan_baku.index', 'bahan_baku.stok_masuk', 'bahan_baku.stok_masuk_segment', 'bahan_baku.opname', 'peralatan.add', 'penyesuaian.atk', 'penyesuaian.atk_gudang', 'penyesuaian.aktiva', 'penyesuaian.index', 'asset', 'aktiva'],
                        ],
                    ];
                @endphp
                @foreach ($nav as $d)
                    <li class="menu-item">
                        <a href="{{ route($d['route']) }}"
                            class='menu-link 
                    {{ in_array(request()->route()->getName(),$d['isi'])? ' active_navbar_new': '' }}'>
                            <span>{{ ucwords($d['nama']) }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </nav>

</header>
