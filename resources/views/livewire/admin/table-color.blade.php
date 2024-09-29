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
                <th scope="col">Picture</th>
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
            <tbody wire:loading.remove wire:target='colors'>
                @forelse ($colors ?? [] as $color)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $color->name }}</td>
                        <td>
                            <img src="{{ asset('storage/' . $color->pic) }}" alt="{{ $color->name }}"
                                class="rounded-circle" width="50px" height="50px"
                                onerror="this.onerror=null; this.src='{{ asset('storage/uploads/Image-Not-Found.jpg') }}';">
                        </td>
                        <td>{{ $color->updated_at }}</td>
                        <td>{{ $color->created_at }}</td>
                        <td>
                            <button class="btn btn-success" wire:click='update("{{ $color->id }}")'>
                                <i class='bx bxs-edit-alt'></i>
                            </button>
                            <button class="btn btn-danger" @click="$dispatch('delete', { id: '{{ $color->id }}' })">
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
    <p class="text-center" wire:loading wire:target='colors'>LOADING...</p>
    {{ $colors->links() }}
</div>

@script
    <script>
        $wire.on('delete', (event) => {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.dispatch('destroy', {
                        id: event.id
                    });
                    Swal.fire({
                        icon: "success",
                        title: "Deleted!",
                        text: "Your file has been deleted.",
                        showConfirmButton: false,
                        timer: 1000
                    });
                }
            });
        });
    </script>
@endscript
