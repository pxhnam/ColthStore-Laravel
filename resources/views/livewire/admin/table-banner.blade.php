@php
    use App\Enums\BannerState;
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
                <th scope="col" wire:click='sort("Title")'>
                    Title
                    @if ($sortBy == 'Title')
                        @if ($sortDirection === 'asc')
                            <i class='bx bxs-chevron-up'></i>
                        @else
                            <i class='bx bxs-chevron-down'></i>
                        @endif
                    @endif
                </th>
                <th scope="col" wire:click='sort("Sub")'>
                    Sub
                    @if ($sortBy == 'Sub')
                        @if ($sortDirection === 'asc')
                            <i class='bx bxs-chevron-up'></i>
                        @else
                            <i class='bx bxs-chevron-down'></i>
                        @endif
                    @endif
                </th>
                <th scope="col">Picture</th>
                <th scope="col">State</th>
            </tr>
        </thead>
        <div class="col-12">
            <tbody wire:loading.remove wire:target='banners'>
                @forelse ($banners ?? [] as $banner)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $banner->title }}</td>
                        <td>{{ $banner->sub }}</td>
                        <td>
                            <img class="rounded" src="{{ asset('storage/' . $banner->path) }}"
                                alt="{{ $banner->title }}" style="width: 380px"
                                onerror="this.onerror=null; this.src='{{ asset('storage/uploads/Image-Not-Found.jpg') }}';">
                        </td>
                        <td>
                            @if (BannerState::SHOW->value === $banner->state)
                                <span class="badge text-bg-primary">{{ $banner->state }}</span>
                            @elseif (BannerState::HIDDEN->value === $banner->state)
                                <span class="badge text-bg-secondary">{{ $banner->state }}</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-success" wire:click='update("{{ $banner->id }}")'>
                                <i class='bx bxs-edit-alt'></i>
                            </button>
                            <button class="btn btn-danger" @click="$dispatch('delete', { id: '{{ $banner->id }}' })">
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
    <p class="text-center" wire:loading wire:target='banners'>LOADING...</p>
    {{ $banners->links() }}
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
