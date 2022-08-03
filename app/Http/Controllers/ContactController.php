<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ContactController extends Controller
{
    public function index(Request $request) {
		/** @var \App\Models\User $user **/
		$user = Auth::user();

		$search = $request->input('search');

		$contacts = $user->contacts()
			->where(function($contacts) use ($search) {
				return $contacts->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', "%$search%")
					->orWhere('mobile', 'LIKE', "%$search%");
			})
			->orderBy('first_name')
			->orderBy('last_name')
			->get();
		
		return response()->json($contacts);
	}

	public function store(Request $request) {
		/** @var \App\Models\User $user **/
		$user = Auth::user();
		
		$validated = $request->validate([
			'contact_id' => ['required', Rule::exists('users', 'id')->where('id', '!=', $user->id)]
		]);

		if (Contact::where('self_id', $user->id)->where('contact_id', $validated['contact_id'])->exists()) {
			return response()->json([
				'contact_id' => 'Contact already exists'
			], 400);
		}

		$user->contacts()->attach($validated['contact_id']);

		return response()->json('Added to contact.');
	}
}
