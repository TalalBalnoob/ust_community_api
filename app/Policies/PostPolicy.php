<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PostPolicy {
	public function destroy(User $user, Post $post): Response {
		return $post['user_id'] === $user['id'] || $user['isAdmin'] ? Response::allow() : Response::deny('Unauthorized action');
	}

	public function update(User $user, Post $post): Response {
		return $post['user_id'] === $user['id'] ? Response::allow() : Response::deny('Unauthorized action');
	}
}
