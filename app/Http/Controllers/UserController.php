<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
	public function index(Request $request) {
		/** @var \App\Models\User $user **/
		$user = Auth::user();

		$search = $request->input('search');
		
		$users = User::whereNotIn('id', [$user->id, ...$user->contacts->pluck('id')])
			->where(function($contacts) use ($search) {
				return $contacts->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', "%$search%")
					->orWhere('mobile', 'LIKE', "%$search%");
			})
			->orderBy('first_name')
			->orderBy('last_name')
			->get();

		return response()->json($users);
	}

    public function show(User $user) {
		return response()->json($user);
	}

	public function update(Request $request) {

		/** @var \App\Models\User $user **/
		$user = Auth::user();

		$validated = $request->validate([
			'first_name' => 'required',
			'last_name' => 'required',
			'image' => ['sometimes', 'image'],
			'about' => ['nullable', 'string'],
			'mobile' => ['required', Rule::unique('users', 'mobile')->ignore($user->id)],
			'password' => ['sometimes', 'min:8']
		]);

		$user->first_name = $validated['first_name'];
		$user->last_name = $validated['last_name'];
		$user->mobile = $validated['mobile'];

		if (isset($validated['password'])) {
			$user->password = Hash::make($validated['password']);
		}

		if (isset($validated['image'])) {
			$user->image = $validated['image']->store('profile_images', 'public');
		}

		$user->save();

		return response()->json($user);
	}

	public function currentUser() {
		return auth()->user();
	}
}
