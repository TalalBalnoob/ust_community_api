<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory {
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array {
		return [
			'displayName' => fake()->firstName() . ' ' . fake()->lastName(),
			'department' => 'Engineering and Computer Science',
			'major' => fake()->randomElement(['Information Technology', 'Architecture']),
			'level' => fake()->randomElement([1, 2, 3, 4]),
			'user_id' => User::factory()->create([
				'user_type_id' => 1
			])
		];
	}
}
