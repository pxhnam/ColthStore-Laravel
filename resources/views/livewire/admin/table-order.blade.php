@php
    use App\Helpers\NumberFormat;
    use App\Enums\InvoiceState;
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
                <th scope="col" wire:click='sort("name")'>
                    Name
                    @if (strtolower($sortBy) == 'name')
                        @if ($sortDirection === 'asc')
                            <i class='bx bxs-chevron-up'></i>
                        @else
                            <i class='bx bxs-chevron-down'></i>
                        @endif
                    @endif
                </th>
                <th scope="col" wire:click='sort("discount")'>
                    Discount
                    @if (strtolower($sortBy) == 'discount')
                        @if ($sortDirection === 'asc')
                            <i class='bx bxs-chevron-up'></i>
                        @else
                            <i class='bx bxs-chevron-down'></i>
                        @endif
                    @endif
                </th>
                <th scope="col" wire:click='sort("total")'>
                    Total
                    @if (strtolower($sortBy) == 'total')
                        @if ($sortDirection === 'asc')
                            <i class='bx bxs-chevron-up'></i>
                        @else
                            <i class='bx bxs-chevron-down'></i>
                        @endif
                    @endif
                </th>
                <th scope="col">State</th>
                <th scope="col" wire:click='sort("created_at")'>
                    Created At
                    @if (strtolower($sortBy) == 'created_at')
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
            <tbody wire:loading.remove wire:target='orders'>
                @forelse ($orders ?? [] as $order)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $order->user->name }}</td>
                        <td>{{ NumberFormat::VND($order->discount) }}</td>
                        <td>{{ NumberFormat::VND($order->total) }}</td>
                        <td>
                            @php
                                $badgeClass = match ($order->state) {
                                    InvoiceState::PENDING->value => 'text-bg-secondary',
                                    InvoiceState::CONFIRMED->value => 'text-bg-primary',
                                    InvoiceState::PAID->value => 'text-bg-success',
                                    InvoiceState::CANCELED->value => 'text-bg-danger',
                                    default => 'text-bg-light',
                                };
                            @endphp
                            <div class="d-flex justify-content-center align-items-center gap-3">
                                <a href="#" wire:click.prevent="previous('{{ $order->id }}')">
                                    <i class='bx bxs-chevron-left'></i>
                                </a>
                                <span class="badge {{ $badgeClass }}">{{ $order->state }}</span>
                                <a href="#" wire:click.prevent="next('{{ $order->id }}')">
                                    <i class='bx bxs-chevron-right'></i>
                                </a>
                            </div>
                        </td>
                        <td>{{ $order->created_at }}</td>
                        <td>
                            <button class="btn btn-success" wire:click="show('{{ $order->id }}')">
                                {{-- <i class='bx bx-show'></i> --}}
                                <i class='bx bx-info-circle'></i>
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
    <p class="text-center" wire:loading wire:target='orders'>LOADING...</p>
    {{ $orders->links() }}
</div>
