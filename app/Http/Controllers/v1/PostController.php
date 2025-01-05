<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;

class PostController extends Controller {
	public function index(Request $request, Post $post) {
		$post_page = Post::query()->latest()->paginate(10);

		foreach ($post_page->items() as $post) {
			$post->addRegularPostInfo($request->user()['id']);
		}

		return response(['posts' => $post_page]);
	}

	public function store(Request $request) {
		$validateReq = $request->validate([
			'title' => ['nullable', 'string', 'min:3', 'max:255'],
			'body' => ['required', 'string'],
			'attachment' => ['nullable', 'image', 'mimes:jpg,png,jpeg,gif', 'max:5120']
		]);

		$newPost = new Post();

		if ($validateReq['title']) {
			$newPost['title'] = $validateReq['title'];
		}
		$newPost['body'] = $validateReq['body'];
		$newPost['user_id'] = $request->user()['id'];
		$newPost['attachment_url'] = isset($validateReq['attachment'])
			? Storage::disk('public')->put('/', $validateReq['attachment'])
			: null;

		$newPost->save();

		return response(['message' => 'new post has been added', 'post' => $newPost]);
	}

	public function show(int $postID, Request $request) {
		$post = Post::query()->where('id', $postID)->get()->first();

		if (!$post) return response(['message' => "not found"], 404);

		$post->addRegularPostInfo($request->user()['id']);

		return response(['data' => $post]);
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

	public function destroy(Post $post) {
		Gate::authorize('destroy', $post);

		$post->delete();

		return response(['message' => 'Post has been deleted']);
	}
}
