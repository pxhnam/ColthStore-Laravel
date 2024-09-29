<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class SizeForm extends Form
{
    public $id;
    public $name;

    public function rules()
    {
        return [
            'name' => ['required', 'string', $this->id ? 'unique:sizes,name,' . $this->id : 'unique:sizes,name'],
        ];
    }
}
