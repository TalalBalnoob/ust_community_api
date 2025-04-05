<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Notifications\CommentNotification;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class CommentController extends Controller
{
    public function index(Post $post)
    {
        $comments = $post->comments()->with('user')->get();

        $comments = $comments->map(function ($comment) {
            $comment->user->profile = User::addUserProfileInfo($comment->user_id);
            return $comment;
        });

        return Response()->json($comments);
    }

    public function store(Request $request, Post $post)
    {
        // Validate the request input
        $validated = $request->validate([
            'body' => ['required', 'string'],
            'attachment' => ['nullable', 'file']
        ]);

        // Handle attachment upload if present
        $attachmentUrl = null;
        if ($request->hasFile('attachment')) {
            $attachmentUrl = $request->file('attachment')->store('attachments', 'public');
        }

        // Create a new comment using mass assignment
        $newComment = Comment::create([
            'user_id' => $request->user()->id,
            'post_id' => $post->id,
            'body' => $validated['body'],
            'attachment_url' => $attachmentUrl,
        ]);

        // Add user profile info to the comment
        $newComment->user = User::query()->where('id', $request->user()->id)->get()->first();
        $newComment->user->profile = User::addUserProfileInfo($request->user()->id);

        $post_ownerID = $post->user()->get()->first()['id'];
        $post_owner = User::query()->get()->where('id', $post_ownerID)->first();
        $post_owner->notify(new CommentNotification($request->user()->profile()->displayName, $post->id));

        // Return a JSON response with the new comment
        return response()->json($newComment, 201);
    }

    public function update(Request $request, Post $post, Comment $comment)
    {
        // Authorize the update action
        Gate::authorize('update', $comment);

        // Validate the request input
        $validated = $request->validate([
            'body' => ['required', 'string'],
            'attachment' => ['nullable', 'file']
        ]);

        // Handle attachment upload if provided
        if ($request->hasFile('attachment')) {
            $validated['attachment_url'] = $request->file('attachment')->store('attachments', 'public');
        }

        // Update the comment
        $comment->update([
            'body' => $validated['body'],
            'attachment_url' => $validated['attachment_url'] ?? $comment->attachment_url,
        ]);

        // Return a success response
        return response()->json(['message' => 'Comment has been updated', 'comment' => $comment]);
    }

    public function destroy(Post $post, Comment $comment)
    {
        // Authorize the delete action
        Gate::authorize('destroy', $comment);

        if ($comment->attachment_url) {
            Storage::disk('public')->delete($comment->attachment_url);
        }

        // Delete the comment
        $comment->delete();

        // Return a success response
        return response()->json(['message' => 'Comment has been deleted'], 200);
    }
}
