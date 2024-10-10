<?php

namespace App\Livewire\Admin;

use Exception;
use App\Models\Color;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;
use App\Livewire\Components\AdminComponent;

class TableColor extends AdminComponent
{
    protected $validSortByColumns = ['name', 'created_at', 'updated_at'];

    public function create()
    {
        $this->dispatch('create-color');
    }
    public function update($id)
    {
        $this->dispatch('update-color', id: $id);
    }

    #[On('destroy')]
    public function destroy($id)
    {
        try {
            $color = Color::findOrFail($id);
            $color->delete();
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
        }
    }

    #[On('load-colors')]
    public function render()
    {
        $colors = Color::query()
            ->where(function ($query) {
                $query->where('name', 'like', "%{$this->search}%");
            })
            ->when($this->sortBy, function ($query) {
                $query->orderBy($this->sortBy, $this->sortDirection);
            })
            ->paginate($this->pageSize);
        return view(
            'livewire.admin.table-color',
            [
                'colors' => $colors
            ]
        );
    }
}
