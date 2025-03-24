<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use App\Notifications\NewMediaNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class LikeController extends Controller
{
    public function like(Request $request, string $post_id)
    {
        $isPostExist = Post::query()->find($post_id);
        $isLiked = Like::query()->where('user_id', $request->user()['id'])->where('post_id', $post_id)->first();

        if ($isLiked) {
            response()->json('already liked', 409);
        }
        if (!$isPostExist) {
            response()->json('post dose not exist', 404);
        }

        Like::create([
            'user_id' => $request->user()['id'],
            'post_id' => $isPostExist['id']
        ]);

        $postOwner = User::query()->find($isPostExist->user_id);
        $postOwner->notify(new NewMediaNotification($postOwner->username));

        return response()->json(['message' => 'Post has been liked']);
    }

    public function unlike(Request $request, string $post_id)
    {
        $isLiked = Like::query()->where('user_id', $request->user()['id'])->where('post_id', $post_id)->first();

        if (!$isLiked) {
            response()->json('like not found', 404);
        }

        $isLiked->delete();

        return response()->json(['message' => 'like has been deleted']);
    }
}
