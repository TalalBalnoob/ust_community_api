<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory {
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array {
		$users_list = User::all('id');
		$posts_list = Post::all('id');
		return [
			'user_id' => $users_list->random(),
			'parent_post' => $posts_list->random(),
			'body' => fake()->realTextBetween(50, 250),
		];
	}
}
