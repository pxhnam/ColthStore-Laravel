<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;



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

    public function sort($sortBy)
    {
        if ($this->sortBy == $sortBy) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
            $this->sortBy = $sortBy;
        }
    }
}
