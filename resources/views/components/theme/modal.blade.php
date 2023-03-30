@props([
'idModal' => '',
'size' => '',
'title' => '',
'btnSave' => 'Y',
])

<div {{ $attributes->merge(['id' => $idModal]) }} class="modal fade" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog {{ $size }}" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" {{ $attributes->merge(['id' => $idModal]) }}>
                    {{ $title }}
                </h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Close</span>
                </button>
                @if ($btnSave == 'Y')
                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Save</span>
                </button>
                @endif

            </div>

        </div>
    </div>
</div>