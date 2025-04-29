<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Bookmark;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    public function getUserBookmarks(Request $request)
    {
        $user = User::query()->find($request->user()['id']);
        if (!$user) {
            return response()->json(['message' => 'user not found'], 404);
        }

        $bookmarks = Bookmark::query()->where('user_id', $user['id'])->with('post')->get();

        return response()->json($bookmarks);
    }

    public function book(Request $request, string $post_id)
    {
        $isPostExist = Post::query()->find($post_id);
        $isBooked = Bookmark::query()->where('user_id', $request->user()['id'])->where('post_id', $post_id)->first();

        if ($isBooked) {
            return response()->json(['message' => 'already booked'], 409);
        }
        if (!$isPostExist) {
            return response()->json(['message' => 'post dose not exist'], 404);
        }

        Bookmark::create([
            'user_id' => $request->user()['id'],
            'post_id' => $isPostExist['id']
        ]);


        return response()->json(['message' => 'Post has been booked']);
    }

    public function unbook(Request $request, string $post_id)
    {
        $isBooked = Bookmark::query()->where('user_id', $request->user()['id'])->where('post_id', $post_id)->first();

        if (!$isBooked) {
            return response()->json(['message' => 'bookmark not found'], 404);
        }

        $isBooked->delete();

        return response()->json(['message' => 'bookmark has been deleted']);
    }
}
