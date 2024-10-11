@php
    use App\Enums\InvoiceState;
    use App\Helpers\NumberFormat;
@endphp
<div class="wrap-modal1 js-modal1 p-t-60 p-b-20 {{ $open ? 'show-modal1' : '' }}">
    <div class="overlay-modal1 js-hide-modal1" wire:click='close()'></div>
    <div class="container">
        <div class="bg0 p-t-60 p-b-30 p-lr-15-lg how-pos3-parent">
            <button class="how-pos3 hov3 trans-04 js-hide-modal1" wire:click='close()'>
                <img src="{{ asset('assets/client/images/icons/icon-close.png') }}" alt="CLOSE" />
            </button>

            @if ($invoice)
                <div class="row p-lr-30">
                    <div class="col-md-12 p-b-20 txt-center">
                        <h3 class="text-uppercase">Invoice Details</h3>
                        <p>{{ $invoice->created_at }}</p>
                    </div>
                    <div class="col-md-12 p-b-30">
                        <p>Customer Name: {{ $invoice->user->name }}</p>
                        <p>Email: {{ $invoice->user->email }}</p>
                        <p>Status: {{ $invoice->state }}</p>
                        <p>Note: {{ $invoice->note }}</p>
                    </div>
                    <div class="col-md-12">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Color</th>
                                    <th scope="col">Size</th>
                                    <th scope="col">Image</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Total</th>
                                </tr>
                            </thead>
                            <div class="col-12">
                                <tbody>
                                    @forelse ($invoice->items ?? [] as $item)
                                        <tr>
                                            <th scope="row">{{ $loop->iteration }}</th>
                                            <td>{{ $item->variant->product->name }}</td>
                                            <td>
                                                <img src="{{ asset('storage/' . $item->variant->color->pic) }}"
                                                    alt="{{ $item->variant->color->name }}" height="30px"
                                                    width="30px" class="rounded-circle">
                                            </td>
                                            <td>{{ $item->variant->size->name }}</td>
                                            <td>
                                                <img src="{{ asset('storage/' . $item->variant->product->images->first()->path) }}"
                                                    alt="{{ $item->variant->product->name }}" height="120px"
                                                    class="rounded">
                                            </td>
                                            <td>{{ NumberFormat::VND($item->cost) }}</td>
                                            <td>{{ $item->num }}</td>
                                            <td>{{ NumberFormat::VND($item->cost * $item->num) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan='12'>TABLE EMPTY</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </div>
                        </table>
                    </div>
                    <div class="col-md-9 col-lg-10"></div>
                    <div class="col-md-3 col-lg-2">
                        <div class="d-flex justify-content-between">
                            <p class="mb-0">Subtotal: </p>
                            <p class="mb-0">{{ NumberFormat::VND($invoice->discount + $invoice->total) }}</p>
                        </div>
                        <div class="d-flex justify-content-between mb-0">
                            <p class="mb-0">Discount: </p>
                            <p class="mb-0">- {{ NumberFormat::VND($invoice->discount) }}</p>
                        </div>
                        <div class="d-flex justify-content-between mb-0">
                            <p class="mb-0">Total: </p>
                            <p class="mb-0">{{ NumberFormat::VND($invoice->total) }}</p>
                        </div>
                    </div>
                    @if ($invoice->state === InvoiceState::PENDING->value)
                        <div class="col-md-12 txt-end m-t-15">
                            <button class="btn btn-danger" wire:click='cancel()'>Cancel order</button>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

@script
    <script>
        $wire.on('notification', data => {
            swal('', data.message || '', data.type);
        });
    </script>
@endscript
