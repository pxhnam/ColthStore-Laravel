<?php

namespace App\Livewire\Admin;

use Exception;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use App\Livewire\Forms\SizeForm;
use App\Models\Size;
use Illuminate\Support\Facades\Log;

class UpdateSize extends Component
{

    use WithFileUploads;

    public $title = '';
    public SizeForm $size;

    #[On('create-size')]
    public function create()
    {
        $this->title = 'New Size';
        $this->dispatch('open-modal');
    }

    #[On('update-size')]
    public function update($id)
    {
        if ($id) {
            try {
                $this->size->id = $id;
                $size = Size::findOrFail($id);
                $this->size->name = $size->name;
            } catch (Exception $ex) {
                Log::error($ex->getMessage());
                $this->dispatch('error', 'An unexpected error occurred. Please try again.');
                return;
            }
        }
        $this->title = 'Update Size';
        $this->dispatch('open-modal');
    }

    public function submit()
    {
        $this->size->validate();
        try {
            if ($this->size->id) {
                $size = Size::findOrFail($this->size->id);
                $size->name = $this->size->name;
                $size->save();
                $this->dispatch('success', 'Updated Successfully!');
            } else {
                Size::create([
                    'name' => $this->size->name,
                ]);
                $this->size->reset();
                $this->dispatch('success', 'Created Successfully!');
            }
            $this->dispatch('load-sizes');
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
            $this->dispatch('error', 'An unexpected error occurred. Please try again.');
        }
    }

    public function close()
    {
        $this->size->resetValidation();
        $this->size->reset();
    }
    public function render()
    {
        return view('livewire.admin.update-size');
    }
}
