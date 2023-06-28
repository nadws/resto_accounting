@props([
    'route' => '',
])
<a data-bs-toggle="tooltip" data-bs-placement="top" title="Dashboard" href="{{ route($route) }}"
    class="btn btn-sm icon icon-left btn-primary me-2 float-end"><i class="fas fa-home"></i></a>
