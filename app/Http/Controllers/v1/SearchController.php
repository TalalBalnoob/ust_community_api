<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Major;
use App\Models\Post;
use App\Models\Role;
use App\Models\Staff;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller {
	public function search(Request $request) {
		$searchText = $request['text'];

		$posts = Post::query()
			->where('title', 'LIKE', "%{$searchText}%")
			->orWhere('body', 'LIKE', "%{$searchText}%")
			->get();

		foreach ($posts as $post) {
			$post->addRegularPostInfo($request->user()['id']);
		}

		$users = Student::query()
			->where('displayName', 'LIKE', "%{$searchText}%")
			->get();

		$users->merge(Staff::query()
			->where('displayName', 'LIKE', "%{$searchText}%")
			->get());

		foreach ($users as $user) {
			$user['profile'] = User::addUserProfileInfo($user['id']);
			if ($user->user_type_id === 1) $user['profile']['major'] = Major::where('id', $user['profile']['major_id'])->value('major');
			if ($user->user_type_id === 2) $user['profile']['role'] = Role::where('id', $user['profile']['role_id'])->value('role');
		}

		return response()->json(['posts' => $posts, 'users' => $users]);
	}
}
