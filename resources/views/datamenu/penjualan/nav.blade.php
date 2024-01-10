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
<x-theme.button modal="Y" idModal="view" icon="fa-calendar-week" addClass="float-end" teks="Filter" />
<form action="" method="GET">
    <x-theme.modal idModal="view" title="View">
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="">dari</label>
                    <input type="date" name="tgl1" value="{{ date('Y-m-d', strtotime('-1 days')) }}"
                        class="form-control">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="">Sampai</label>
                    <input type="date" name="tgl2" value="{{ date('Y-m-d', strtotime('-1 days')) }}"
                        class="form-control">
                </div>
            </div>
        </div>
    </x-theme.modal>
</form>
<a class="btn btn-sm btn-primary float-end me-2 " href="{{ route('penjualan.export',['tgl1' => $tgl1, 'tgl2' => $tgl2]) }}"><i class="fas fa-file-excel"></i> Export</a>
