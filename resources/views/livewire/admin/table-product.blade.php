@php
    use App\Enums\ProductState;
    use App\Helpers\NumberFormat;
@endphp

<div class="row table-responsive shadow-sm p-3 bg-body-tertiary rounded">
    <div class="col-12 col-md-1">
        <div class="form-floating">
            <input type="number" class='form-control' name="page-size" id="page-size" placeholder=""
                wire:model.live='pageSize'>
            <label for="page-size">Page size</label>
        </div>
    </div>
    <div class="col-12 col-md-3">
        <div class="form-floating">
            <input type="text" class='form-control' name="search" id="search" placeholder=""
                wire:model.live='search' autocomplete="off">
            <label for="search">Search...</label>
        </div>
    </div>
    <div class="col-12 col-md-7"></div>
    <div class="col-12 col-md-1 d-flex justify-content-end align-items-center">
        <button class="btn btn-primary text-end" wire:click='create()'>
            CREATE
        </button>
    </div>
    <table class="table table-bordered table-hover text-center mt-2">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col" wire:click='sort("Name")'>
                    Name
                    @if ($sortBy == 'Name')
                        @if ($sortDirection === 'asc')
                            <i class='bx bxs-chevron-up'></i>
                        @else
                            <i class='bx bxs-chevron-down'></i>
                        @endif
                    @endif
                </th>
                <th scope="col" wire:click='sort("Slug")'>
                    Slug
                    @if ($sortBy == 'Slug')
                        @if ($sortDirection === 'asc')
                            <i class='bx bxs-chevron-up'></i>
                        @else
                            <i class='bx bxs-chevron-down'></i>
                        @endif
                    @endif
                </th>
                <th scope="col">Picture</th>
                <th scope="col">Size</th>
                <th scope="col">Color</th>
                <th scope="col">Category</th>
                {{-- <th scope="col" wire:click='sort("Category")'>
                    Category
                    @if ($sortBy == 'Category')
                        @if ($sortDirection === 'asc')
                            <i class='bx bxs-chevron-up'></i>
                        @else
                            <i class='bx bxs-chevron-down'></i>
                        @endif
                    @endif
                </th> --}}
                {{-- <th scope="col" wire:click='sort("Amount")'>
                    Amount
                    @if ($sortBy == 'Amount')
                        @if ($sortDirection === 'asc')
                            <i class='bx bxs-chevron-up'></i>
                        @else
                            <i class='bx bxs-chevron-down'></i>
                        @endif
                    @endif
                </th> --}}
                <th scope="col">Amount</th>
                <th scope="col">Cost</th>
                <th scope="col">State</th>
                {{-- <th scope="col" wire:click='sort("updated_at")'>
                    Updated At
                    @if ($sortBy == 'updated_at')
                        @if ($sortDirection === 'asc')
                            <i class='bx bxs-chevron-up'></i>
                        @else
                            <i class='bx bxs-chevron-down'></i>
                        @endif
                    @endif
                </th> --}}
                {{-- <th scope="col" wire:click='sort("created_at")'>
                    Created At
                    @if ($sortBy == 'created_at')
                        @if ($sortDirection === 'asc')
                            <i class='bx bxs-chevron-up'></i>
                        @else
                            <i class='bx bxs-chevron-down'></i>
                        @endif
                    @endif
                </th> --}}
                <th scope="col">Action</th>
            </tr>
        </thead>
        <div class="col-12">
            <tbody wire:loading.remove wire:target='products'>
                @forelse ($products ?? [] as $product)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $product->name }}</td>
                        <td class="text-primary">{{ $product->slug }}</td>
                        <td>
                            {{-- <img class="rounded" src="{{ asset('storage/' . $product->pic) }}"
                            alt="{{ $product->name }}" style="width: 130px"> --}}
                            @if ($product->images->isNotEmpty())
                                <img class="rounded" src="{{ asset('storage/' . $product->images->first()->path) }}"
                                    alt="{{ $product->name }}" style="width: 130px">
                            @else
                                <img src="{{ asset('storage/uploads/Image-Not-Found.jpg') }}" alt="Not Found Image"
                                    height="120px" class="rounded" />
                            @endif
                        </td>
                        <td> {{ $product->variants->pluck('size.name')->unique()->join(', ') }}</td>
                        <td>
                            @foreach ($product->variants->pluck('color.pic')->unique() as $image)
                                <img src="{{ asset('storage/' . $image) }}" alt="" class="rounded-circle"
                                    width="30px" height="30px">
                            @endforeach
                        </td>
                        <td>{{ $product->category->name }}</td>
                        <td>{{ $product->variants->sum('num') }}</td>
                        <td>{{ NumberFormat::VND($product->variants->avg('cost')) }}</td>
                        <td>
                            @if (ProductState::SHOW->value === $product->state)
                                <span class="badge text-bg-primary">{{ $product->state }}</span>
                            @elseif (ProductState::HIDDEN->value === $product->state)
                                <span class="badge text-bg-secondary">{{ $product->state }}</span>
                            @endif
                        </td>
                        {{-- <td>{{ $product->updated_at }}</td> --}}
                        {{-- <td>{{ $product->created_at }}</td> --}}
                        <td>
                            <button class="btn btn-success" wire:click='update("{{ $product->id }}")'>
                                <i class='bx bxs-edit-alt'></i>
                            </button>
                            <button class="btn btn-danger" wire:click='destroy("{{ $product->id }}")'>
                                <i class='bx bxs-trash-alt'></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan='12'>TABLE EMPTY</td>
                    </tr>
                @endforelse
            </tbody>
        </div>
    </table>
    <p class="text-center" wire:loading wire:target='products'>LOADING...</p>
    {{ $products->links() }}
</div>
