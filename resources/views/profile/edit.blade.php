<x-theme.app title="{{$title}}" table="Y" sizeCard="8">
    <x-slot name="cardHeader">
        <div class="row justify-content-end">

        </div>
    </x-slot>
    <x-slot name="cardBody">
        <div class="col-lg-12">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="col-lg-12 mt-2">
            <hr style="border: 1px solid black">
            @include('profile.partials.update-password-form')
        </div>
    </x-slot>



</x-theme.app>