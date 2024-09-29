<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class CategoryForm extends Form
{
    public $id;
    public $name;
    public $slug;
    public $pic;
    public $url;
    public $desc;

    public function rules()
    {
        return [
            'name' => ['required', 'string', $this->id ? 'unique:categories,name,' . $this->id : 'unique:categories,name'],
            'slug' => ['required', 'string', $this->id ? 'unique:categories,slug,' . $this->id : 'unique:categories,slug'],
            'pic' => [$this->id ? 'nullable'  : 'required', 'sometimes', 'image', 'max:2048'],
            'desc' => 'required'
        ];
    }
}
