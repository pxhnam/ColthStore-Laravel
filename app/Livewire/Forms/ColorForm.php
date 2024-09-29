<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class ColorForm extends Form
{
    public $id;
    public $name;
    public $pic;
    public $url;

    public function rules()
    {
        return [
            'name' => ['required', 'string', $this->id ? 'unique:colors,name,' . $this->id : 'unique:colors,name'],
            'pic' => [$this->id ? 'nullable'  : 'required', 'sometimes', 'image', 'max:2048']
        ];
    }
}
