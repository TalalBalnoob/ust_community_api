<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Follower;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller {

	public function getCurrentUserProfile(Request $request) {
		$user = $request->user();

		$user['following'] = Follower::query()->where('follower_id', $user['id'])->get()->count();
		$user['followers'] = Follower::query()->where('followed_id', $user['id'])->get()->count();
		$user['profile'] = User::addUserProfileInfo($user['id']);
		$user['posts'] = Post::query()->where('user_id', $user['id'])->get();

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

		foreach ($user['posts'] as $post) {
			$post->addRegularPostInfo($user['id']);
		}

		$user['comments'] = Comment::query()->where('user_id', $user['id']);


		return response(['user' => $user]);
	}
}
