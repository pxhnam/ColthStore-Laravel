<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Helpers\Mail;
use App\Models\Token;
use App\Enums\TokenType;
use App\Enums\UserRole;
use App\Enums\UserState;
use App\Helpers\Response;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\auth\LoginRequest;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        return view('client.pages.auth.login');
    }
    public function handleLogin(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->validated();

        // if (Auth::attempt($credentials)) {
        if (Auth::attemptWhen(
            $credentials,
            function (User $user) {
                return $user->state === UserState::ACTIVED->value &&
                    $user->hasRole(UserRole::USER->value);
            }
        )) {
            $request->session()->regenerate();
            return redirect()->intended('');
        }
        return back()->onlyInput('email')->withErrors(['error' => 'Incorrect account or password!']);
    }

    public function register()
    {
        return view('client.pages.auth.register');
    }

    public function handleRegister(RegisterRequest $request): RedirectResponse
    {
        $credentials = $request->validated();
        try {
            $user = User::create([
                'name' => $credentials['name'],
                'email' => $credentials['email'],
                'password' => Hash::make($credentials['password']),
            ]);
            $user->assignRole(UserRole::USER);
            $this->generateCode(
                $user->id,
                $credentials['name'],
                $credentials['email'],
                TokenType::ACCOUNT_VERIFICATION
            );
            return redirect()->route('verification')
                ->with('success', 'Please check your email to verify your account.')
                ->with('email', $credentials['email']);
        } catch (\Exception $e) {
            Log::error('Registration failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'There was an issue with your registration. Please try again.');
        }
    }

    public function verification()
    {
        return view('client.pages.auth.account_verification');
    }

    public function verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'code' => 'required',
        ]);
        if ($validator->passes()) {
            $user = User::where('email', $request->input('email'))->first();
            if ($user) {
                if ($user->state === UserState::PENDING) {
                    $token = Token::where('user_id', $user->id)
                        ->where('type', TokenType::ACCOUNT_VERIFICATION)
                        ->latest('created_at')
                        ->first();
                    if ($token->timeout > now()) {
                        $user->state = UserState::ACTIVED;
                        $user->email_verified_at = now();
                        $user->save();
                        return back()->with(['success' => 'Verification successful! You can now log in.']);
                    } else {
                        return back()->onlyInput('email')
                            ->withErrors(['error' => 'The verification code has expired. Please request a new code.']);
                    }
                } else {
                    return back()->with(['success' => 'Previously verified account.']);
                }
            } else {
                return back()->onlyInput('email')->withErrors(['error' => 'Invalid verification code. Please try again.']);
            }
        } else {
            return back()->onlyInput('email')->withErrors($validator);
        }
    }

    public function refreshToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ], [
            'email.required' => 'Please enter the email to receive the code.',
            'email.email' => 'Please enter a valid email address.',
        ]);

        if ($validator->fails()) {
            return Response::make(
                false,
                '',
                [
                    'errors' => $validator->errors()
                ]
            );
        }

        $user = User::where('email', $request->input('email'))->first();
        if ($user) {
            if ($user->state === UserState::PENDING) {
                $token = Token::where('user_id', $user->id)
                    ->where('type', TokenType::ACCOUNT_VERIFICATION)
                    ->latest('created_at')
                    ->first();
                if ($token->timeout < now()) {
                    $this->generateCode(
                        $user->id,
                        $user->name,
                        $user->email,
                        TokenType::ACCOUNT_VERIFICATION
                    );
                    return Response::make(true, 'Please check your email for the verification code.');
                } else {
                    return Response::make(false, 'You can only request a new code every 5 minutes.');
                }
            } else {
                return Response::make(true, 'Previously verified account.');
            }
        } else {
            return Response::make(false, 'Account not registered.');
        }
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function generateCode($id, $name, $email, $typeToken)
    {
        try {
            $code = Str::random(10);
            Token::create([
                'code' => $code,
                'timeout' => now()->addMinutes(5),
                'type' => $typeToken,
                'user_id' => $id,
            ]);
            $params = [
                'name' => $name,
                'code' => $code,
            ];
            Mail::send(
                'emails.account_verification',
                $params,
                'MÃ BẢO MẬT CỦA BẠN',
                $email,
                $name
            );
        } catch (\Exception $e) {
            Log::error('Error generating verification code or sending email: ' . $e->getMessage());
        }
    }
}
