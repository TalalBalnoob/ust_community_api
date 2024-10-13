<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class LikeController extends Controller {
	public function like(Request $request, string $post_id) {
		$isPostExist = Post::query()->find($post_id);
		$isLiked = Like::query()->where('user_id', $request->user()['id'])->where('post_id', $post_id)->first();

		if ($isLiked) abort(400, 'bad request');
		if (!$isPostExist) abort(400, 'bad request');

		$newLike = new Like([
			'user_id' => $request->user()['id'],
			'post_id' => $isPostExist['id']
		]);

		$newLike->save();

		return response(['message' => 'like has been added']);
	}

	public function unlike(Request $request, string $post_id) {
		$isLiked = Like::query()->where('user_id', $request->user()['id'])->where('post_id', $post_id)->first();

		if (!$isLiked) abort(404, 'like not found');

		$isLiked->delete();

		return response(['message' => 'like has been deleted']);
	}
}
