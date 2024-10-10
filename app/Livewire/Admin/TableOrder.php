<?php

namespace App\Livewire\Admin;

use Exception;
use App\Models\Invoice;
use App\Enums\InvoiceState;
use Illuminate\Support\Facades\Log;
use App\Livewire\Components\AdminComponent;

class TableOrder extends AdminComponent
{
    protected $validSortByColumns = ['name', 'discount', 'total', 'created_at', 'updated_at'];

    public function show($id)
    {
        $this->dispatch('show-order', id: $id);
    }

    public function next($id)
    {
        try {
            $order = Invoice::findOrFail($id);
            $currentState = $order->state;

            $states = InvoiceState::getValues();
            $currentIndex = array_search($currentState, $states);

            if ($currentIndex !== false && $currentIndex < count($states) - 1) {
                $nextState = $states[$currentIndex + 1];
                $order->state = $nextState;
                $order->save();
            }
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
        }
    }

    public function previous($id)
    {
        try {
            $order = Invoice::findOrFail($id);
            $currentState = $order->state;

            $states = InvoiceState::getValues();
            $currentIndex = array_search($currentState, $states);

            if ($currentIndex !== false && $currentIndex > 0) {
                $previousState = $states[$currentIndex - 1];
                $order->state = $previousState;
                $order->save();
            }
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
        }
    }

    public function render()
    {
        $orders = Invoice::query()
            ->with('user')
            ->when($this->search, function ($query) {
                $query->whereHas('user', function ($query) {
                    $query->where('name', 'like', "%{$this->search}%");
                });
            })
            ->when($this->sortBy, function ($query) {
                if ($this->sortBy === 'name') {
                    $query->join('users', 'invoices.user_id', '=', 'users.id')
                        ->select('invoices.*')
                        ->orderBy('users.name', $this->sortDirection);
                } else {
                    $query->orderBy($this->sortBy, $this->sortDirection);
                }
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->pageSize);

        return view(
            'livewire.admin.table-order',
            [
                'orders' => $orders
            ]
        );
    }
}
