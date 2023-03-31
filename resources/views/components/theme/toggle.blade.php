@props([
'name' => '',
'checked' => false,
])

@php
    $ubah = str_replace(" ", "_", $name);
    $judul = $name;
@endphp

<div class="col-lg-1 me-2">
    <div class="form-check form-switch form-switch2">
        <input class="form-check-input form-check-input2" {{$checked ? 'checked' : ''}} name="{{$ubah}}" value="Y" type="checkbox"
        id="{{$ubah}}" />
    </div>
</div>
<div class="col-lg-2 mt-2">
    <label for="{{$ubah}}">{{ ucwords($name) }}</label> <br>

</div>