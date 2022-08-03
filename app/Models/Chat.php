<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

	public function messages() {
		return $this->hasMany(Message::class);
	}

	public function unreadMessages() {
		return $this->messages()->whereNull('seen_at');
	}

	public function groupDetails() {
		return $this->hasOne(GroupDetail::class);
	}

	public function participants() {
		return $this->belongsToMany(User::class, 'chat_participants', 'chat_id', 'user_id');
	}
}
