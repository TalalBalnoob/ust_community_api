<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Major extends Model {

	public function student(): BelongsTo {
		return $this->belongsTo(Student::class);
	}
	use HasFactory;
}
