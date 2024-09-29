<form wire:submit.prevent='submit' autocomplete="off">
    <x-modal title='{{ $title }}' center=true static=true btnClose='Close' btnDone='Save' submit=true>
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="form-floating">
                    <input type="text"
                        @if ($errors->has('category.name')) class='form-control is-invalid' @else class='form-control' @endif
                        name="name" id="name" placeholder="" wire:model='category.name' wire:change='genSlug()'>
                    <label for="name">Name</label>
                </div>
                @error('category.name')
                    <span class="text-danger fst-italic">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-md-12 mb-3">
                <div class="form-floating">
                    <input type="text"
                        @if ($errors->has('category.slug')) class='form-control is-invalid' @else class='form-control' @endif
                        name="slug" id="slug" placeholder="" wire:model='category.slug'>
                    <label for="slug">Slug</label>
                </div>
                @error('category.slug')
                    <span class="text-danger fst-italic">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-md-12 mb-3">
                <div class="w-100">
                    <label for="pic" class="form-label">Picture: </label>
                    <input class="form-control" type="file" id="pic" wire:model='category.pic'
                        accept=".jpg,.jpeg,.png,.webp">
                </div>
                <p class="text-info" wire:loading wire:target='category.pic'>Loading...</p>
                @error('category.pic')
                    <span class="text-danger fst-italic">{{ $message }}</span>
                @enderror
                @if ($category->pic || $category->url)
                    <div class="mt-2 text-center">
                        @if (is_object($category->pic) && method_exists($category->pic, 'temporaryUrl'))
                            <img class="rounded" src="{{ $category->pic->temporaryUrl() }}" alt="preview"
                                style="width: 210px">
                        @else
                            <img class="rounded" src="{{ asset('storage/' . $category->url) }}" alt="preview"
                                style="width: 210px">
                        @endif
                    </div>
                @endif
            </div>
            <div class="col-md-12 mb-3">
                <div class="form-floating">
                    <input type="text"
                        @if ($errors->has('category.desc')) class='form-control is-invalid' @else class='form-control' @endif
                        name="desc" id="desc" placeholder="" wire:model='category.desc'>
                    <label for="desc">Description</label>
                </div>
                @error('category.desc')
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
