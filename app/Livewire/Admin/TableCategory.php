<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\On;
use App\Livewire\Components\AdminComponent;
use App\Models\Category;

class TableCategory extends AdminComponent
{

    public function create()
    {
        $this->dispatch('create-category');
    }

    public function update($id)
    {
        $this->dispatch('update-category', id: $id);
    }

    #[On('load-categories')]
    public function render()
    {
        $categories = Category::query()
            ->where(function ($query) {
                $query->where('name', 'like', "%{$this->search}%");
            })
            ->when($this->sortBy, function ($query) {
                $query->orderBy($this->sortBy, $this->sortDirection);
            })
            ->paginate($this->pageSize);
        return view(
            'livewire.admin.table-category',
            [
                'categories' => $categories
            ]
        );
    }
}
