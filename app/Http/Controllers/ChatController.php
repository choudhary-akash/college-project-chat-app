<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ChatController extends Controller
{
	public function index()
	{
		/** @var \App\Models\User $user **/
		$user = Auth::user();

		$chats = $user->chats->transform(function ($chat) use ($user) {
			$chat->messages = $chat->messages()->latest()->take(1)->get();

			if ($chat->type == 'group') {
				$chat->display_image = $chat->groupDetails?->image;
				$chat->display_name = $chat->groupDetails?->name;
			} else {
				$chat->recipient = $chat->participants()->wherePivot('user_id', '!=', $user->id)->first();
				$chat->recipient?->append('image');
			}

			return $chat;
		});

		return response()->json($chats);
	}

	public function show(Chat $chat)
	{
		/** @var \App\Models\User $user **/
		$user = Auth::user();

		Gate::denyIf(Chat::where('id', $chat->id)->whereHas('participants', function ($participant) use ($user) {
			$participant->where('user_id', $user->id);
		})->doesntExist());

		$chat->messages = $chat->messages()->latest()->take(1)->get();

		if ($chat->type == 'group') {
			$chat->display_image = $chat->groupDetails?->image;
			$chat->display_name = $chat->groupDetails?->name;
		} else {
			$chat->recipient = $chat->participants()->wherePivot('user_id', '!=', $user->id)->first();
		}
		
		return $chat;
	}

	public function markAsRead(Chat $chat) {
		/** @var \App\Models\User $user **/
		$user = Auth::user();

		Gate::denyIf(Chat::where('id', $chat->id)->whereHas('participants', function ($participant) use ($user) {
			$participant->where('user_id', $user->id);
		})->doesntExist());

		$chat->unreadMessages()->update([
			'seen_at' => now()
		]);

		return response()->json('Marked as read.');
	}
}
