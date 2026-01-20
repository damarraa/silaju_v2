<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * View login.
     */
    public function create()
    {
        return view('pages.auth.signin');
    }

    /**
     * Login.
     */
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (!Auth::attempt($credentials, $remember)) {
            throw ValidationException::withMessages([
                'username' => trans('auth.failed'),
            ]);
        }

        $request->session()->regenerate();

        $user = Auth::user();
        if ($user->hasRole('super_admin')) {
            return redirect()->intended(route('dashboard.admin'));
        }

        return redirect()->intended(route('dashboard.index'));
    }

    /**
     * Logout.
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('login');
    }
}
