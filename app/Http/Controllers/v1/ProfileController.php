<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Follower;
use App\Models\Major;
use App\Models\Post;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class ProfileController extends Controller {

	public function getUserProfile(Request $request, string $user_id) {
		if (!$user_id) {
			$user_id = $request->user()->id;
		}

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

		foreach ($user['comments'] as $comment) {
			$comment['user']['profile'] = User::addUserProfileInfo($comment['user_id']);
		}

		return response()->json($user);
	}

	public function editUserProfile(Request $request) {
		$user = $request->user();
		if (!$user) abort(404, 'User Not found');

		$validateReq = $request->validate([
			'username' => ['nullable', 'string', 'min:3', 'max:32'],
			'bio' => ['nullable', 'string', 'max:255'],
			'attachment' => ['nullable', 'image', 'mimes:jpg,png,jpeg,gif', 'max:5120']
		]);

		$profile = $user->user_type_id === 1 ? Student::find($user->id) : User::find($user->id);

		if ($validateReq['username']) $profile->displayName = 'New Display Name';
		if ($validateReq['bio']) $profile->bio = 'Updated bio content';

		$profile->save();

		$user['profile'] = User::addUserProfileInfo($user['id']);
		if ($user->user_type_id === 1) $user['profile']['major'] = Major::where('id', $user['profile']['major_id'])->value('major');
		if ($user->user_type_id === 2) $user['profile']['role'] = Role::where('id', $user['profile']['role_id'])->value('role');

		return response()->json($user);
	}

	public function followers(Request $request, string $user_id) {
		$users = Follower::query()->where('followed_id', $user_id)->get('follower_id');
		$users_followers = new Collection();

		foreach ($users as $follower) {
			$res = User::query()->where('id', $follower->follower_id)->get()->first();
			$res['profile'] = User::addUserProfileInfo($res['id']);

			$users_followers->add($res);
		}

		return response()->json($users_followers);
	}

	public function followings(Request $request, string $user_id) {
		$users = Follower::query()->where('follower_id', $user_id)->get('followed_id');
		$users_followings = new Collection();

		foreach ($users as $followed) {
			$res = User::query()->where('id', $followed->followed_id)->get()->first();
			$res['profile'] = User::addUserProfileInfo($res['id']);

			$users_followings->add($res);
		}

		return response()->json($users_followings);
	}
}
