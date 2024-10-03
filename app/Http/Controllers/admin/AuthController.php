<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use App\Enums\UserRole;
use App\Enums\UserState;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\auth\LoginRequest;

class AuthController extends Controller
{

    public function login()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->hasRole(UserRole::ADMIN->value)) {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('home');
            }
        }
        return view('admin.pages.auth.login');
    }

    public function handleLogin(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->validated();
        if (Auth::attemptWhen(
            $credentials,
            function (User $user) {
                return $user->state === UserState::ACTIVED->value &&
                    $user->hasRole(UserRole::ADMIN->value);
            }
        )) {
            // Authentication was successful...
            $request->session()->regenerate();
            return redirect()->intended('admin/dashboard');
        }
        return back()->onlyInput('email')->withErrors(['error' => 'Tài khoản hoặc mật khẩu không chính xác.']);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin');
    }
}
