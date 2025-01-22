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

		return response()->json($post_page->items());
	}

	public function store(Request $request) {
		$validateReq = $request->validate([
			'title' => ['nullable', 'string', 'min:3', 'max:255'],
			'body' => ['required', 'string'],
			'attachment' => ['nullable', 'image', 'mimes:jpg,png,jpeg,gif', 'max:5120']
		]);

		$attachmentUrl = null;
		if ($request->hasFile('attachment')) {
			$attachmentUrl = $request->file('attachment')->store('attachments', 'public');
		}

		$newPost = Post::create([
			'title' => $validateReq['title'] ?? null,
			'body' => $validateReq['body'],
			'user_id' => $request->user()['id'],
			'attachment_url' => $attachmentUrl,
		]);

		return response()->json($newPost, 201);
	}

	public function show(int $postID, Request $request) {
		$post = Post::query()->where('id', $postID)->get()->first();

		if (!$post) return response()->json(['message' => "not found"], 404);

		$post->addRegularPostInfo($request->user()['id']);

		return response()->json($post);
	}

	public function update(Request $request, Post $post) {
		Gate::authorize('update', $post);

		$validateReq = $request->validate([
			'title' => ['nullable', 'string', 'min:3', 'max:255'],
			'body' => ['required', 'string'],
			'attachment' => ['nullable', 'file']
		]);

		$post->update($validateReq);

		return response()->json(['message' => 'post has been updated']);
	}

	public function destroy(Post $post) {
		Gate::authorize('destroy', $post);

		$post->delete();

		return response()->json(['message' => 'Post has been deleted']);
	}
}
