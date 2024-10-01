<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Follower>
 */
class FollowerFactory extends Factory {
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array {
		$users_list = User::all('id');
		$user1 = null;
		$user2 = null;

		do {
			$user1 = $users_list->random();
			$user2 = $users_list->random();
		} while ($user1 == $user2);

		return [
			'follower_id' => $user1,
			'followed_id' => $user2
		];
	}
}
