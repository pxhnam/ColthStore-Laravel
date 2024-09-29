<form wire:submit.prevent='submit' autocomplete="off">
    <x-modal title='{{ $title }}' center=true static=true size='large' btnClose='Close' btnDone='Save' submit=true>
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <input type="text"
                        @if ($errors->has('banner.title')) class='form-control is-invalid' @else class='form-control' @endif
                        name="title" id="title" placeholder="" wire:model='banner.title'>
                    <label for="title">Title</label>
                </div>
                @error('banner.title')
                    <span class="text-danger fst-italic">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <input type="text"
                        @if ($errors->has('banner.sub')) class='form-control is-invalid' @else class='form-control' @endif
                        name="sub" id="sub" placeholder="" wire:model='banner.sub'>
                    <label for="sub">Sub</label>
                </div>
                @error('banner.sub')
                    <span class="text-danger fst-italic">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-md-12 mb-3">
                <div class="w-100">
                    <label for="pic" class="form-label">Picture: </label>
                    <input class="form-control" type="file" id="pic" wire:model='banner.pic'
                        accept=".jpg,.jpeg,.png,.webp">
                </div>
                <p class="text-info" wire:loading wire:target='banner.pic'>Loading...</p>
                @error('banner.pic')
                    <span class="text-danger fst-italic">{{ $message }}</span>
                @enderror
                @if ($banner->pic || $banner->path)
                    <div class="mt-2 text-center">
                        @if (is_object($banner->pic) && method_exists($banner->pic, 'temporaryUrl'))
                            <img class="rounded" src="{{ $banner->pic->temporaryUrl() }}" alt="preview" width="720px">
                        @else
                            <img class="rounded" src="{{ asset('storage/' . $banner->path) }}" alt="preview"
                                width="720px">
                        @endif
                    </div>
                @endif
            </div>
            <div class="col-md-12 mb-3">
                <span>State: </span>
                <div class="btn-group" role="group" aria-label="">
                    @foreach ($states as $state)
                        <input type="radio" class="btn-check" name="btnradio" id="{{ $state }}"
                            autocomplete="off" wire:model='banner.state' value="{{ $state }}">
                        <label class="btn btn-outline-primary" for="{{ $state }}">{{ $state }}</label>
                    @endforeach
                </div>
                @error('banner.state')
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
