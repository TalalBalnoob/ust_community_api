<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Staff extends Model {

	public function user(): HasOne {
		return $this->hasOne(User::class);
	}

	public function role(): HasOne {
		return $this->hasOne(Role::class);
	}

	use HasFactory;
}
