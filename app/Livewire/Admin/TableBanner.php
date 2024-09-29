<?php

namespace App\Livewire\Admin;

use App\Livewire\Components\AdminComponent;
use Exception;
use App\Models\Banner;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;

class TableBanner extends AdminComponent
{

    public function create()
    {
        $this->dispatch('create-banner');
    }
    public function update($id)
    {
        $this->dispatch('update-banner', id: $id);
    }

    #[On('destroy')]
    public function destroy($id)
    {
        try {
            $banner = Banner::findOrFail($id);
            $banner->delete();
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
        }
    }

    #[On('load-banners')]
    public function render()
    {
        $banners = Banner::query()
            ->where(function ($query) {
                $query->where('title', 'like', "%{$this->search}%")
                    ->orWhere('sub', 'like', "%{$this->search}%");
            })
            ->when($this->sortBy, function ($query) {
                $query->orderBy($this->sortBy, $this->sortDirection);
            })
            ->paginate($this->pageSize);
        return view(
            'livewire.admin.table-banner',
            [
                'banners' => $banners
            ]
        );
    }
}
