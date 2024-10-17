<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommentPolicy {
	public function destroy(User $user, Comment $comment): Response {
		return $comment['user_id'] === $user['id'] || $user['isAdmin'] ? Response::allow() : Response::deny('Unauthorized action');
	}

	public function update(User $user, Comment $comment): Response {
		return $comment['user_id'] === $user['id'] ? Response::allow() : Response::deny('Unauthorized action');
	}
}
