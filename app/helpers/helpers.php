<?php

namespace App\Helpers;

use App\Models\Staff;
use App\Models\Student;
use App\Models\User;

class Helper {
	public static function addUserProfileInfo(string $user_id) {
		$user = User::query()->find($user_id);

		if ($user['user_type_id'] == 1) return Student::query()->where('user_id', $user['id'])->first();
		if ($user['user_type_id'] == 2) return Staff::query()->where('user_id', $user['id'])->first();
	}
}
