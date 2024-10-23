<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller {

	public function index(Post $post) {
		$comments = $post->comments()->get();

		foreach ($comments->all() as $comment) {
			$comment['user'] = User::addUserProfileInfo($comment['user_id']);
		}

		return Response(['comments' => $comments]);
	}

	public function store(Request $request, Post $post) {
		$validateReq = $request->validate([
			'body' => ['required', 'string'],
			'attachment' => ['nullable', 'file']
		]);

		$newComment = new Comment();

		$newComment['user_id'] = $request->user()['id'];
		$newComment['post_id'] = $post['id'];
		$newComment['body'] = $validateReq['body'];
		$newComment['attachment_url'] = null;

		$newComment->save();

		return response(['comment' => $newComment]);
	}

	public function update(Request $request, Post $post, Comment $comment) {
		Gate::authorize('update', $comment);

		$validateReq = $request->validate([
			'body' => ['required', 'string'],
			'attachment' => ['nullable', 'file']
		]);

		$comment->update($validateReq);

		return response(['message' => 'Comment has been updated']);
	}

	public function destroy(Post $post, Comment $comment) {
		Gate::authorize('destroy', $comment);

		$comment->delete();

		return response(['message' => 'Comment has been deleted']);
	}
}
