<?php

namespace Database\Factories;

use App\Models\Major;
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
		$major = Major::all()->random();
		return [
			'displayName' => fake()->firstName() . ' ' . fake()->lastName(),
			'major_id' => $major['id'],
			'level' => random_int(1, $major['years_of_study']),
			'user_id' => User::factory()->create([
				'user_type_id' => 1
			])
		];
	}
}
