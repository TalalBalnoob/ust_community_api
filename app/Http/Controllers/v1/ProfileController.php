<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Follower;
use App\Models\Major;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class ProfileController extends Controller {

	public function getCurrentUserProfile(Request $request) {
		$user = $request->user();

		$user['following'] = Follower::query()->where('follower_id', $user['id'])->get()->count();
		$user['followers'] = Follower::query()->where('followed_id', $user['id'])->get()->count();
		$user['profile'] = User::addUserProfileInfo($user['id']);
		$user['posts'] = Post::query()->where('user_id', $user['id'])->get();
		if ($user->user_type_id === 1) $user['profile']['major'] = Major::where('id', $user['profile']['major_id'])->value('major');
		if ($user->user_type_id === 2) $user['profile']['role'] = Role::where('id', $user['profile']['role_id'])->value('role');

		foreach ($user['posts'] as $post) {
			$post->addRegularPostInfo($user['id']);
		}

		$user['comments'] = Comment::query()->where('user_id', $user['id']);


		return response(['user' => $user]);
	}

	public function getUserProfile(Request $request, string $user_id) {
		$user = User::query()->find($user_id);

		if (!$user) abort(404, 'User Not found');

		$user['following'] = Follower::query()->where('follower_id', $user['id'])->get()->count();
		$user['followers'] = Follower::query()->where('followed_id', $user['id'])->get()->count();
		$user['profile'] = User::addUserProfileInfo($user['id']);
		$user['posts'] = Post::query()->where('user_id', $user['id'])->get();
		if ($user->user_type_id === 1) $user['profile']['major'] = Major::where('id', $user['profile']['major_id'])->value('major');
		if ($user->user_type_id === 2) $user['profile']['role'] = Role::where('id', $user['profile']['role_id'])->value('role');

		foreach ($user['posts'] as $post) {
			$post->addRegularPostInfo($user['id']);
		}

		$user['comments'] = Comment::query()->where('user_id', $user['id'])->get();

		foreach ($user['comments']->all() as $comment) {
			$comment['user'] = User::addUserProfileInfo($comment['user_id']);
		}


		return response(['user' => $user]);
	}
}
