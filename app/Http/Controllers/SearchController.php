<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Staff;
use App\Models\Student;
use Illuminate\Http\Request;

class SearchController extends Controller {
	public function search(Request $request) {
		$searchText = $request['text'];

		$posts = Post::query()
			->where('title', 'LIKE', "%{$searchText}%")
			->orWhere('body', 'LIKE', "%{$searchText}%")
			->get();

		foreach ($posts as $post) {
			$post->addRegularPostInfo($request->user());
		}

		$users = Student::query()
			->where('displayName', 'LIKE', "%{$searchText}%")
			->get();

		$users->merge(Staff::query()
			->where('displayName', 'LIKE', "%{$searchText}%")
			->get());

		return response(['results' => ['posts' => $posts, 'users' => $users]]);
	}
}
