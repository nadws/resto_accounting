@props([
    'edit' => '',
    'hapus' => '',
    'detail' => '',
])

@if (empty($edit) && empty($hapus) && empty($detail))
    <li>
        <a class="dropdown-item text-black bg-warning">Akses tidak ada.</a>
    </li>
@endif
