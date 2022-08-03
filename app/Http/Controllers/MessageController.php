<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Events\IncomingMessage;

class MessageController extends Controller
{
	public function index(Chat $chat)
	{
		/** @var \App\Models\User $user **/
		$user = auth()->user();

		Gate::denyIf(Chat::where('id', $chat->id)->whereHas('participants', function($participant) use ($user) {
			$participant->where('user_id', $user->id);
		})->doesntExist());

		return response()->json($chat->messages()->get());
	}

	public function store(Request $request, User $recipient)
	{
		/** @var \App\Models\User $user **/
		$user = auth()->user();

		$validated = $request->validate([
			'content' => ['sometimes', 'string'],
		]);

		$chat = $user->chats()->whereHas('participants', function ($participant) use ($recipient) {
			$participant->where('users.id', $recipient->id);
		})->first();

		if (is_null($chat)) {
			$chat = new Chat();
			$chat->save();

			$chat->participants()->attach($user);
			$chat->participants()->attach($recipient);
		}

		$message = new Message();
		$message->chat_id = $chat->id;
		$message->sender_id = $user->id;
		$message->content = $validated['content'];
		$message->sent_at = now();

		$message->save();

		IncomingMessage::dispatch($recipient, $message);

		return response()->json($message);
	}
}
