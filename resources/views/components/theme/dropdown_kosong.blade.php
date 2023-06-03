@props([
    'emptyKondisi' => '',
])

@if (empty(array_filter($emptyKondisi)))
    <li>
        <a class="dropdown-item text-black bg-warning">Akses tidak ada.</a>
    </li>
@endif
