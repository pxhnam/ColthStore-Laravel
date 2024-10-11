@php
    use App\Helpers\NumberFormat;
@endphp
<div class="container">
    <div class="table-responsive flex-w justify-content-center m-t-30 m-b-75" style="min-height: 680px">
        <table class="table-custom">
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Name</th>
                    <th>Discount</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Created at</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td><a href='javascript:void(0)'>{{ $order->id }}</a></td>
                        <td>{{ $order->user->name }}</td>
                        <td>- {{ NumberFormat::VND($order->discount) }}</td>
                        <td>{{ NumberFormat::VND($order->total) }}</td>
                        <td>
                            <p class="status status-{{ strtolower($order->state) }}">{{ $order->state }}</p>
                        </td>
                        <td>{{ $order->created_at }}</td>
                        <td>
                            <button class="btn btn-info" wire:click="show('{{ $order->id }}')">
                                <i class="zmdi zmdi-info-outline"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
