<?php

namespace App\Livewire\Components;

use Exception;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class AdminComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    #[Url(history: true)]
    public $search;

    #[Url()]
    public $sortBy = '';

    #[Url()]
    public $sortDirection = '';

    public $pageSize = 10;

    protected $validSortByColumns = [];

    protected $validSortDirections = ['asc', 'desc'];

    public function mount()
    {
        if ($this->sortBy) {
            $this->sortBy = strtolower($this->sortBy);
            if (! in_array($this->sortBy, $this->validSortByColumns, true)) {
                return redirect()->to(url()->current());
            }
        }
        if ($this->sortDirection) {
            $this->sortDirection = strtolower($this->sortDirection);
            if (! in_array($this->sortDirection, $this->validSortDirections, true)) {
                return redirect()->to(url()->current());
            }
        }
    }

    public function sort($sortBy)
    {
        try {
            if ($this->sortBy == $sortBy) {
                $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                $this->sortDirection = 'asc';
                $this->sortBy = $sortBy;
            }
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
        }
    }

    protected function queryString()
    {
        return [
            'search' => [
                'as' => 'q',
            ],
            'sortBy' => [
                'as' => 'sort',
            ],
            'sortDirection' => [
                'as' => 'arr',
            ]
        ];
    }
}
