<ul class="nav nav-pills float-start">
    {{-- <li class="nav-item">
        <a class="nav-link {{ $id_buku == '2' ? 'active' : '' }}" aria-current="page"
            href="{{ route('jurnal', ['id_buku' => '2', 'tgl1' => $tgl1, 'tgl2' => $tgl2, 'period' => 'costume']) }}">Biaya</a>
    </li> --}}
    <li class="nav-item">
        <a class="nav-link {{ $id_buku == '2' ? 'active' : '' }}" aria-current="page"
            href="{{ route('jurnal.index', ['id_buku' => '2', 'tgl1' => $tgl1, 'tgl2' => $tgl2, 'period' => 'costume']) }}">Biaya</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $id_buku == '3' ? 'active' : '' }}"
            href="{{ route('jurnal.index', ['id_buku' => '3', 'tgl1' => $tgl1, 'tgl2' => $tgl2, 'period' => 'costume']) }}">Penjualan</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $id_buku == '4' ? 'active' : '' }}"
            href="{{ route('jurnal.index', ['id_buku' => '4', 'tgl1' => $tgl1, 'tgl2' => $tgl2, 'period' => 'costume']) }}">Hutang</a>
    </li>

</ul>
