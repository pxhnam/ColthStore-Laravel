<?php

namespace App\Livewire\Admin;

use App\Models\Invoice;
use Exception;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\On;

class ShowOrder extends Component
{
    public $title = '';
    public $invoice;
    public $items;

    #[On('show-order')]
    public function show($id)
    {
        try {
            $this->invoice = Invoice::findOrFail($id);
            $this->title = 'Invoice Detail';
            $this->dispatch('open-modal');
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
        }
    }

    public function close() {}

    public function render()
    {
        return view('livewire.admin.show-order');
    }
}
