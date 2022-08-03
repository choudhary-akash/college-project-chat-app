<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

	protected $appends = ['image'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

	public function contacts() {
		return $this->belongsToMany(User::class, 'contacts', 'self_id', 'contact_id');
	}

	public function getFullNameAttribute() {
		return $this->first_name . ' ' . $this->last_name;
	}

	public function chats() {
		return $this->belongsToMany(Chat::class, 'chat_participants', 'user_id', 'chat_id');
	}

	public function getImageAttribute() {
		if (isset($this->attributes['image']) && Storage::disk('public')->exists($this->attributes['image'])) {
			return Storage::disk('public')->url($this->attributes['image']);
		} else {
			return asset('img/placeholder_profile_image.png');
		}
	}
}
