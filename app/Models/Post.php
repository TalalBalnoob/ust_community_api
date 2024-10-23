<?php

namespace App\Models;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model {

	protected $fillable = [
		'title',
		'body',
		'attachment_url',
	];

	public function likes(): HasMany {
		return $this->hasMany(Like::class);
	}

	public function comments(): HasMany {
		return $this->hasMany(Comment::class);
	}

	public function bookmarks(): HasMany {
		return $this->hasMany(Bookmark::class);
	}

	public function user(): BelongsTo {
		return $this->belongsTo(User::class);
	}

	public function addRegularPostInfo($user) {
		$this['isLiked'] = Like::query()->where('user_id', $user['id'])->where('post_id', $this['id'])->get()->count() === 1 ? true : false;
		$this['likes'] = $this->likes()->get()->count();

		$this['user'] = Helper::addUserProfileInfo($this['user_id']);

		$this->addComments();

		return $this;
	}

	public function addComments() {
		$this['comments'] = $this->comments()->get();

		foreach ($this['comments'] as $comment) {
			$comment['user'] = Helper::addUserProfileInfo($comment['user_id']);
		}

		return $this;
	}

	use HasFactory;
}
