@props([
    'route' => '',
    'halaman' => '',
])

{{-- modal setting --}}
@if (auth()->user()->posisi_id == 1)
    <x-theme.button modal="Y" idModal="akses" icon="fas fa-cog" addClass="float-end" teks="" />
@endif

<form action="{{ route('akses.save') }}" method="post">
    @csrf
    <input type="hidden" name="route" value="{{ $route }}">
    <x-theme.modal title="Akses Setting" idModal="akses" size="modal-lg">
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Halaman</th>
                    <th>Create</th>
                    <th>Read</th>
                    <th>Update</th>
                    <th>Delete</th>
                </tr>
            </thead>

            <tbody>
                @php
                    $user = DB::table('users')
                        ->where('nonaktif', 'T')
                        ->get();
                @endphp
                @foreach ($user as $no => $u)
                    @php
                        $akses = SettingHal::akses($halaman, $u->id);
                        
                        $create = SettingHal::btnSetHal($halaman, $u->id, 'create');
                        
                        $read = SettingHal::btnSetHal($halaman, $u->id, 'read');
                        
                        $update = SettingHal::btnSetHal($halaman, $u->id, 'update');
                        
                        $delete = SettingHal::btnSetHal($halaman, $u->id, 'delete');
                        
                    @endphp
                    <tr>
                        <td>{{ $no + 1 }}</td>
                        <td>{{ ucwords($u->name) }}</td>

                        <td>
                            <label><input type="checkbox"
                                    class="form-check-glow form-check-input form-check-primary akses_h akses_h{{ $u->id }}"
                                    id_user="{{ $u->id }}" id_user="{{ $u->id }}"
                                    {{ empty($akses->id_permission_page) ? '' : 'Checked' }} />
                                Akses</label>
                            <input type="hidden" class="open_check{{ $u->id }}" name="id_user[]"
                                {{ empty($akses->id_permission_page) ? 'disabled' : '' }} value="{{ $u->id }}">
                        </td>
                        <td>
                            <input type="hidden" name="id_permission_gudang" value="{{ $halaman }}">

                            @foreach ($create as $c)
                                <label><input type="checkbox" name="id_permission{{ $u->id }}[]"
                                        value="{{ $c->id_permission_button }}"
                                        {{ empty($c->id_permission_page) ? '' : 'Checked' }}
                                        class="form-check-glow form-check-input form-check-primary open_check{{ $u->id }}"
                                        {{ empty($akses->id_permission_page) ? 'disabled' : '' }} />
                                    {!! $c->nm_permission_button !!}</label>
                                <br>
                            @endforeach
                        </td>
                        <td>

                            @foreach ($read as $r)
                                <label><input type="checkbox" name="id_permission{{ $u->id }}[]"
                                        value="{{ $r->id_permission_button }}"
                                        {{ empty($r->id_permission_page) ? '' : 'Checked' }}
                                        class="form-check-glow form-check-input form-check-primary open_check{{ $u->id }}"
                                        {{ empty($akses->id_permission_page) ? 'disabled' : '' }} />
                                    {!! $r->nm_permission_button !!}</label> <br>
                            @endforeach
                        </td>
                        <td>
                            @foreach ($update as $up)
                                <label><input type="checkbox" name="id_permission{{ $u->id }}[]"
                                        value="{{ $up->id_permission_button }}"
                                        {{ empty($up->id_permission_page) ? '' : 'Checked' }}
                                        class="form-check-glow form-check-input form-check-primary open_check{{ $u->id }}"
                                        {{ empty($akses->id_permission_page) ? 'disabled' : '' }} />
                                    {!! $up->nm_permission_button !!}</label> <br>
                            @endforeach
                        </td>
                        <td>
                            @foreach ($delete as $d)
                                <label><input type="checkbox" name="id_permission{{ $u->id }}[]"
                                        value="{{ $d->id_permission_button }}"
                                        {{ empty($d->id_permission_page) ? '' : 'Checked' }}
                                        class="form-check-glow form-check-input form-check-primary open_check{{ $u->id }}"
                                        {{ empty($akses->id_permission_page) ? 'disabled' : '' }} />
                                    {!! $d->nm_permission_button !!}</label> <br>
                            @endforeach
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </x-theme.modal>
</form>
{{-- end modal setting --}}
