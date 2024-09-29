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
                <th scope="col" wire:click='sort("desc")'>
                    Description
                    @if ($sortBy == 'desc')
                        @if ($sortDirection === 'asc')
                            <i class='bx bxs-chevron-up'></i>
                        @else
                            <i class='bx bxs-chevron-down'></i>
                        @endif
                    @endif
                </th>
                <th scope="col" wire:click='sort("updated_at")'>
                    Updated At
                    @if ($sortBy == 'updated_at')
                        @if ($sortDirection === 'asc')
                            <i class='bx bxs-chevron-up'></i>
                        @else
                            <i class='bx bxs-chevron-down'></i>
                        @endif
                    @endif
                </th>
                <th scope="col" wire:click='sort("created_at")'>
                    Created At
                    @if ($sortBy == 'created_at')
                        @if ($sortDirection === 'asc')
                            <i class='bx bxs-chevron-up'></i>
                        @else
                            <i class='bx bxs-chevron-down'></i>
                        @endif
                    @endif
                </th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <div class="col-12">
            <tbody wire:loading.remove wire:target='categories'>
                @forelse ($categories ?? [] as $category)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->slug }}</td>
                        <td>
                            <img class="rounded" src="{{ asset('storage/' . $category->pic) }}"
                                alt="{{ $category->name }}" style="width: 130px"
                                onerror="this.onerror=null; this.src='{{ asset('storage/uploads/Image-Not-Found.jpg') }}';">
                        </td>
                        <td>{{ $category->desc }}</td>
                        <td>{{ $category->updated_at }}</td>
                        <td>{{ $category->created_at }}</td>
                        <td>
                            <button class="btn btn-success" wire:click='update("{{ $category->id }}")'>
                                <i class='bx bxs-edit-alt'></i>
                            </button>
                            <button class="btn btn-danger" wire:click='destroy("{{ $category->id }}")'>
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
    <p class="text-center" wire:loading wire:target='categories'>LOADING...</p>
    {{-- {{ $colors->links() }} --}}
</div>
