<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use App\Notifications\LikePostNotification;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function like(Request $request, string $post_id)
    {
        $isPostExist = Post::query()->find($post_id);
        $isLiked = Like::query()->where('user_id', $request->user()['id'])->where('post_id', $post_id)->first();

        if ($isLiked) {
            return response()->json(['message' => 'already liked'], 409);
        }
        if (!$isPostExist) {
            return response()->json(['message' => 'post dose not exist'], 404);
        }

        Like::create([
            'user_id' => $request->user()['id'],
            'post_id' => $isPostExist['id']
        ]);

        $post_ownerID = $isPostExist->user()->get()->first()['id'];
        $post_owner = User::query()->get()->where('id', $post_ownerID)->first();
        $post_owner->notify(new LikePostNotification($request->user()->profile()->displayName, $request->user()['id'], $post_id));

        return response()->json(['message' => 'Post has been liked']);
    }

    public function unlike(Request $request, string $post_id)
    {
        $isLiked = Like::query()->where('user_id', $request->user()['id'])->where('post_id', $post_id)->first();

        if (!$isLiked) {
            return response()->json(['message' => 'like not found'], 404);
        }

        $isLiked->delete();

        return response()->json(['message' => 'like has been deleted']);
    }
}
