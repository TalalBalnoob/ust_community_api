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
		'name',
		'email',
		'password',
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

	public function type(): HasOne {
		return $this->hasOne(UserType::class);
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

	public function student(): BelongsTo {
		return $this->belongsTo(Student::class);
	}

	public function staff(): BelongsTo {
		return $this->belongsTo(Staff::class);
	}
}
