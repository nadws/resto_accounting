<ul class="nav nav-pills float-start">
    @php
        $kategori = [
            2 => 'biaya',
            3 => 'penjualan',
            4 => 'hutang',
            5 => 'pengeluaran aktiva gantung',
            6 => 'pembalikan aktiva gantung',
        ];
    @endphp
    @foreach ($kategori as $i => $d)
        <li class="nav-item">
            <a class="nav-link {{ $id_buku == $i ? 'active' : '' }}" aria-current="page"
                href="{{ route('jurnal.index', ['id_buku' => $i, 'tgl1' => $tgl1, 'tgl2' => $tgl2, 'period' => 'costume']) }}">{{ ucwords($d) }}</a>
        </li>
    @endforeach

</ul>
