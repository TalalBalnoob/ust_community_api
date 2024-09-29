<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Staff>
 */
class StaffFactory extends Factory {
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array {
		return [
			'displayName' => fake()->firstName() . ' ' . fake()->lastName(),
			'department' => 'Engineering and Computer Science',
			'role' => fake()->randomElement(['lecturer', 'head of department']),
			'user_id' => User::factory()->create([
				'user_type_id' => 2
			])
		];
	}
}
