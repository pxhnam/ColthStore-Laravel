<?php

namespace App\Livewire\Admin;

use Exception;
use App\Models\User;
use App\Enums\UserRole;
use Livewire\Component;
use App\Enums\UserState;
use App\Livewire\Forms\UserForm;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UpdateUser extends Component
{
    public UserForm $user;
    public $states;
    public $roles;


    public function mount($id = null)
    {
        $this->states = UserState::cases();
        $this->roles = UserRole::cases();
        if ($id) {
            try {
                $this->user->id = $id;
                $user = User::findOrFail($id);
                $this->user->name = $user->name;
                $this->user->email = $user->email;
                $this->user->state = $user->state;
                foreach ($this->roles as $role) {
                    if ($user->hasRole($role)) {
                        $this->user->roles[] = $role;
                    }
                }
            } catch (Exception $ex) {
                Log::error($ex->getMessage());
                $this->dispatch('error', 'An unexpected error occurred. Please try again.');
            }
        }
    }

    public function submit()
    {
        $this->user->validate();
        DB::beginTransaction();
        try {
            if ($this->user->id) {
                $user = User::findOrFail($this->user->id);
                $user->name = $this->user->name;
                $user->email = $this->user->email;
                $user->state = $this->user->state;
                if ($this->user->password) {
                    $user->password = Hash::make($this->user->password);
                }
                $user->save();
                foreach ($this->roles ?? [] as $role) {
                    $user->removeRole($role);
                }
                foreach ($this->user->roles as $role) {
                    $user->assignRole($role);
                }
                $this->dispatch('success', 'Updated Successfully.');
            } else {
                $user = User::create([
                    'name' => $this->user->name,
                    'email' => $this->user->email,
                    'password' => Hash::make($this->user->password)
                ]);
                $user->state = $this->user->state;
                $user->email_verified_at = now();
                $user->save();
                foreach ($this->user->roles as $role) {
                    $user->assignRole($role);
                }
                $this->user->reset();
                $this->dispatch('success', 'Created Successfully.');
            }
            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error($ex->getMessage());
            $this->dispatch('error', 'An unexpected error occurred. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.admin.update-user');
    }
}
