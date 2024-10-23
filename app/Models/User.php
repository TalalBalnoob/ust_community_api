<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable {
	use HasApiTokens, HasFactory, Notifiable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'username',
		'password',
		'isAdmin',
		'user_type_id'
	];

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
	 * Get the attributes that should be cast.
	 *
	 * @return array<string, string>
	 */
	protected function casts(): array {
		return [
			'email_verified_at' => 'datetime',
			'password' => 'hashed',
		];
	}

	public function type(): BelongsTo {
		return $this->belongsTo(UserType::class);
	}

	public function posts(): HasMany {
		return $this->hasMany(Post::class);
	}

	public function likes(): HasMany {
		return $this->hasMany(Like::class);
	}

	public function comments(): HasMany {
		return $this->hasMany(Comment::class);
	}


	public function bookmarks(): HasMany {
		return $this->hasMany(Bookmark::class);
	}

	public function student(): HasOne {
		return $this->hasOne(Student::class);
	}

	public function staff(): HasOne {
		return $this->hasOne(Staff::class);
	}

	public static function addUserProfileInfo(string $user_id) {
		$user = User::query()->find($user_id);

		if ($user['user_type_id'] == 1) return Student::query()->where('user_id', $user['id'])->first();
		if ($user['user_type_id'] == 2) return Staff::query()->where('user_id', $user['id'])->first();

		return $user;
	}
}
