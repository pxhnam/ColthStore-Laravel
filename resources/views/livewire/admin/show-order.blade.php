@php
    use App\Helpers\NumberFormat;
@endphp
<x-modal title='{{ $title }}' size='extra-large' center=true static=true btnClose='Close'>
    @if ($invoice)
        <div class="row">
            <div class="col-md-12 mb-3">
                <h3 class="text-uppercase text-center">{{ $title }}</h3>
                <p class="text-center">{{ $invoice->created_at }}</p>
                <p class="mb-0">Customer Name: {{ $invoice->user->name }}</p>
                <p class="mb-0">Email: {{ $invoice->user->email }}</p>
                <p class="mb-0">State: {{ $invoice->state }}</p>
                <p class="mb-0">Note: {{ $invoice->note }}</p>
            </div>
            <div class="col-md-12">
                <table class="table table-bordered table-hover text-center mt-2">
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
                        <tbody wire:loading.remove wire:target='invoice.items'>
                            @forelse ($invoice->items ?? [] as $item)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $item->variant->product->name }}</td>
                                    <td>
                                        <img src="{{ asset('storage/' . $item->variant->color->pic) }}"
                                            alt="{{ $item->variant->color->name }}" height="30px" width="30px"
                                            class="rounded-circle">
                                    </td>
                                    <td>{{ $item->variant->size->name }}</td>
                                    <td>
                                        <img src="{{ asset('storage/' . $item->variant->product->images->first()->path) }}"
                                            alt="{{ $item->variant->product->name }}" height="120px" class="rounded">
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
                <p class="text-center" wire:loading wire:target='invoice.items'>LOADING...</p>
            </div>
            <div class="col-md-9"></div>
            <div class="col-md-3">
                <div class="d-flex justify-content-between">
                    <p class="mb-0">Subtotal: </p>
                    <p class="mb-0">{{ NumberFormat::VND($invoice->discount + $invoice->total) }}</p>
                </div>
                <div class="d-flex justify-content-between mb-0">
                    <p class="mb-0">Discount: </p>
                    <p class="mb-0">-{{ NumberFormat::VND($invoice->discount) }}</p>
                </div>
                <div class="d-flex justify-content-between mb-0">
                    <p class="mb-0">Total: </p>
                    <p class="mb-0">{{ NumberFormat::VND($invoice->total) }}</p>
                </div>
            </div>
        </div>
    @endif
</x-modal>
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
