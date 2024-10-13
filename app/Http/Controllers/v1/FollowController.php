<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Follower;
use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller {
	public function follow(Request $request, string $followed_id) {
		$isUserExist = User::query()->find($followed_id);
		$isFollowed = Follower::query()->where('follower_id', $request->user()['id'])->where('followed_id', $followed_id)->first();

		if ($isFollowed) abort(409, 'already followed');
		if (!$isUserExist) abort(404, 'user dose not exist');

		$newFollow = new Follower([
			'follower_id' => $request->user()['id'],
			'followed_id' => $isUserExist['id']
		]);

		$newFollow->save();

		return response(['message' => 'user has been followed']);
	}

	public function unfollow(Request $request, string $followed_id) {
		$isFollowed = Follower::query()->where('follower_id', $request->user()['id'])->where('followed_id', $followed_id)->first();

		if (!$isFollowed) abort(404, 'follow not found');

		$isFollowed->delete();

		return response(['message' => 'user has been un followed']);
	}
}
