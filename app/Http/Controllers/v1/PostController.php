<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Staff;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller {

	public function index() {
		$pq = Post::query()->paginate();

		return response(['posts' => $pq]);
	}

	// TODO: add the attachment store funcinolty
	// FIXME: fix the errors 😢
	public function store(Request $request) {
		$validateReq = $request->validate([
			'title' => ['nullable', 'string', 'min:3', 'max:255'],
			'body' => ['required', 'string'],
			'attachment' => ['nullable', 'file']
		]);

		$newPost = new Post;

		if ($validateReq['title']) $newPost['title'] = $validateReq['title'];

		$newPost['body'] = $validateReq['body'];

		$newPost['user_id'] = $request->user()['id'];

		$newPost->save();

		return response(['message' => 'new post has been added', 'post' => $newPost]);
	}

	public function show(Post $post) {
		$post['likes'] = $post->likes()->get()->count();
		$post['comments'] = $post->comments()->get();

		$poster_type = User::query()->find($post['user_id'])['user_type_id'];

		if ($poster_type == 1) $post['poster'] = Student::query()->where('user_id', $post['user_id'])->first();
		if ($poster_type == 2) $post['poster'] = Staff::query()->where('user_id', $post['user_id'])->first();

		return response($post);
	}

	public function update(Request $request, Post $post) {
		Gate::authorize('update', $post);

		$validateReq = $request->validate([
			'title' => ['nullable', 'string', 'min:3', 'max:255'],
			'body' => ['required', 'string'],
			'attachment' => ['nullable', 'file']
		]);

		$post->update($validateReq);

		return response(['message' => 'update']);
	}

	public function destroy(Request $request, Post $post) {
		Gate::authorize('destroy', $post);

		$post->delete();

		return response(['message' => 'Post has been deleted']);
	}
}
