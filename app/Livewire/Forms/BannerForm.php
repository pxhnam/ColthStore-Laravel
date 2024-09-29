<?php

namespace App\Livewire\Forms;

use App\Enums\BannerState;
use Livewire\Attributes\Validate;
use Livewire\Form;

class BannerForm extends Form
{
    public $id;
    public $title;
    public $sub;
    public $pic;
    public $path;
    public $state;

    public function rules()
    {
        return [
            'title' => 'required',
            'sub' => 'required',
            'pic' => [$this->id ? 'nullable'  : 'required', 'image', 'max:2048'],
            'state' => 'required|in:' . implode(',', BannerState::getValues()),
        ];
    }
}
