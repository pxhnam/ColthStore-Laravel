@php
    use App\Enums\ProductState;
@endphp
<form wire:submit.prevent='submit' autocomplete="off">
    <x-modal title='{{ $title }}' size='large' center=true static=true scroll=true btnClose='Close' btnDone='Save'
        submit=true>
        <div class="row">
            <div class="col-lg-6 col-md-12 mb-3">
                <div class="form-floating">
                    <input type="text"
                        @if ($errors->has('product.name')) class='form-control is-invalid' @else class='form-control' @endif
                        name="name" id="name" placeholder="" wire:model='product.name' wire:change='genSlug()'>
                    <label for="name">Name</label>
                </div>
                @error('product.name')
                    <span class="text-danger fst-italic">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-lg-6 col-md-12 mb-3">
                <div class="form-floating">
                    <input type="text"
                        @if ($errors->has('product.slug')) class='form-control is-invalid' @else class='form-control' @endif
                        name="slug" id="slug" placeholder="" wire:model='product.slug'>
                    <label for="slug">Slug</label>
                </div>
                @error('product.slug')
                    <span class="text-danger fst-italic">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-md-12 mb-3">
                <div class="form-floating">
                    <select id="categories" aria-label=""
                        @if ($errors->has('product.category')) class='form-select is-invalid' @else class='form-select' @endif
                        wire:model='product.category'>
                        <option value='' selected>---</option>
                        @foreach ($categories as $category)
                            <option value='{{ $category->id }}'>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <label for="categories">Categories</label>
                </div>
                @error('product.category')
                    <span class="text-danger fst-italic">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-md-12 mb-3">
                <div class="upload-image">
                    <label for="pics" class="form-label">
                        <i class='bx bx-image-add'></i>
                        <span>Upload</span>
                    </label>
                    <input class="form-control" type="file" id="pics" wire:model='product.pics' multiple
                        accept=".jpg,.jpeg,.png,.webp">
                    <p>
                        You have uploaded {{ count($product->pics) + count($product->preview) }}
                        {{ Str::plural('image', count($product->pics) + count($product->preview)) }}.
                    </p>
                </div>
                <p class="text-info" wire:loading wire:target='product.pics'>Loading...</p>
                @error('product.pics')
                    <span class="text-danger fst-italic">{{ $message }}</span>
                @enderror
                @error('product.pics.*')
                    <span class="text-danger fst-italic">{{ $message }}</span>
                @enderror
                <div class="mt-2 text-center">
                    @foreach ($product->pics ?? [] as $index => $pic)
                        @if (is_object($pic) && method_exists($pic, 'temporaryUrl'))
                            <div class="preview-image border rounded">
                                <img class="rounded" src="{{ $pic->temporaryUrl() }}" alt="preview">
                                <i class='bx bxs-x-circle' wire:click='removeTemporaryImage({{ $index }})'></i>
                            </div>
                        @endif
                    @endforeach
                </div>
                <div class="mt-2 text-center">
                    @foreach ($product->preview ?? [] as $image)
                        <div class="preview-image border rounded">
                            <img class="rounded" src="{{ asset('storage/' . $image->path) }}" alt="preview">
                            <i class='bx bxs-x-circle' wire:click='destroyImage("{{ $image->id }}")'></i>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-primary" wire:click='add()'>
                            <i class='bx bx-plus-medical'></i>
                        </button>
                    </div>
                    @for ($index = 0; $index < $count; $index++)
                        <div wire:key='{{ $index }}'
                            class='d-block d-lg-flex justify-content-between align-items-center border rounded p-3 my-2'>
                            <div class="col-md-12 col-lg-2 mb-3 mb-lg-0">
                                <div class="form-floating">
                                    <select class="form-select" id="sizes" aria-label="sizes"
                                        wire:model='product.details.{{ $index }}.size'>
                                        <option value=''>---</option>
                                        @foreach ($sizes as $size)
                                            <option value='{{ $size->id }}'>{{ $size->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="sizes">Sizes</label>
                                </div>
                                @error('product.details.' . $index . '.size')
                                    <span class="text-danger fst-italic">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12 col-lg-2 mb-3 mb-lg-0">
                                <div class="form-floating">
                                    <select class="form-select" id="colors" aria-label="colors"
                                        wire:model='product.details.{{ $index }}.color'>
                                        <option value=''>---</option>
                                        @foreach ($colors as $color)
                                            <option value='{{ $color->id }}'>{{ $color->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="colors">Colors</label>
                                </div>
                                @error('product.details.' . $index . '.color')
                                    <span class="text-danger fst-italic">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12 col-lg-2 mb-3 mb-lg-0">
                                <div class="form-floating">
                                    <input type="text" class='form-control' name="amount" id="amount"
                                        placeholder="" wire:model='product.details.{{ $index }}.amount'>
                                    <label for="amount">Amount</label>
                                </div>
                                @error('product.details.' . $index . '.amount')
                                    <span class="text-danger fst-italic">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12 col-lg-4 mb-3 mb-lg-0">
                                <div class="form-floating">
                                    <input type="text" class='form-control' name="cost" id="cost"
                                        placeholder="" wire:model='product.details.{{ $index }}.cost'>
                                    <label for="cost">Cost</label>
                                </div>
                                @error('product.details.' . $index . '.cost')
                                    <span class="text-danger fst-italic">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12 col-lg-1 text-center">
                                @if (isset($product->details[$index]['id']))
                                    <button type="button" class="btn btn-danger"
                                        wire:click='destroyVariant({{ $index }}, "{{ $product->details[$index]['id'] }}")'>
                                        <i class='bx bxs-trash-alt'></i>
                                    </button>
                                @else
                                    <button type="button" class="btn btn-danger"
                                        wire:click='removeIndex({{ $index }})'>
                                        <i class='bx bxs-trash-alt'></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <div class="form-floating">
                    <textarea placeholder="" id="description" style="height: 100px"
                        @if ($errors->has('product.desc')) class='form-control is-invalid' @else class='form-control' @endif
                        wire:model='product.desc'></textarea>
                    <label for="description">Description</label>
                </div>
                @error('product.desc')
                    <span class="text-danger fst-italic">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-md-12 mb-3">
                <span>State: </span>
                <div class="btn-group" role="group" aria-label="">
                    @foreach (ProductState::cases() as $state)
                        <input type="radio" class="btn-check" name="btnradio" id="{{ $state }}"
                            autocomplete="off" wire:model='product.state' value="{{ $state }}">
                        <label class="btn btn-outline-primary" for="{{ $state }}">{{ $state }}</label>
                    @endforeach
                </div>
                @error('product.state')
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
