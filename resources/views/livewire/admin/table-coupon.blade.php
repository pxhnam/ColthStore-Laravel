@php
    use App\Enums\CouponType;
    use App\Helpers\NumberFormat;
    use Carbon\Carbon;
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
                <th scope="col" wire:click='sort("Code")'>
                    Code
                    @if ($sortBy == 'Code')
                        @if ($sortDirection === 'asc')
                            <i class='bx bxs-chevron-up'></i>
                        @else
                            <i class='bx bxs-chevron-down'></i>
                        @endif
                    @endif
                </th>
                <th scope="col" wire:click='sort("Value")'>
                    Value
                    @if ($sortBy == 'Value')
                        @if ($sortDirection === 'asc')
                            <i class='bx bxs-chevron-up'></i>
                        @else
                            <i class='bx bxs-chevron-down'></i>
                        @endif
                    @endif
                </th>
                <th scope="col">
                    Min - Max
                </th>
                <th scope="col">Usage</th>
                <th scope="col" wire:click='sort("start_date")'>
                    Start
                    @if ($sortBy == 'start_date')
                        @if ($sortDirection === 'asc')
                            <i class='bx bxs-chevron-up'></i>
                        @else
                            <i class='bx bxs-chevron-down'></i>
                        @endif
                    @endif
                </th>
                <th scope="col" wire:click='sort("expiry_date")'>
                    End
                    @if ($sortBy == 'expiry_date')
                        @if ($sortDirection === 'asc')
                            <i class='bx bxs-chevron-up'></i>
                        @else
                            <i class='bx bxs-chevron-down'></i>
                        @endif
                    @endif
                </th>
                <th scope="col"></th>
            </tr>
        </thead>
        <div class="col-12">
            <tbody wire:loading.remove wire:target='coupons'>
                @forelse ($coupons ?? [] as $coupon)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $coupon->code }}</td>

                        <td>
                            @switch($coupon->type)
                                @case(CouponType::FIXED->value)
                                    {{ NumberFormat::VND($coupon->value) }}
                                @break

                                @case(CouponType::PERCENT->value)
                                    {{ $coupon->value }}%
                                @break

                                @default
                            @endswitch
                        </td>
                        <td>{{ NumberFormat::VND($coupon->min) . ' - ' . NumberFormat::VND($coupon->max) }}</td>
                        <td>{{ $coupon->count . '\\' . $coupon->limit }}</td>
                        <td>{{ Carbon::parse($coupon->start_date)->format('H:i d-m-Y') }}</td>
                        <td>{{ Carbon::parse($coupon->expiry_date)->format('H:i d-m-Y') }}</td>
                        <td>
                            <button class="btn btn-success" wire:click='update("{{ $coupon->id }}")'>
                                <i class='bx bxs-edit-alt'></i>
                            </button>
                            <button class="btn btn-danger" @click="$dispatch('delete', { id: '{{ $coupon->id }}' })">
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
        <p class="text-center" wire:loading wire:target='coupons'>LOADING...</p>
        {{ $coupons->links() }}
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
