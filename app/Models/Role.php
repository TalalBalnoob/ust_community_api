<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Role extends Model {

	public function staff(): BelongsTo {
		return $this->belongsTo(Staff::class);
	}
	use HasFactory;
}
