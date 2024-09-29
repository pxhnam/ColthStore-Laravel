<?php

namespace App\Livewire\Admin;

use Exception;
use App\Models\Color;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use App\Livewire\Forms\ColorForm;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class UpdateColor extends Component
{
    use WithFileUploads;

    public $title = '';
    public ColorForm $color;

    #[On('create-color')]
    public function create()
    {
        $this->title = 'New Color';
        $this->dispatch('open-modal');
    }

    #[On('update-color')]
    public function update($id)
    {
        if ($id) {
            try {
                $this->color->id = $id;
                $color = Color::findOrFail($id);
                $this->color->name = $color->name;
                $this->color->url = $color->pic;
            } catch (Exception $ex) {
                Log::error($ex->getMessage());
                $this->dispatch('error', 'An unexpected error occurred. Please try again.');
                return;
            }
        }
        $this->title = 'Update Color';
        $this->dispatch('open-modal');
    }

    public function submit()
    {
        $this->color->validate();
        try {
            if ($this->color->pic instanceof UploadedFile) {
                $this->color->url = $this->color->pic->store('uploads', 'public');
            }
            if ($this->color->id) {
                $color = Color::findOrFail($this->color->id);
                $color->name = $this->color->name;
                if ($this->color->pic) {
                    $color->pic = $this->color->url;
                }
                $color->save();
                $this->color->reset('pic');
                $this->dispatch('success', 'Updated Successfully!');
            } else {
                Color::create([
                    'name' => $this->color->name,
                    'pic' => $this->color->url,
                ]);
                $this->color->reset();
                $this->dispatch('success', 'Created Successfully!');
            }
            $this->dispatch('load-colors');
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
            $this->dispatch('error', 'An unexpected error occurred. Please try again.');
        }
        // $this->dispatch('close-modal');
    }

    public function close()
    {
        $this->color->resetValidation();
        $this->color->reset();
    }

    public function render()
    {
        return view('livewire.admin.update-color');
    }
}
