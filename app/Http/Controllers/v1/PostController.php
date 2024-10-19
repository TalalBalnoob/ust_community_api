<?php

namespace App\Http\Controllers\v1;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Post;
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

		$post['poster'] = Helper::addUserProfileInfo($post['user_id']);

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
