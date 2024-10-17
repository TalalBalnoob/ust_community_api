<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model {

	protected $fillable = [
		'body',
		'attachment_url',
	];

	public function user(): BelongsTo {
		return $this->belongsTo(User::class);
	}

	public function post(): BelongsTo {
		return $this->belongsTo(Post::class);
	}
	use HasFactory;
}
