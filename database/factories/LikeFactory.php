<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Like>
 */
class LikeFactory extends Factory {
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array {
		$users_list = User::all('id');
		$posts_list = Post::all('id');

		$user = $users_list->random();
		$post = $posts_list->random();

		return [
			'user_id' => $user,
			'post_id' => $post
		];
	}
}
