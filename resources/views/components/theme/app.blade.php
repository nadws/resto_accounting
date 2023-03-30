@props([
'title' => '',
'table' => 'Y',
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
                        <h3 class="float-start">{{ $title }}</h3>
                        {{ $cardHeader }}
                    </div>
                    <div class="card-body">

                        {{ $cardBody }}
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>

<x-theme.footer />