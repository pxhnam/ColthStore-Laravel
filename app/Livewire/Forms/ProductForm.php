<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Enums\ProductState;
use Livewire\Attributes\Validate;

class ProductForm extends Form
{
    public $id;
    public $name;
    public $slug;
    public $category;
    public $pics = [];
    public $preview = [];
    public $desc;
    public $state;
    public $details = [];

    public function rules()
    {
        return [
            'name' => ['required', 'string', $this->id ? 'unique:products,name,' . $this->id : 'unique:products,name'],
            'slug' => ['required', 'string', $this->id ? 'unique:products,slug,' . $this->id : 'unique:products,slug'],
            'pics' => [$this->id ? 'nullable'  : 'required', 'array'],
            'pics.*' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'category' => 'required|exists:categories,id',
            'desc' => 'required|string',
            'state' => 'required|in:' . implode(',', ProductState::getValues()),

            'details.*.size' => 'required|exists:sizes,id',
            'details.*.color' => 'required|exists:colors,id',
            'details.*.amount' => 'required|numeric|min:1',
            'details.*.cost' => 'required|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'details.*.size.required' => 'The size field is required.',
            'details.*.size.exists' => 'The selected size is invalid.',

            'details.*.color.required' => 'The color field is required.',
            'details.*.color.exists' => 'The selected color is invalid.',

            'details.*.amount.required' => 'The amount field is required.',
            'details.*.amount.numeric' => 'The amount must be a number.',
            'details.*.amount.min' => 'The amount must be at least 1.',

            'details.*.cost.required' => 'The cost field is required.',
            'details.*.cost.numeric' => 'The cost must be a number.',
            'details.*.cost.min' => 'The cost must be at least 0.',
        ];
    }
}
