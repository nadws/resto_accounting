@props([
'title' => '',
'table' => 'Y',
'nav' => 'T',
'sizeCard' => '12',
])
<x-theme.head :title="$title" />

<x-theme.navbar />

<div class="content-wrapper container">
    <div class="page-content">
        @if ($table == 'T')
        {{ $slot }}
        @else
        <div class="row">
            <div class="col-lg-{{$sizeCard}}">
                <div class="card">
                    <div class="card-header">
                        @if ($nav == 'Y' )
                        <div class="row">
                            <div class="col-lg-6">
                                <ul class="nav nav-pills">
                                    @php
                                        $rotName = request()->route()->getName();
                                    @endphp
                                    <li class="nav-item">
                                        <a class="nav-link {{$rotName == 'produk.index' ? 'active' : ''}}" aria-current="page" href="{{ route('produk.index') }}">Produk</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{$rotName == 'stok_masuk.index' ? 'active' : ''}}" aria-current="page" href="{{ route('stok_masuk.index') }}">Stok Masuk</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{$rotName == 'opname.index' ? 'active' : ''}}" href="{{ route('opname.index') }}">Opname</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        @endif
                        <h3 class="float-start mt-1">{{ $title }}</h3>
                        
                        {{ $cardHeader }}
                    </div>
                    <div class="card-body">

                        {{ $cardBody }}
                    </div>
                    @if (!empty($cardFooter))
                    <div class="card-footer">
                        {{ $cardFooter }}
                    </div>
                    @else

                    @endif

                </div>
            </div>

        </div>
        @endif

    </div>
</div>

<x-theme.footer />