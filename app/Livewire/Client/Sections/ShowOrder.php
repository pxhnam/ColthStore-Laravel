<?php

namespace App\Livewire\Client\Sections;

use App\Enums\InvoiceState;
use App\Models\Invoice;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\On;

class ShowOrder extends Component
{
    public $open = false;
    public $invoice;

    #[On('show-order')]
    public function show($id)
    {
        try {
            $invoice = Invoice::findOrFail($id);
            if ($invoice) {
                $this->invoice = $invoice;
                $this->open = true;
            }
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
        }
    }

    public function cancel()
    {
        try {
            if ($this->invoice->state === InvoiceState::PENDING->value) {
                $this->invoice->state = InvoiceState::CANCELED->value;
                $this->invoice->save();
                $this->notification('success', 'Order canceled successfully!');
                $this->dispatch('load-orders');
            }
        } catch (\Exception $ex) {
            $this->notification('error', 'Something went wrong!');
            Log::error($ex->getMessage());
        }
    }

    public function close()
    {
        $this->open = false;
        $this->invoice = null;
    }

    public function notification($type, $message)
    {
        $this->dispatch(
            'notification',
            type: $type,
            message: $message
        );
    }

    public function render()
    {
        return view('livewire.client.sections.show-order');
    }
}
