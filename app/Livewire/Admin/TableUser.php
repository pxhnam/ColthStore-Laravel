<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Enums\UserState;
use App\Livewire\Components\AdminComponent;
use App\Models\Role;
use Livewire\Attributes\Url;

class TableUser extends AdminComponent
{

    protected $validSortByColumns = ['name', 'email', 'email_verified_at', 'created_at', 'updated_at'];

    #[Url()]
    public $state;

    #[Url()]
    public $role;

    public $roles;

    public function mount()
    {
        $this->roles = Role::all();
    }

    public function create()
    {
        return $this->redirect(route('admin.users.create'), true);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->state = UserState::REMOVED->value;
        $user->save();
        $this->dispatch('success', 'User deleted successfully.');
    }

    public function render()
    {
        $users = User::query()
            ->where(function ($query) {
                $query->where('users.name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%");
            })
            ->when($this->role, function ($query) {
                $query->whereHas('roles', function ($query) {
                    $query->where('name', $this->role);
                });
            })
            ->when($this->state, function ($query) {
                $query->where('state', $this->state);
            })
            ->when($this->sortBy, function ($query) {
                $query->orderBy($this->sortBy, $this->sortDirection);
            })
            ->paginate($this->pageSize);
        return view(
            'livewire.admin.table-user',
            [
                'users' => $users,
            ]
        );
    }
}
