<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller {

	public function getCurrentUserProfile(Request $request) {
		$user = $request->user();

		$user['profile'] = Helper::addUserProfileInfo($user['id']);

		return response(['user' => $user]);
	}

	public function getUserProfile(Request $request, string $user_id) {
		$user = User::query()->find($user_id);

		if (!$user) abort(404, 'User Not found');

		$user['profile'] = Helper::addUserProfileInfo($user['id']);

		return response(['user' => $user]);
	}
}
