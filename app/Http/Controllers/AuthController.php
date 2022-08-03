<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request) {
		$validated = $request->validate([
			'first_name' => ['required'],
			'last_name' => ['required'],
			'mobile' => ['required', 'unique:users'],
			'password' => ['required', 'min:8']
		]);

		$user = new User();

		$user->first_name = $validated['first_name'];
		$user->last_name = $validated['last_name'];
		$user->mobile = $validated['mobile'];
		$user->password = Hash::make($validated['password']);

		$user->save();

		Auth::login($user);

		return redirect('/');
	}

	public function login(Request $request) {
		$request->validate([
			'mobile' => ['required', 'exists:users'],
			'password' => ['required', 'min:8'],
			'remember' => ['sometimes', 'boolean']
		]);

		if (!Auth::attempt($request->only(['mobile', 'password']), $request->input('remember'))) {
			throw ValidationException::withMessages(['password' => 'Invalid password.']);
		}

		return redirect()->intended('/');
	}

	public function logout(Request $request) {
		Auth::logout();

		$request->session()->invalidate();

		$request->session()->regenerateToken();

		return redirect('/');
	}
}
