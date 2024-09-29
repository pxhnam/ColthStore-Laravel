<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\On;
use App\Livewire\Components\AdminComponent;
use App\Models\Product;

class TableProduct extends AdminComponent
{
    public function create()
    {
        $this->dispatch('create-product');
    }

    public function update($id)
    {
        $this->dispatch('update-product', id: $id);
    }

    #[On('load-products')]
    public function render()
    {
        $products = Product::query()
            ->where(function ($query) {
                $query->where('name', 'like', "%{$this->search}%");
            })
            ->when($this->sortBy, function ($query) {
                $query->orderBy($this->sortBy, $this->sortDirection);
            })
            ->paginate($this->pageSize);
        return view(
            'livewire.admin.table-product',
            [
                'products' => $products
            ]
        );
    }
}
