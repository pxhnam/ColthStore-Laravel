<form wire:submit.prevent='submit' autocomplete="off">
    <x-modal title='{{ $title }}' center=true static=true btnClose='Close' btnDone='Save' submit=true>
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="form-floating">
                    <input type="text"
                        @if ($errors->has('size.name')) class='form-control is-invalid' @else class='form-control' @endif
                        name="name" id="name" placeholder="" wire:model='size.name'>
                    <label for="name">Name</label>
                </div>
                @error('size.name')
                    <span class="text-danger fst-italic">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </x-modal>
</form>
@script
    <script>
        $wire.on('open-modal', () => {
            $('.modal').modal('show');
        });
        $wire.on('close-modal', () => {
            $('.modal').modal('hide');
        });
        $wire.on('success', msg => {
            Swal.fire({
                icon: 'success',
                title: msg,
                showConfirmButton: false,
                timer: 1000
            });
        });
        $wire.on('error', msg => {
            Swal.fire({
                icon: 'error',
                title: msg,
                showConfirmButton: false,
                timer: 1000
            });
        });
    </script>
@endscript
