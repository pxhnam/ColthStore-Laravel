<?php

namespace App\Livewire\Admin;

use Exception;
use App\Models\Size;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;
use App\Livewire\Components\AdminComponent;

class TableSize extends AdminComponent
{
    protected $validSortByColumns = ['name', 'created_at', 'updated_at'];

    public function create()
    {
        $this->dispatch('create-size');
    }
    public function update($id)
    {
        $this->dispatch('update-size', id: $id);
    }

    #[On('destroy')]
    public function destroy($id)
    {
        try {
            $size = Size::findOrFail($id);
            $size->delete();
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
        }
    }

    #[On('load-sizes')]
    public function render()
    {
        $sizes = Size::query()
            ->where(function ($query) {
                $query->where('name', 'like', "%{$this->search}%");
            })
            ->when(
                $this->sortBy,
                function ($query) {
                    $query->orderBy($this->sortBy, $this->sortDirection);
                }
            )
            ->paginate($this->pageSize);
        return view(
            'livewire.admin.table-size',
            [
                'sizes' => $sizes
            ]
        );
    }
}
