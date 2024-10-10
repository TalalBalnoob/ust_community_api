<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserType extends Model {

	public $timestamps = false;

	public function user(): HasMany {
		return $this->hasMany(User::class);
	}
	use HasFactory;
}
