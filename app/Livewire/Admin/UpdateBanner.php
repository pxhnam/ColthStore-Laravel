<?php

namespace App\Livewire\Admin;

use App\Enums\BannerState;
use App\Livewire\Forms\BannerForm;
use App\Models\Banner;
use Exception;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;

class UpdateBanner extends Component
{
    use WithFileUploads;

    public $title = '';
    public BannerForm $banner;

    #[On('create-banner')]
    public function create()
    {
        $this->title = 'New Banner';
        $this->dispatch('open-modal');
    }

    #[On('update-banner')]
    public function update($id)
    {
        if ($id) {
            try {
                $this->banner->id = $id;
                $banner = Banner::findOrFail($id);
                $this->banner->title = $banner->title;
                $this->banner->sub = $banner->sub;
                $this->banner->path = $banner->path;
                $this->banner->state = $banner->state;
            } catch (Exception $ex) {
                Log::error($ex->getMessage());
                $this->dispatch('error', 'An unexpected error occurred. Please try again.');
                return;
            }
        }
        $this->title = 'Update Banner';
        $this->dispatch('open-modal');
    }

    public function submit()
    {
        $this->banner->validate();
        try {
            if ($this->banner->pic instanceof UploadedFile) {
                $this->banner->path = $this->banner->pic->store('uploads', 'public');
            }
            if ($this->banner->id) {
                $banner = Banner::findOrFail($this->banner->id);
                $banner->title = $this->banner->title;
                $banner->sub = $this->banner->sub;
                $banner->state = $this->banner->state;
                if ($this->banner->pic) {
                    $banner->path = $this->banner->path;
                }
                $banner->save();
                $this->banner->reset('pic');
                $this->dispatch('success', 'Updated Successfully!');
            } else {
                Banner::create([
                    'title' => $this->banner->title,
                    'sub' => $this->banner->sub,
                    'path' => $this->banner->path,
                    'state' => $this->banner->state
                ]);
                $this->banner->reset();
                $this->dispatch('success', 'Created Successfully!');
            }
            $this->dispatch('load-banners');
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
            $this->dispatch('error', 'An unexpected error occurred. Please try again.');
        }
    }

    public function close()
    {
        $this->banner->resetValidation();
        $this->banner->reset();
    }

    public function render()
    {
        $states = BannerState::cases();
        return view(
            'livewire.admin.update-banner',
            [
                'states' => $states
            ]
        );
    }
}
