<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class UserForm extends Form
{
    public $id;
    public $name;
    public $email;
    public $password;
    public $state;
    public $roles = [];
    // protected $rules = [
    //     'name' => 'required',
    //     'email' => ['required', 'email', $this->id ? 'unique:users,email,' . $this->id : 'unique:users,email'],
    //     'password' => $this->id ? 'nullable' : 'required',
    //     'roles' => 'required|array|min:1',
    // ];
    public function rules()
    {
        return [
            'name' => 'required',
            'email' => ['required', 'email', $this->id ? 'unique:users,email,' . $this->id : 'unique:users,email'],
            'password' => $this->id ? 'nullable' : 'required',
            'roles' => 'required|array|min:1'
        ];
    }

    protected $messages = [];

    protected $validationAttributes = [];
}
