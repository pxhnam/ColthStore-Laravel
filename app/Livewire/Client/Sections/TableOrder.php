<?php

namespace App\Livewire\Client\Sections;

use App\Models\Invoice;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;

class TableOrder extends Component
{

    public function show($id)
    {
        $this->dispatch('show-order', id: $id);
    }

    #[On('load-orders')]
    public function render()
    {
        $orders = [];
        if (Auth::check()) {
            $user = Auth::user();
            $orders = $user->invoices()->orderBy('created_at', 'desc')->get();
        }
        return view(
            'livewire.client.sections.table-order',
            [
                'orders' => $orders
            ]
        );
    }
}
