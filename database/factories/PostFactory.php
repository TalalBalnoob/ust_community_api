<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory {
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array {
		$users_list = User::all('id');
		$randomNumber = random_int(1, 10);
		return [
			'user_id' => $users_list->random(),
			// 3 of 10 chance to get a post with title 
			'title' => $randomNumber > 5 && $randomNumber < 9 ? fake()->words($randomNumber, true) : null,
			'body' => fake()->realTextBetween(50, 250),
		];
	}
}
